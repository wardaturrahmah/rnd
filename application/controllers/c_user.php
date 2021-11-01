<?php 
class c_user extends CI_Controller {

	 function __construct() {
        parent::__construct();
	
		$this->load->model('Transaksi_model', '', TRUE);
		$this->load->model('Master_model', '', TRUE);
		$this->load->model('Login_model', '', TRUE);
	}

	public function index()
	{
		
		$this->add_user();
	}
	
	function cek_login()
	{
		if(($this->session->userdata('login_seas')!=TRUE) )
		{
			redirect();
		}
		
	}
	function checkaut_menu($id_menu)
	{
		$auth = $this->Master_model->checkaut_menu($id_menu)->row();
		return $auth;
	}
	
	function add_user()
	{
		$this->cek_login();	
		$id_menu38=38;
		$id_menu39=39;
		$auth_menu38=$this->checkaut_menu($id_menu38);
		$auth_menu39=$this->checkaut_menu($id_menu39);
		if($auth_menu38->R==1)
		{
			$data['auth_menu38']=$auth_menu38;
			$data['auth_menu39']=$auth_menu39;
			$data['main_view']='c_user';
			$data['form']=site_url('c_user/add_user2');
			$data['form2']=site_url('c_user/edit_user');
			$data['form3']=site_url('c_user/edit_group');
			$data['user']=$this->session->userdata('nama_seas');//ganti
			$id_spv=$this->session->userdata('id_seas');//ganti
			$userunder=$this->Master_model->list_under_spv($id_spv)->result();
			$data['group_menu']=$this->Master_model->group_menu()->result();
			$data['head']=$userunder;
			$data['list']=$this->Login_model->list_user()->result();//sementara
			//$data['list']=$userunder;//asli
			
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_user2()
	{
		$this->cek_login();
		$id_menu38=38;
		$auth_menu38=$this->checkaut_menu($id_menu38);
		if($auth_menu38->C==1)
		{
			$maksid=$this->Master_model->maksiduser()->row()->id;
			if($maksid==null)
			{
				$maksid=1;
			}
			else
			{
				$maksid+=1;
			}
			$form = array(			
						'id'							=> $maksid,
						'id_head'							=> $this->input->post('head'),
						'Username'							=> $this->input->post('userb'),
						'Password'						=> md5($this->input->post('password')),
						'Group_menu'							=> $this->input->post('group_menu'),
						'Group_produk'							=> $this->input->post('group_produk'),
						'realname'							=> $this->input->post('realname'),
						);
			$this->Transaksi_model->add_form($form,'login_mkt');
			$form=array('kegiatan'			=>		'Membuat user '.$this->input->post('userb')
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			if($this->input->post('group_produk')==1)
			{
				redirect('c_user/akses_lp/'.$maksid);
			}
			else
			{
				redirect('c_user/akses/'.$maksid);
			}
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form()
	{
		$this->cek_login();	
		$id_menu37=37;
		$auth_menu37=$this->checkaut_menu($id_menu37);
		if($auth_menu37->U==1)
		{
			$data['main_view']='edit_user';
			$data['form2']=site_url('c_user/edit_user');
			$data['user']=$this->session->userdata('nama_seas');//ganti
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_user()
	{
		$this->cek_login();
		$id_menu37=37;
		$auth_menu37=$this->checkaut_menu($id_menu37);
		if($auth_menu37->U==1)
		{
			$user=$this->session->userdata('nama_seas');//ganti
			$pl=md5($this->input->post('password3'));
			$pb=md5($this->input->post('password4'));
			if($this->Login_model->check_user2($user,$pl) == TRUE){
				$form=array(
					'password'		=>md5($this->input->post('password4'))
				);
				$this->Transaksi_model->edit_form('Username',$user,$form,'login_mkt');
				$form=array('kegiatan'		=>		'Mengganti password'
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else{
				$this->session->set_flashdata('message_pass', 'Password tidak dapat diganti. Password Lama anda salah.');
				redirect('c_user');
			}
			redirect('login');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function cek_pass()
	{
		$this->cek_login();
		$user=$this->input->post('user');
		$pass=md5($this->input->post('pass'));
		$data=$this->Login_model->check_user2($user,$pass);
		echo json_encode($data);
	}
	function edit_group()
	{
		$this->cek_login();
		$id_menu38=38;
		$auth_menu38=$this->checkaut_menu($id_menu38);
		if($auth_menu38->U==1)
		{
			
			$id=$this->input->post('u_id');
			$gr=$this->input->post('u_grup');
			$grm=$this->input->post('u_gm');
			$grp=$this->input->post('u_gp');
			$id_head=$this->input->post('u_head');
			$realname=$this->input->post('u_realname');
			$form=array(
					'id_head'		=> $id_head
					,'realname'		=> $realname
					,'Group_menu'	=> $grm
					,'Group_produk' => $grp
				);
				$this->Transaksi_model->edit_form('id',$id,$form,'login_mkt');
				
			$form=array('kegiatan'			=>		'Mengganti User '.$this->input->post('u_name')
					,'pic'				=>		$this->session->userdata('nama_seas')//ganti
					,'tgl'				=>		date("Y-m-d H:i:s")
					,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
					);
			$this->Transaksi_model->add_form($form,'log_act');
			if($grp==2)
			{
				$form2=array(
					'akses' => 0
				);
				$this->Transaksi_model->edit_form('id_user',$id,$form2,'akses_lp');
			}
			redirect('c_user');	
		}
		else
		{
			echo "Access Deny";
		}
	}
	function akses($id)
	{
		$akses=$this->Transaksi_model->akses_item2($id)->result();
		$data['id']=$id;
		$data['username']=$akses[0]->username;
		$data['akses']=$akses;
		$data['group_produk']='Produk';
		$data['form']=site_url('c_user/edit_akses_item/'.$id);
		$data['form3']=site_url('c_user');
		$data['main_view']='v_akses2';
		$this->load->view('sidemenu',$data);
	}
	function edit_akses_item($id)
	{
		$produk=$this->Master_model->get_produk()->result();
		$no=0;
		foreach($produk as $pr)
		{
			
			$akses=$this->input->post('c-'.$pr->id);
			if($akses=="")
			{
				$flag=0;
			}
			else
			{
				$flag=1;
			}
			$no++;
			//echo $no.'.'.$pr->id.'-'.$flag.'<br>';
			$form=array('akses'=>$flag);
			$form2=array('id_user'=>$id,'item'=>$pr->id);
			$this->Transaksi_model->edit_form2($form2,$form,'akses_item');
			
		}
		$us=$this->Login_model->get_akses3($id)->row();
		$form=array('kegiatan'			=>		'Menyeting Hak Akses Produk User '.$us->Username
					,'pic'				=>		$this->session->userdata('nama_seas')//ganti
					,'tgl'				=>		date("Y-m-d H:i:s")
					,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
					);
		$this->Transaksi_model->add_form($form,'log_act');
		
		//redirect('c_user/akses/'.$id);
	}
	function akses_lp($id)
	{
		$akses=$this->Transaksi_model->akses_lp($id)->result();
		$data['id']=$id;
		$data['username']=$akses[0]->username;
		$data['akses']=$akses;
		$data['group_produk']='Line Produk';
		$data['form']=site_url('c_user/edit_akses_lp/'.$id);
		$data['form3']=site_url('c_user');
		$data['main_view']='v_akses2';
		$this->load->view('sidemenu',$data);
	}
	function edit_akses_lp($id)
	{
		$produk=$this->Master_model->get_lp()->result();
		$no=0;
		foreach($produk as $pr)
		{
			
			$akses=$this->input->post('c-'.$pr->id_lp);
			if($akses=="")
			{
				$flag=0;
			}
			else
			{
				$flag=1;
			}
			$no++;
			//echo $no.'.'.$pr->id.'-'.$flag.'<br>';
			$form=array('akses'=>$flag);
			$form2=array('id_user'=>$id,'id_lp'=>$pr->id_lp);
			$this->Transaksi_model->edit_form2($form2,$form,'akses_lp');
			$this->Transaksi_model->update_akses_item($pr->id_lp,$id,$flag);
		}
		$us=$this->Login_model->get_akses3($id)->row();
		$form=array('kegiatan'			=>		'Menyeting Hak Akses Line Produk User '.$us->Username
					,'pic'				=>		$this->session->userdata('nama_seas')//ganti
					,'tgl'				=>		date("Y-m-d H:i:s")
					,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
					);
		$this->Transaksi_model->add_form($form,'log_act');
		//redirect('c_user/akses/'.$id);
	}
	
	function add_group()
	{
		$this->cek_login();
		$id_menu40=40;
		$auth_menu40=$this->checkaut_menu($id_menu40);
		if($auth_menu40->R==1)
		{
			$data['auth_menu40']=$auth_menu40;
			$data['main_view']='c_group';
			$data['form']=site_url('c_user/add_group2');
			//$data['form2']=site_url('c_user/edit_user');
			//$data['form3']=site_url('c_user/edit_group');
			$data['user']=$this->session->userdata('nama_seas');//ganti
			$id_spv=$this->session->userdata('id_seas');//ganti
			$data['head']=$this->Master_model->list_under_spv($id_spv)->result();
			$data['list']=$this->Login_model->list_group()->result();
			
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
		
	}
	function add_group2()
	{
		$this->cek_login();
		$id_menu40=40;
		$auth_menu40=$this->checkaut_menu($id_menu40);
		if($auth_menu40->C==1)
		{
			$maksid=$this->Master_model->maksidgroup()->row()->id;
			if($maksid==null)
			{
				$maksid=1;
			}
			else
			{
				$maksid+=1;
			}
			$form = array(			
						'id'							=> $maksid,
						'group_menu'							=> $this->input->post('group'),
						);
			$this->Transaksi_model->add_form($form,'Group_menu');
			$form=array('kegiatan'		=>		'Menambahkan Group '.$this->input->post('group')
					,'pic'				=>		$this->session->userdata('nama_seas')//ganti
					,'tgl'				=>		date("Y-m-d H:i:s")
					,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
					);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('c_user/akses_menu/'.$maksid);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function akses_menu($id)
	{
		$this->cek_login();
		$id_menu40=40;
		$auth_menu40=$this->checkaut_menu($id_menu40);
		if($auth_menu40->R==1)
		{
			$akses=$this->Transaksi_model->akses_menu($id)->result();
			$data['id']=$id;
			$data['akses']=$akses;
			$data['form']=site_url('c_user/edit_akses_menu/'.$id);
			$data['form3']=site_url('c_user/add_group');
			$data['main_view']='v_akses_menu';
			if($auth_menu40->U==0)
			{
				$dis='disabled';
			}
			else
			{
				$dis='';
			}
			$data['dis']=$dis;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_akses_menu($id)
	{
		$this->cek_login();
		$id_menu40=40;
		$auth_menu40=$this->checkaut_menu($id_menu40);
		if($auth_menu40->U==1)
		{
			$menu=$this->Master_model->get_menu()->result();
			$no=0;
			foreach($menu as $mn)
			{
				
				$c=$this->input->post('c-'.$mn->id);
				if($c=="")
				{
					$flagc=0;
				}
				else
				{
					$flagc=1;
				}
				$r=$this->input->post('r-'.$mn->id);
				if($r=="")
				{
					$flagr=0;
				}
				else
				{
					$flagr=1;
				}
				$u=$this->input->post('u-'.$mn->id);
				if($u=="")
				{
					$flagu=0;
				}
				else
				{
					$flagu=1;
				}
				$d=$this->input->post('d-'.$mn->id);
				if($d=="")
				{
					$flagd=0;
				}
				else
				{
					$flagd=1;
				}
				$a=$this->input->post('a-'.$mn->id);
				if($a=="")
				{
					$flaga=0;
				}
				else
				{
					$flaga=1;
				}
				$ua=$this->input->post('ua-'.$mn->id);
				if($ua=="")
				{
					$flagua=0;
				}
				else
				{
					$flagua=1;
				}
				$no++;
				
				echo $no.'.'.$mn->menu.'-'.$flagc.'-'.$flagr.'-'.$flagu.'-'.$flagd.'-'.$flaga.'-'.$flagua.'<br>';
				$form=array('C'=>$flagc,'R'=>$flagr,'U'=>$flagu,'D'=>$flagd,'A'=>$flaga,'UA'=>$flagua);
				$form2=array('id_group'=>$id,'id_menu'=>$mn->id);
				$this->Transaksi_model->edit_form2($form2,$form,'akses_menu_group');
			}
			$form=array('kegiatan'			=>		'Menyeting Hak Akses Menu Group '.$gr->group_menu
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
		}
		else
		{
			echo "Access Deny";
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
