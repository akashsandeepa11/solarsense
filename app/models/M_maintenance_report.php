<?php

class M_maintenance_report {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Insert new report
    public function insertReport($data){
        $this->db->query("
            INSERT INTO service_report 
            (task_id, agent_id, actions_taken, replaced_parts, time_spent, completion_date, technician_notes, final_status)
            VALUES 
            (:task_id, :agent_id, :actions_taken, :replaced_parts, :time_spent, :completion_date, :technician_notes, :final_status)
        ");

        $this->db->bind(':task_id', $data['task_id']);
        $this->db->bind(':agent_id', $data['agent_id']);
        $this->db->bind(':actions_taken', $data['actions_taken']);
        $this->db->bind(':replaced_parts', $data['replaced_parts']);
        $this->db->bind(':time_spent', $data['time_spent']);
        $this->db->bind(':completion_date', $data['completion_date']);
        $this->db->bind(':technician_notes', $data['technician_notes']);
        $this->db->bind(':final_status', $data['final_status']);

        return $this->db->execute();
    }

    public function insertReportAndCompleteTask($data){
        try {
            $this->db->beginTransaction();

            $this->db->query("
                INSERT INTO service_report 
                (task_id, agent_id, actions_taken, replaced_parts, time_spent, completion_date, technician_notes, final_status)
                VALUES 
                (:task_id, :agent_id, :actions_taken, :replaced_parts, :time_spent, :completion_date, :technician_notes, :final_status)
            ");

            $this->db->bind(':task_id', $data['task_id']);
            $this->db->bind(':agent_id', $data['agent_id']);
            $this->db->bind(':actions_taken', $data['actions_taken']);
            $this->db->bind(':replaced_parts', $data['replaced_parts']);
            $this->db->bind(':time_spent', $data['time_spent']);
            $this->db->bind(':completion_date', $data['completion_date']);
            $this->db->bind(':technician_notes', $data['technician_notes']);
            $this->db->bind(':final_status', $data['final_status']);

            if(!$this->db->execute()){
                $this->db->rollBack();
                return false;
            }

            $this->db->query("
                UPDATE service_req
                SET status = 'Completed'
                WHERE task_id = :task_id
                  AND agent_id = :agent_id
            ");

            $this->db->bind(':task_id', $data['task_id']);
            $this->db->bind(':agent_id', $data['agent_id']);

            if(!$this->db->execute()){
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('M_maintenance_report::insertReportAndCompleteTask failed: ' . $e->getMessage());
            return false;
        }
    }

    // OPTIONAL (future)
    public function getReportByTaskId($task_id){
        $this->db->query("SELECT * FROM service_report WHERE task_id = :task_id");
        $this->db->bind(':task_id', $task_id);
        return $this->db->single();
    }
}
?>