<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function index()
    {
        $data["title"] = "Menu Management";

        $email = $this->session->userdata("email");
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();

        $data["menu"] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules("menu", "New Menu", "required");

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("layout/sidebar", $data);
            $this->load->view("layout/topbar", $data);
            $this->load->view("menu/index", $data);
            $this->load->view("layout/footer",);
        } else {

            $data = ['menu' => $this->input->post('menu')];
            $this->db->insert('user_menu', $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            A new menu has been created
          </div>');
            redirect('menu');
        }
    }

    public function submenu()
    {
        $data["title"] = "SubMenu Management";

        $email = $this->session->userdata("email");
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();
        $data["menu"] = $this->db->get('user_menu')->result_array();

        $this->load->model("Model_SubMenu");
        $data["SubMenu"] = $this->Model_SubMenu->getSubMenu();

        $this->form_validation->set_rules("menu_id", "Menu_id", "required|numeric");
        $this->form_validation->set_rules("title", "Title", "required");
        $this->form_validation->set_rules("url", "Url", "required");
        $this->form_validation->set_rules("icon", "Icon", "required");
        $this->form_validation->set_rules("is_active", "Is_active", "required|numeric");

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("layout/sidebar", $data);
            $this->load->view("layout/topbar", $data);
            $this->load->view("menu/submenu", $data);
            $this->load->view("layout/footer",);
        } else {

            $data = [
                "menu_id" => $this->input->post("menu_id"),
                "title" => $this->input->post("title"),
                "url" => $this->input->post("url"),
                "icon" => $this->input->post("icon"),
                "is_active" => $this->input->post("is_active")
            ];

            $this->db->insert("user_sub_menu", $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            A new SubMenu has been created
          </div>');
            redirect('menu');
        }
    }


    public function delete($id)
    {
        $this->db->delete('user_menu', ['id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Menu has been deleted
          </div>');
        redirect('menu');
    }
}
