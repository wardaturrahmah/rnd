<?php 
class panelis extends CI_Controller {

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
		$id_menu=7;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_panelis';
			$data['form']=site_url('panelis/add_panelis');
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama panelis','Action');
			$list=$this->Master_model->get_panelis()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('panelis/edit_form/'.$list->id_panelis,"Ubah",array('class' => 'btn btn-info'));
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('panelis/delete_/'.$list->id_panelis,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->nama_panelis,$action);
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
	function add_panelis()
	{
		$this->cek_login();
		$id_menu=7;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$num=$this->Master_model->cek_panelis($this->input->post('panelis'))->num_rows();
			if($num==0)
			{
			$form = array(			
						'nama_panelis'					=> ucwords(strtolower($this->input->post('panelis'))),

						);
			$this->Transaksi_model->add_form($form,'master_panelis');
			$form=array('kegiatan'		=>		'Menambah panelis '.$this->input->post('panelis')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			
			}
			else
			{
				$this->session->set_flashdata('message_panelis', 'Nama panelis sudah ada');

			}
			redirect('panelis');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();
		$id_menu=7;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_panelis';
			$data['form']=site_url('panelis/edit_panelis/'.$id);
			$row=$this->Master_model->get_panelis2($id)->row();
			$data['default']['panelis']=$row->nama_panelis;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama panelis','Action');
			$list=$this->Master_model->get_panelis()->result();
			foreach($list as $list){
				$action=anchor('line_produk/edit_panelis/'.$list->id_panelis,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->nama_panelis,$action);
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
	function edit_panelis($id)
	{
		$this->cek_login();
		$id_menu=7;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$dt=$this->Master_model->get_panelis2($id)->row();
			$form = array(			
						'nama_panelis'					=> ucwords(strtolower($this->input->post('panelis'))),

						);
			$this->Transaksi_model->edit_form('id_panelis',$id,$form,'master_panelis');
			$form=array('kegiatan'		=>		'Mengubah panelis '.$dt->nama_panelis.' menjadi '.$this->input->post('panelis')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('panelis');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=7;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->D==1)
		{
			$dt=$this->Master_model->get_panelis2($id)->row();
			$cek=$this->Master_model->panelis_hdr($dt->nama_panelis)->num_rows();
			if($cek==0)
			{
				$this->Transaksi_model->delete_form2($id,'master_panelis','id_panelis');	
				$form=array('kegiatan'		=>		'Menghapus panelis '.$dt->nama_panelis
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_panelis', 'Panelis tidak dapat dihapus karena sudah digunakan');
			}
			redirect('panelis');
		}
		else
		{
			echo "Access Deny";
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
