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

    public function edit()
    {
        $email = $this->session->userdata("email");
        $data["title"] = "Edit Profile";
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();

        $this->form_validation->set_rules("name", "Name", "required|trim");

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("layout/sidebar", $data);
            $this->load->view("layout/topbar", $data);
            $this->load->view("user/edit", $data);
            $this->load->view("layout/footer",);
        } else {
            $name = $this->input->post("name");
            $email = $this->input->post("email");

            // cek jika ada gambar yang akan di upload ###################
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']     = '2048';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {

                    // menghapus foto yang pernah di upload oleh user @@
                    $old_image = $data["user"]["image"];
                    if ($old_image != "default.jpg") {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    } // @@

                    $new_image = $this->upload->data("file_name");
                    $this->db->set("image", $new_image);
                } else {
                    echo $this->upload->display_errors();
                }
            } //############################################################

            // $this->db->set("name", $name); bisa dengan cara ini juga dari pa dika
            $this->db->where('email', $email);
            $this->db->update("user", ["name" => $name]); // bisa juga dengan cara ini yang simpel buatan saya

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Your profile has been update!
                  </div>');
            redirect('user');
        }
    }
}
