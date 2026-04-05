<?php
// models/SolarSystemModel.php

class M_Solarsystem
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Fetch one system by its primary key
    public function findById(int $user_id): ?array
    {
        $this->db->query('SELECT * FROM solar_system WHERE user_id = :user_id LIMIT 1');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single_assoc();
        return $row ?: null;
    }

    // Fetch all systems belonging to a particular owner
    public function findByOwner(int $userId): array
    {
        $this->db->query('SELECT * FROM solar_system WHERE user_id = :user_id ORDER BY id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Fetch all systems (admin / batch use)
    public function findAll(): array
    {
        $this->db->query("
        SELECT * FROM solar_system ORDER BY id
        ");
        return $this->db->resultSet();
    }

    // Insert a new system record and return the new ID
    // public function create(array $data): int
    // {
    //     $this->db->query('
    //         INSERT INTO solar_system
    //             (user_id, district, capacity, tilt, azimuth,
    //              module_type, array_type, losses_pct, dc_ac_ratio, inv_eff_pct)
    //         VALUES
    //             (:user_id, :district, :system_capacity_kw, :tilt_deg, :azimuth_deg,
    //              :module_type, :array_type, :losses_pct, :dc_ac_ratio, :inv_eff_pct)
    //     ');

    //     $this->db->bind(':user_id',           $_SESSION['user_id']);
    //     $this->db->bind(':district',           $data['district']);
    //     $this->db->bind(':system_capacity_kw', $data['system_capacity_kw']);
    //     $this->db->bind(':tilt_deg',           $data['tilt_deg']);
    //     $this->db->bind(':azimuth_deg',        $data['azimuth_deg']);
    //     $this->db->bind(':module_type',        $data['module_type']  ?? DEFAULT_MODULE_TYPE);
    //     $this->db->bind(':array_type',         $data['array_type']   ?? DEFAULT_ARRAY_TYPE);
    //     $this->db->bind(':losses_pct',         $data['losses_pct']   ?? DEFAULT_LOSSES);
    //     $this->db->bind(':dc_ac_ratio',        $data['dc_ac_ratio']  ?? DEFAULT_DC_AC_RATIO);
    //     $this->db->bind(':inv_eff_pct',        $data['inv_eff_pct']  ?? DEFAULT_INV_EFF);

    //     $this->db->execute();
    //     return (int) $this->db->lastInsertId();
    // }
}