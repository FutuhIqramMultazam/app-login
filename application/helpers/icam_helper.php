<?php

function is_logged_in()
{

    $ci = get_instance();

    if (!$ci->session->userdata("email")) {
        redirect("auth");
    } else {

        $role_id = $ci->session->userdata('role_id');
        $menuUrl = $ci->uri->segment(1);

        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menuUrl])->row_array();
        $menuId = $queryMenu["id"];

        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menuId
        ]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}
