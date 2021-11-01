<?php
class login extends CI_Controller{
	function __construct() 
	{
        parent::__construct();
		$this->load->model('Login_model', '', TRUE);
		$this->load->model('Transaksi_model', '', TRUE);
	}
	function index()
	{
		$this->login();
	}
	function login()
	{
		$data['form_action']	= site_url('login/login_process');
		$this->load->view('login', $data);		
	}
	function login_process()
	{
		$username = $this->input->post('username');
		$password = md5($this->input->post('Password'));
		if ($this->Login_model->check_user2($username, $password) == TRUE)
		{
			$login= $this->Login_model->get_akses2($username)->row();
			$data = array('nama_seas' => $username, //ganti
							'login_seas' => TRUE, //ganti
							'group_seas' => $login->Group_ ,//ganti
							'id_seas'=>$login->id,//ganti
							'realname_seas'=>$login->realname,//ganti
							'group_produk_seas'=>$login->Group_produk,//ganti
							'group_menu_seas'=>$login->Group_menu );//ganti
			$this->session->set_userdata($data);
			$form=array('kegiatan'			=>		'Login'
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('list_produk');
			
		}
		else
		{
			redirect('login');
		}
	}
	
	function process_logout()
	{
		$form=array('kegiatan'			=>		'Logout'
					,'pic'				=>		$this->session->userdata('nama_seas')//ganti
					,'tgl'				=>		date("Y-m-d H:i:s")
					,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
					);
		$this->Transaksi_model->add_form($form,'log_act');
		//$this->session->sess_destroy();
		$this->session->unset_userdata('nama_seas');//ganti
		$this->session->unset_userdata('login_seas');//ganti
		$this->session->unset_userdata('group_seas');//ganti
		$this->session->unset_userdata('id_seas');//ganti
		$this->session->unset_userdata('realname_seas');//ganti
		$this->session->unset_userdata('group_produk_seas');//ganti
		$this->session->unset_userdata('group_menu_seas');//ganti

		
		redirect('login');
	}
}