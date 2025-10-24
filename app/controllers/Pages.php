<?php
    class Pages extends Controller{
        public function index(){
            // Changed to load the landing page as the default view
            $this->view('pages/landing');
        }

        public function about(){
            $this->view('pages/about');
        }

        public function landing(){
            $this->view('pages/landing');
        }

        // Render 404 page
        public function error404(){
            $this->view('pages/404');
        }
    }
?>  