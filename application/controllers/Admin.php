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
        $this->load->view("layout/footer");
    }

    public function role()
    {
        $email = $this->session->userdata("email");

        $data["title"] = "Role";
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();
        $data["user_role"] = $this->db->get('user_role')->result_array();

        $this->load->view("layout/header", $data);
        $this->load->view("layout/sidebar", $data);
        $this->load->view("layout/topbar", $data);
        $this->load->view("admin/role", $data);
        $this->load->view("layout/footer");
    }

    public function roleAccess($role_id)
    {
        $email = $this->session->userdata("email");
        $data["title"] = "Role";
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();

        $data["user_role"] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        // $this->db->where('id !=',1); kalo dari pa dika sih gini,tapi saya coba iseng pake get_Where ternyata bisa
        $data["user_menu"] = $this->db->get_where('user_menu', ['id !=' => 1])->result_array();

        $this->load->view("layout/header", $data);
        $this->load->view("layout/sidebar", $data);
        $this->load->view("layout/topbar", $data);
        $this->load->view("admin/role-access", $data);
        $this->load->view("layout/footer");
    }

    public function changeaccess()
    {
        $role_id = $this->input->post("roleId");
        $menu_id = $this->input->post("menuId");

        $data = [
            "role_id" => $role_id,
            "menu_id" => $menu_id
        ];
        // cari di chatgpt gimana cara kerja ini, query nya atau apa nya
        $result = $this->db->get_where("user_access_menu", $data);

        if ($result->num_rows() < 1) {
            $this->db->insert("user_access_menu", $data);
        } else {
            $this->db->delete("user_access_menu", $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Access changed!
      </div>');
    }
}
