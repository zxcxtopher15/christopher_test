<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MyModel');
        $this->load->helper('security');
    }

    public function index()
    {
        $this->load->view('home');
    }

    public function registration()
    {
        $this->load->view('register');
    }

    public function login()
    {
        $this->load->view('login');
    }

    public function dashboard()
    {
        $data['users'] = $this->MyModel->get_users();
        $this->load->view('dashboard', $data);
    }

    public function register()
    {
        if ($this->input->post('register')) {

            $uname = $this->input->post('uname');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->MyModel->is_username_exists($uname)) {
                echo json_encode(array('status' => 'error', 'message' => 'Username already exists.'));
                exit();
            }

            if ($this->MyModel->is_email_exists($email)) {
                echo json_encode(array('status' => 'error', 'message' => 'Email already exists.'));
                exit();
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $data = array(
                'username' => $uname,
                'email' => $email,
                'password' => $hashed_password,
            );
            $data = $this->security->xss_clean($data);
            
            $user_id = $this->MyModel->insert_user($data);

            if ($user_id) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Registration failed.'));
            }
            exit();
        } else {
            $this->load->view('register');
        }
    }

    public function loginfunction()
    {
        $username = $this->input->post('uname');
        $password = $this->input->post('password');

        $user = $this->MyModel->get_user_by_username($username);

        if ($user) {
            $stored_hashed_password = $user->password;

            if (password_verify($password, $stored_hashed_password)) {
                $this->session->set_userdata('logged_in', true);
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Invalid password.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'User not found.'));
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        echo json_encode(array('status' => 'success'));
    }

    public function delete_user()
    {
        $user_id = $this->input->post('id');
        $result = $this->MyModel->delete_user($user_id);
        echo json_encode(['success' => $result]);
    }

    public function update_user()
    {
        $user_id = $this->input->post('id');
        $username = $this->input->post('username');
        $email = $this->input->post('email');

        $result = $this->MyModel->update_user($user_id, $username, $email);

        echo json_encode(['success' => $result]);
    }
}
