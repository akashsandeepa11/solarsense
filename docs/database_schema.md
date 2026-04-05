# SolarSense Database Schema Documentation

This document provides a comprehensive overview of the tables currently in the `solarsense` database, detailing their attributes, primary keys, foreign keys, relationships, and the current amount of data (row counts) within them.

> [!NOTE]
> Row counts reflect the database state at the time of documentation generation.

---

## Core Authenticated User Roles

### `user`
- **Description:** The central authentication table storing accounts for all types of users.
- **Current Rows:** 53
- **Primary Key:** `user_id` (AUTO_INCREMENT)
- **Columns:** `user_id`, `email`, `password`, `type`, `full_name`
- **Foreign Keys:** None

---

## Companies and Business Entities

### `installer_company`
- **Description:** Profiles and details of verified solar installation companies.
- **Current Rows:** 21
- **Primary Key:** `company_id` (AUTO_INCREMENT)
- **Columns:** `company_id`, `company_name`, `address`, `num_employees`, `website`, `district`, `postal_code`, `register_date`, `contact`, `email`, `status`, `request_date`, `service_type`, `years_experience`, `complete_projects`, `service_areas`
- **Foreign Keys:** None

### `prospective_installer_company`
- **Description:** Pending installer companies requesting access.
- **Current Rows:** 0
- **Primary Key:** `company_id` (AUTO_INCREMENT)
- **Columns:** `company_id`, `company_name`, `address`, `contact`, `email`, `request_date`, `status`
- **Foreign Keys:** None

---

## User Entities (IS-A Relationship with `user`)

### `homeowner`
- **Description:** Details of homeowners registered within the system to track solar panels and billing.
- **Current Rows:** 12
- **Primary Key:** `user_id`
- **Columns:** `user_id`, `company_id`, `address`, `contact`, `register_date`, `nic`, `district`, `ceb_account`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`
  - `company_id` references `installer_company(company_id)`

### `installer_admin`
- **Description:** Administrator users for specifically linked installer companies.
- **Current Rows:** 25
- **Primary Key:** `user_id`
- **Columns:** `user_id`, `company_id`, `register_date`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`
  - `company_id` references `installer_company(company_id)`

### `service_agent`
- **Description:** Support/maintenance agents tied to companies.
- **Current Rows:** 3
- **Primary Key:** `user_id`
- **Columns:** `user_id`, `company_id`, `address`, `contact`, `register_date`, `nic`, `district`, `specialization`, `experience_years`, `availability`, `certifications`, `status`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`
  - `company_id` references `installer_company(company_id)`

### `inventory_manager`
- **Description:** Managers tracking stock components.
- **Current Rows:** 0
- **Primary Key:** `user_id`
- **Columns:** `user_id`, `company_id`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`
  - `company_id` references `installer_company(company_id)`

### `operation_manager`
- **Description:** Operation managers governing general company operations.
- **Current Rows:** 0
- **Primary Key:** `user_id`
- **Columns:** `user_id`, `company_id`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`
  - `company_id` references `installer_company(company_id)`

### `superadmin`
- **Description:** Global site administrators.
- **Current Rows:** 0
- **Primary Key:** `user_id`
- **Columns:** `user_id`
- **Foreign Keys:** 
  - `user_id` references `user(user_id)`

### `prospective_homeowner`
- **Description:** People that requested a quotation but aren't fully registered.
- **Current Rows:** 0
- **Primary Key:** `user_id`
- **Columns:** `user_id`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`

---

## Services and Maintenance

### `service_type`
- **Description:** Lookup table for available repair and maintenance types.
- **Current Rows:** 3
- **Primary Key:** `service_type_id` (AUTO_INCREMENT)
- **Columns:** `service_type_id`, `type_name`
- **Foreign Keys:** None

### `service_req`
- **Description:** Service requests specifically mapping homeowners to service agents.
- **Current Rows:** 3
- **Primary Key:** `task_id` (AUTO_INCREMENT)
- **Columns:** `task_id`, `service_type_id`, `service_description`, `request_date`, `homeowner_id`, `agent_id`, `status`
- **Foreign Keys:**
  - `service_type_id` references `service_type(service_type_id)`
  - `homeowner_id` references `homeowner(user_id)`
  - `agent_id` references `service_agent(user_id)` *(ON DELETE SET NULL)*

