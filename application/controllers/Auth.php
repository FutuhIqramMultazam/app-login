<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    // ##### constrac ini sudah tidak di butuhkan karena sudah di autoload di folder config/autoload.php > libraries #####
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->load->library('form_validation');
    // }  

    public function index()
    {

        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
            'valid_email' => "This is not an email"
        ], true);
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data["title"] = "Login Page";
            $this->load->view('layout/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('layout/auth_footer');
        } else {
            // ketika validasinya success
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        // ini dibacanya gini, select * from user where email = $email;

        // jika usernya ada
        if ($user) {
            // jika usernya aktif
            if ($user["is_active"] == 1) {
                // cek password 
                if (password_verify($password, $user["password"])) {
                    $data = [
                        'email' => $user["email"],
                        'role_id' => $user["role_id"]
                    ];
                    $this->session->set_userdata($data);
                    if ($user["role_id"] == 1) {
                        redirect("admin");
                    } else {
                        redirect("user");
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Wrong password!
                  </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            This email has not been activated!
          </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email is not registered!
          </div>');
            redirect('auth');
        }
    }


    public function registration()
    {

        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email has already been registered here'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'password dont match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data["title"] = "User Registation";
            $this->load->view('layout/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('layout/auth_footer');
        } else {
            $data = [
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);

            // $this->_sendEmail();

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Congratulations, you already have an account
          </div>');
            redirect('auth');
        }
    }

    // private function _sendEmail()
    // {
    //     $config = [
    //         "protocol" => "smtp",
    //         "smtp_host" => "ssl://smtp.googlemail.com",
    //         "smtp_user" => "icamganteng@itg.ac.id",
    //         "smtp_pass" => "nihpasswordku",
    //         "smtp_port" => 465,
    //         "mailtype" => "html",
    //         "charset" => "utf-8",
    //         "newline" => "\r\n",
    //     ];

    //     $this->load->library('email', $config);

    //     $this->email->from("icamganteng@itg.ac.id", "Futuh Iqram multazam");
    //     $this->email->to("example@gmail.com");
    //     $this->email->subject("testing");
    //     $this->email->message("hello World");

    //     if ($this->email->send()) {
    //         return true;
    //     } else {
    //         echo $this->email->print_debugger();
    //         die;
    //     }
    // }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            You have been logged out!
          </div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view("layout/header");
        $this->load->view("auth/blocked");
        $this->load->view("layout/footer");
    }
}
