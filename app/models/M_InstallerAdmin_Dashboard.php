<?php
class M_InstallerAdmin_Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Get summary statistics for the Installer Admin Dashboard
     * * @param int $companyId
     * @return array
     */
    public function getStats($companyId)
    {
        // 1. Total Active Clients
        $this->db->query("SELECT COUNT(*) as total FROM homeowner WHERE company_id = :company_id");
        $this->db->bind(':company_id', $companyId);
        $activeClients = $this->db->single()->total ?? 0;

        // 2. Critical Faults: Use single quotes for 'Pending'
        $this->db->query("
        SELECT COUNT(*) as total FROM (
            SELECT s1.user_id
            FROM sms s1
            JOIN (SELECT user_id, MAX(reading_date) as max_date FROM sms GROUP BY user_id) s2 
                ON s1.user_id = s2.user_id AND s1.reading_date = s2.max_date
            JOIN homeowner h ON s1.user_id = h.user_id
            WHERE h.company_id = :company_id 
            AND s1.export_reading < (s1.expected_generation * 0.50)
        ) as critical_count
    ");
        $this->db->bind(':company_id', $companyId);
        $criticalFaults = $this->db->single()->total ?? 0;

        // 3. Underperforming: Latest generation thresholds
        $this->db->query("
        SELECT COUNT(*) as total FROM (
            SELECT s1.user_id
            FROM sms s1
            JOIN (SELECT user_id, MAX(reading_date) as max_date FROM sms GROUP BY user_id) s2 
                ON s1.user_id = s2.user_id AND s1.reading_date = s2.max_date
            JOIN homeowner h ON s1.user_id = h.user_id
            WHERE h.company_id = :company_id 
            AND s1.export_reading >= (s1.expected_generation * 0.50)
            AND s1.export_reading < (s1.expected_generation * 0.85)
        ) as underperforming_count
    ");
        $this->db->bind(':company_id', $companyId);
        $underperforming = $this->db->single()->total ?? 0;

        // 4. Pending Tasks: Fixed query with single quotes for 'Pending'
        $this->db->query("
        SELECT COUNT(*) as total 
        FROM service_req sr 
        JOIN homeowner h ON sr.homeowner_id = h.user_id 
        WHERE h.company_id = :company_id AND sr.status = 'Pending'
    ");
        $this->db->bind(':company_id', $companyId);
        $pendingTasks = $this->db->single()->total ?? 0;

        // 5. Active Service Agents: Fixed query with single quotes for 'Available'
        $this->db->query("SELECT COUNT(*) as total FROM service_agent WHERE company_id = :company_id AND status = 'Available'");
        $this->db->bind(':company_id', $companyId);
        $activeAgents = $this->db->single()->total ?? 0;

        // 6. Total Energy Generated
        $this->db->query("
        SELECT SUM(s.export_reading) as total 
        FROM sms s
        JOIN homeowner h ON s.user_id = h.user_id
        WHERE h.company_id = :company_id
    ");
        $this->db->bind(':company_id', $companyId);
        $totalKwh = $this->db->single()->total ?? 0;

        return [
            'active_clients' => $activeClients,
            'critical_faults' => $criticalFaults,
            'underperforming' => $underperforming,
            'pending_tasks' => $pendingTasks,
            'active_agents' => $activeAgents,
            'total_energy' => round($totalKwh / 1000, 2),
            'pending_complaints' => 0
        ];
    }

    // Add this method to M_InstallerAdmin_Dashboard.php
    public function getAlerts($companyId)
    {
        $this->db->query("
        SELECT 
            u.email,
            s1.export_reading,
            s1.expected_generation,
            CASE 
                WHEN s1.export_reading < (s1.expected_generation * 0.50) THEN 'high'
                WHEN s1.export_reading < (s1.expected_generation * 0.85) THEN 'medium'
            END AS priority,
            CASE 
                WHEN s1.export_reading < (s1.expected_generation * 0.50) THEN 'Critical Fault (Generation < 50%)'
                WHEN s1.export_reading < (s1.expected_generation * 0.85) THEN 'Underperforming (Generation < 85%)'
            END AS issue
        FROM sms s1
        JOIN (SELECT user_id, MAX(reading_date) as max_date FROM sms GROUP BY user_id) s2 
            ON s1.user_id = s2.user_id AND s1.reading_date = s2.max_date
        JOIN homeowner h ON s1.user_id = h.user_id
        JOIN user u ON s1.user_id = u.user_id
        WHERE h.company_id = :company_id 
        HAVING priority IS NOT NULL
        ORDER BY priority DESC
    ");

        $this->db->bind(':company_id', $companyId);
        return $this->db->resultSet();
    }

    /**
     * Get the best and worst performing systems based on latest generation data
     */
    public function getPerformanceSnapshot($companyId, $limit = 3)
    {
        // Base query to get the latest performance data for all systems in the company
        $sql = "
        SELECT 
            u.full_name,
            ROUND((s1.export_reading / s1.expected_generation) * 100, 1) as performance
        FROM sms s1
        JOIN (SELECT user_id, MAX(reading_date) as max_date FROM sms GROUP BY user_id) s2 
            ON s1.user_id = s2.user_id AND s1.reading_date = s2.max_date
        JOIN homeowner h ON s1.user_id = h.user_id
        JOIN user u ON s1.user_id = u.user_id
        WHERE h.company_id = :company_id
    ";

        // Fetch Best Performers (Descending)
        $this->db->query($sql . " ORDER BY performance DESC LIMIT :limit");
        $this->db->bind(':company_id', $companyId);
        $this->db->bind(':limit', $limit);
        $best = $this->db->resultSet();

        // Fetch Worst Performers (Ascending)
        $this->db->query($sql . " ORDER BY performance ASC LIMIT :limit");
        $this->db->bind(':company_id', $companyId);
        $this->db->bind(':limit', $limit);
        $worst = $this->db->resultSet();

        return [
            'best' => $best,
            'worst' => $worst
        ];
    }

    /**
     * Get the current status of all service agents in the company
     */
    public function getServiceTeamStatus($companyId)
    {
        $this->db->query("
        SELECT 
            u.full_name as name,
            sa.status
        FROM service_agent sa
        JOIN user u ON sa.user_id = u.user_id
        WHERE sa.company_id = :company_id
        ORDER BY sa.status ASC, u.full_name ASC
    ");

        $this->db->bind(':company_id', $companyId);
        return $this->db->resultSet();
    }

    /**
     * Get new customer registration counts for the last 6 months
     */
    public function getNewCustomersChartData($companyId)
    {
        $this->db->query("
        SELECT 
            DATE_FORMAT(register_date, '%b') AS month_label,
            COUNT(user_id) AS customer_count,
            DATE_FORMAT(register_date, '%Y-%m') AS sort_key
        FROM homeowner
        WHERE company_id = :company_id 
          AND register_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY sort_key, month_label
        ORDER BY sort_key ASC
    ");

        $this->db->bind(':company_id', $companyId);
        return $this->db->resultSet();
    }

    /**
     * Get the count of service tasks grouped by status for the doughnut chart
     */
    public function getServiceTasksChartData($companyId)
    {
        $this->db->query("
        SELECT 
            sr.status,
            COUNT(sr.task_id) as task_count
        FROM service_req sr
        JOIN homeowner h ON sr.homeowner_id = h.user_id
        WHERE h.company_id = :company_id
        GROUP BY sr.status
    ");

        $this->db->bind(':company_id', $companyId);
        return $this->db->resultSet();
    }
}