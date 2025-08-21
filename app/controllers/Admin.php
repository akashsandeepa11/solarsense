<?php
class Admin extends Controller{
    private $user = [
        'role' => ROLE_ADMIN,
    ];

    public function __construct(){

    }

    public function dashboard(){
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/admin/dashboard', $data, layout: 'dashboard');
    }

    public function users(){
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/admin/users', $data, layout: 'users');
    }

    public function reports(){
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/admin/reports', $data, layout: 'reports');
    }
}
?>

