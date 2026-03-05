<?php
class M_Auth{
    private $db;
    private $stmt;

    public function __construct(){
        $this->db = new Database();
    }

    // login user
    public function login($email, $password){
        $this->db->query("SELECT * FROM user WHERE email = :email");
        $this->db->bind(':email', value: $email);

        $row = $this->db->single();
        $hashed_password = $row->password;
        
        if(password_verify($password, $hashed_password)){
            return $row;
        }else{
            return false;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
    
        redirect('Users/login');
    }   

    //find the user
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        
        if($row){
            return true;
        }else{
            return false;
        }
    }

    public function add_installer_verification($companyData)
    {
        try {
            $this->db->beginTransaction();

            $this->db->query('
    INSERT INTO installer_company (
        company_name,
        address,
        num_employees,
        website,
        district,
        postal_code,
        register_date,
        contact,
        email,
        status,
        request_date,
        service_type,
        years_experience,
        complete_projects,
        service_areas
    ) VALUES (
        :company_name,
        :address,
        :num_employees,
        :website,
        :district,
        :postal_code,
        NOW(),
        :contact,
        :email,
        :status,
        NOW(),
        :service_type,
        :years_experience,
        :complete_projects,
        :service_areas
    )
');


            $this->db->bind(':company_name', $companyData['company_name']);
            $this->db->bind(':address', $companyData['address']);
            $this->db->bind(':num_employees', $companyData['number_of_employees']);
            $this->db->bind(':website', $companyData['website']);
            $this->db->bind(':district', $companyData['district']);
            $this->db->bind(':postal_code', $companyData['postal_code']);
            $this->db->bind(':contact', $companyData['contact_number']);
            $this->db->bind(':email', $companyData['email']);
            $this->db->bind(':status', 'Pending');
            $this->db->bind(':service_type', $companyData['service_type']);
            $this->db->bind(':years_experience', $companyData['years_of_experience']);
            $this->db->bind(':complete_projects', $companyData['completed_projects']);
            $this->db->bind(':service_areas', $companyData['service_areas']);

            $this->db->execute();
            $this->db->commit();

            return true;            

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            var_dump($e->getMessage());
            return false;
        }
    }

}
?>