<?php

require_once '../models/User.php';

class Users extends Controller {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->userModel = new UserModel($this->db);
    }

    // Show login page + handle login POST
    public function login() {
        $data = [
            'email' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $data['email'] = $email;

            // Check if user exists
            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // ✅ Login success → create session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                header("Location: " . URLROOT . "/dashboard");
                exit;
            } else {
                $data['error'] = "Invalid email or password.";
            }
        }

        // Load the login view
        $this->view('pages/users/login', $data, layout: "main");
    }

    // Show register page + handle registration
    public function register() {
        $data = [
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if ($this->userModel->register($email, $password)) {
                $data['success'] = "User registered successfully!";
            } else {
                $data['error'] = "Registration failed.";
            }
        }

        $this->view('pages/auth/login', $data, layout: "main");
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location: " . URLROOT . "/users/login");
        exit;
    }
}
