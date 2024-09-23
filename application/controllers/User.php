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
                    Your profile has been update !
                  </div>');
            redirect('user');
        }
    }

    public function changepassword()
    {
        $email = $this->session->userdata("email");
        $data["title"] = "Change Password";
        $data["user"] = $this->db->get_where("user", ["email" => $email])->row_array();

        $this->form_validation->set_rules("current_password", "Old Password", "required|trim");
        $this->form_validation->set_rules("new_password1", "New Password", "required|trim|matches[new_password2]|min_length[3]");
        $this->form_validation->set_rules("new_password2", "Repeat Password", "required|trim|matches[new_password1]");

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("layout/sidebar", $data);
            $this->load->view("layout/topbar", $data);
            $this->load->view("user/changepassword", $data);
            $this->load->view("layout/footer",);
        } else {
            $current_password = $this->input->post("current_password");
            $new_password1 = $this->input->post("new_password1");

            // cek passwordnya sama ga kaya di database
            if (!password_verify($current_password, $data["user"]["password"])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Current password is wrong !
                 </div>');
                redirect('user/changepassword');
            } else {

                // cek dulu password baru nya sama ga sama password lama, kalo sama artinya gagal
                if ($current_password == $new_password1) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    New password cannot be the same as current password !
                    </div>');
                    redirect('user/changepassword');
                } else {
                    // password suadh ok
                    $password_ok = password_hash($new_password1, PASSWORD_DEFAULT);

                    $this->db->set("password", $password_ok);
                    $this->db->where("email", $email);
                    $this->db->update("user");

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password changed !
                    </div>');
                    redirect('user/changepassword');
                }
            }
        }
    }
}
