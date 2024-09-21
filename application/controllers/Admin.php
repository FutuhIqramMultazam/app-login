<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        /* if (!$this->session->userdata("email")) {
            redirect("auth");
        } 
        tadinya kan mau kay gini, tapi ini ribet, dan ga efektif, kita kan mau nya yang simpel, kan programmer, bukan penulis wkwk
        */

        is_logged_in(); /* fungsi ini saya yang bikin sendiri di helper */
    }


    public function index()
    {
        $email = $this->session->userdata("email");

        $data["title"] = "Dashboard";
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();

        $this->load->view("layout/header", $data);
        $this->load->view("layout/sidebar", $data);
        $this->load->view("layout/topbar", $data);
        $this->load->view("admin/index", $data);
        $this->load->view("layout/footer",);
    }
}
