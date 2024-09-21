<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $email = $this->session->userdata("email");
        $data["title"] = "My Profile";
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();

        $this->load->view("layout/header", $data);
        $this->load->view("layout/sidebar", $data);
        $this->load->view("layout/topbar", $data);
        $this->load->view("user/index", $data);
        $this->load->view("layout/footer",);
    }
}
