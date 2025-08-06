<?php 
 class Page extends Controller{
    public function __construct(){

    }

    public function about(){
        $data = [];

        $this->view('v_about', $data);
    }
 }
?>