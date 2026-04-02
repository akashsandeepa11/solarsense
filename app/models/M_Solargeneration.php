<?php
require_once __DIR__ . '/../config/config.php';

class M_Solargeneration
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Save a new generation estimate; returns inserted ID
    public function save(array $data): int
    {
        $this->db->query('
            INSERT INTO solar_generation_estimates
                (system_id, date_from, date_to, ac_annual_kwh, ac_monthly_kwh,
                 capacity_factor, weather_source, pvwatts_raw, calculated_at)
            VALUES
                (:system_id, :date_from, :date_to, :ac_annual_kwh, :ac_monthly_kwh,
                 :capacity_factor, :weather_source, :pvwatts_raw, NOW())
        ');

        $this->db->bind(':system_id',       $data['system_id']);
        $this->db->bind(':date_from',       $data['date_from']);
        $this->db->bind(':date_to',         $data['date_to']);
        $this->db->bind(':ac_annual_kwh',   $data['ac_annual_kwh']);
        $this->db->bind(':ac_monthly_kwh',  json_encode($data['ac_monthly_kwh']));
        $this->db->bind(':capacity_factor', $data['capacity_factor']);
        $this->db->bind(':weather_source',  $data['weather_source']);
        $this->db->bind(':pvwatts_raw',     json_encode($data['pvwatts_raw']));

        $this->db->execute();
        return (int) $this->db->lastInsertId();
    }

    // Retrieve the latest estimate for a system
    public function findLatestBySystem(int $systemId): ?array
    {
        $this->db->query('
            SELECT * FROM solar_generation_estimates
            WHERE system_id = :system_id
            ORDER BY calculated_at DESC
            LIMIT 1
        ');
        $this->db->bind(':system_id', $systemId);

        $row = $this->db->single_assoc();
        if (!$row) return null;

        $row['ac_monthly_kwh'] = json_decode($row['ac_monthly_kwh'], true);
        $row['pvwatts_raw']    = json_decode($row['pvwatts_raw'],    true);
        return $row;
    }

    // Retrieve all estimates for a system (history)
    public function findAllBySystem(int $systemId): array
    {
        $this->db->query('
            SELECT * FROM solar_generation_estimates
            WHERE system_id = :system_id
            ORDER BY calculated_at DESC
        ');
        $this->db->bind(':system_id', $systemId);

        $rows = $this->db->resultSet();
        foreach ($rows as &$row) {
            $row['ac_monthly_kwh'] = json_decode($row['ac_monthly_kwh'], true);
            $row['pvwatts_raw']    = json_decode($row['pvwatts_raw'],    true);
        }
        return $rows;
    }
}