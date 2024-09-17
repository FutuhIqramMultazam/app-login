<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function index()
    {
        $data["title"] = "Dashboard";
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $this->load->view("layout/header", $data);
        $this->load->view("layout/sidebar", $data);
        $this->load->view("layout/topbar", $data);
        $this->load->view("admin/index", $data);
        $this->load->view("layout/footer",);
    }
}
