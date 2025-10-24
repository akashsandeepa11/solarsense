<?php
$this->db->query("SELECT company_id AS companyId, company_name, address, contact, email, request_date, status FROM installer_company");