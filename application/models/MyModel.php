<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyModel extends CI_Model
{
    public function insert_user($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function is_username_exists($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    public function is_email_exists($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    public function get_user_by_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function get_users()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    public function delete_user($user_id)
    {
        $this->db->where('id', $user_id);
        $result = $this->db->delete('users');

        return $result;
    }

    public function update_user($user_id, $username, $email)
    {
        if ($this->is_unique_username_email($user_id, $username, $email)) {
            return false;
        }

        $data = [
            'username' => $username,
            'email' => $email,
        ];

        $this->db->where('id', $user_id);
        $result = $this->db->update('users', $data);

        return $result;
    }

    private function is_unique_username_email($user_id, $username, $email)
    {
        $this->db->where('id !=', $user_id);
        $this->db->group_start();
        $this->db->where('username', $username);
        $this->db->or_where('email', $email);
        $this->db->group_end();
        $query = $this->db->get('users');

        return $query->num_rows() > 0;
    }
}