### `service_task`
- **Description:** General tracking table for standard service activities.
- **Current Rows:** 7
- **Primary Key:** `task_id` (AUTO_INCREMENT)
- **Columns:** `task_id`, `service_type`, `service_description`, `request_date`, `user_id`, `status`, `agent_id`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`
  - `agent_id` references `service_agent(user_id)`

### `maintenance_request`
- **Description:** Homeowner generated tickets directly for their solar systems.
- **Current Rows:** 0
- **Primary Key:** `req_id` (AUTO_INCREMENT)
- **Columns:** `req_id`, `user_id`, `system_id`, `service_type`, `additional_details`, `timestamp`
- **Foreign Keys:**
  - `user_id` references `homeowner(user_id)`
  - `system_id` references `solar_system(system_id)`

---

## Solar Tracking

### `solar_system`
- **Description:** Metadata about physical installations belonging to homeowners.
- **Current Rows:** 11
- **Primary Key:** `system_id` (AUTO_INCREMENT)
- **Columns:** `system_id`, `user_id`, `capacity`, `tilt`, `azimuth`, `panel_brand`, `inverter_brand`, `installation_date`
- **Foreign Keys:**
  - `user_id` references `homeowner(user_id)`

### `fault`
- **Description:** Detected and logged hardware/inverter faults.
- **Current Rows:** 0
- **Primary Key:** `fault_id` (AUTO_INCREMENT)
- **Columns:** `fault_id`, `system_id`, `description`, `timestamp`
- **Foreign Keys:**
  - `system_id` references `solar_system(system_id)`

### `energy_report`
- **Description:** Monthly generated energy performance metrics.
- **Current Rows:** 0
- **Primary Key:** `report_id` (AUTO_INCREMENT)
- **Columns:** `report_id`, `system_id`, `month`, `total_energy_generated`, `profit_generated`, `report_file`
- **Foreign Keys:**
  - `system_id` references `solar_system(system_id)`

### `sms`
- **Description:** Parsed smart-meter / CEB bill logs recorded via messages.
- **Current Rows:** 15
- **Primary Key:** `sms_id` (AUTO_INCREMENT)
- **Columns:** `sms_id`, `user_id`, `created_at`, `balance_bf`, `reading_date`, `units_bf`, `export_reading`, `import_reading`, `prev_export_reading`, `prev_import_reading`, `consumption_units`, `monthly_bill`, `total_due`, `units_cf`, `raw_sms`
- **Foreign Keys:**
  - `user_id` references `user(user_id)`

---

## Inventory & Orders

### `item_categories`
- **Description:** Lookup classifications for stock categories.
- **Current Rows:** 0
- **Primary Key:** `id` (AUTO_INCREMENT)
- **Columns:** `id`, `name`
- **Foreign Keys:** None

### `inventory`
- **Description:** General stock and component details tracked by installer companies.
- **Current Rows:** 2
- **Primary Key:** `inventory_id` (AUTO_INCREMENT)
- **Columns:** `inventory_id`, `company_id`, `description`, `item_name`, `category_id`, `quantity`, `unit_price`, `buying_price`, `item_image`
- **Foreign Keys:**
  - `company_id` references `installer_company(company_id)`
  - `category_id` references `item_categories(id)`

### `orders`
- **Description:** Material requests/orders placed.
- **Current Rows:** 0
- **Primary Key:** `order_id` (AUTO_INCREMENT)
- **Columns:** `order_id`, `user_id`, `date`, `total_amount`, `status`
- **Foreign Keys:**
  - `user_id` references `homeowner(user_id)`

### `order_item`
- **Description:** Joins multiple inventory products under single orders.
- **Current Rows:** 0
- **Primary Key:** `order_item_id` (AUTO_INCREMENT)
- **Columns:** `order_item_id`, `order_id`, `inventory_id`, `quantity`
- **Foreign Keys:**
  - `order_id` references `orders(order_id)`
  - `inventory_id` references `inventory(inventory_id)`

---

## System Events

### `feedback`
- **Description:** Customer survey statements and reviews.
- **Current Rows:** 0
- **Primary Key:** `feedback_id` (AUTO_INCREMENT)
- **Columns:** `feedback_id`, `user_id`, `description`, `date`
- **Foreign Keys:**
  - `user_id` references `homeowner(user_id)`

### `notification`
- **Description:** Messages tied to automatic fault logging.
- **Current Rows:** 0
- **Primary Key:** `notification_id` (AUTO_INCREMENT)
- **Columns:** `notification_id`, `fault_id`, `message`, `timestamp`
- **Foreign Keys:**
  - `fault_id` references `fault(fault_id)`

### `email_logs`
- **Description:** System generated general outbound mail registry.
- **Current Rows:** 0
- **Primary Key:** `id` (AUTO_INCREMENT)
- **Columns:** `id`, `recipients`, `sent_at`
- **Foreign Keys:** None

### `quotation`
- **Description:** Handled proposals for prospective clients.
- **Current Rows:** 0
- **Primary Key:** `quotation_id` (AUTO_INCREMENT)
- **Columns:** `quotation_id`, `prospect_id`, `company_id`, `handled_by`, `description`, `date`, `status`
- **Foreign Keys:**
  - `prospect_id` references `prospective_homeowner(user_id)`
  - `company_id` references `installer_company(company_id)`
  - `handled_by` references `operation_manager(user_id)`
