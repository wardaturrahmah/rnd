<?php 
class risetman extends CI_Controller {

	 function __construct() {
        parent::__construct();
	
		$this->load->model('Transaksi_model', '', TRUE);
		$this->load->model('Master_model', '', TRUE);
	}

	public function index()
	{
		$this->add_form();
	}
	
	function cek_login()
	{
		if(($this->session->userdata('login_seas')!=TRUE) )//ganti
		{
			redirect();
		}
		
	}
	function checkaut_menu($id_menu)
	{
		$auth = $this->Master_model->checkaut_menu($id_menu)->row();
		return $auth;
	}
	function add_form()
	{
		$this->cek_login();	
		$id_menu=6;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_risetman';
			$data['form']=site_url('risetman/add_risetman');
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Risetman','Action');
			$list=$this->Master_model->get_risetman()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('risetman/edit_form/'.$list->id_risetman,"Ubah",array('class' => 'btn btn-info'));
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('risetman/delete_/'.$list->id_risetman,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->risetman,$action);
			} 
			$data['auth_menu']=$auth_menu;
			$data['table'] = $this->table->generate(); 
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_risetman()
	{
		$this->cek_login();
		$id_menu=6;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$cek=$this->Master_model->cek_risetman($this->input->post('risetman'))->num_rows();
			if($cek==0)
			{
				$form = array(			
							'risetman'					=> ucwords(strtolower($this->input->post('risetman'))),

							);
				$this->Transaksi_model->add_form($form,'risetman');
				$form=array('kegiatan'		=>		'Menambah risetman '.$this->input->post('risetman')
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_risetman', 'Risetman sudah ada');
			}
			redirect('risetman');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();
		$id_menu=6;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_risetman';
			$data['form']=site_url('risetman/edit_risetman/'.$id);
			$row=$this->Master_model->get_risetman2($id)->row();
			$data['default']['risetman']=$row->risetman;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama risetman','Action');
			$list=$this->Master_model->get_risetman()->result();
			foreach($list as $list){
				$action=anchor('line_produk/edit_risetman/'.$list->id_risetman,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->risetman,$action);
			} 
			$data['table'] = $this->table->generate(); 
			$data['auth_menu']=$auth_menu;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_risetman($id)
	{
		$this->cek_login();
		$id_menu=6;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$dt=$this->Master_model->get_risetman2($id)->row();
			$form = array(			
						'risetman'					=> ucwords(strtolower($this->input->post('risetman'))),

						);
			$this->Transaksi_model->edit_form('id_risetman',$id,$form,'risetman');
			$form=array('kegiatan'		=>		'Mengubah risetman '.$dt->risetman.' menjadi '.$this->input->post('risetman')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			redirect('risetman');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=6;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->D==1)
		{
			$dt=$this->Master_model->get_risetman2($id)->row();
			$cek=$this->Master_model->risetman_formula($id)->num_rows();
			if($cek==0)
			{
				$this->Transaksi_model->delete_form2($id,'risetman','id_risetman');	
				$form=array('kegiatan'		=>		'Menghapus risetman '.$dt->risetman
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_risetman', 'Risetman tidak dapat dihapus karena sudah digunakan');
			}
			redirect('risetman');
		}
		else
		{
			echo "Access Deny";
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
