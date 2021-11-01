<?php 
class masalah extends CI_Controller {

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
		$id_menu=8;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_masalah';
			$data['form']=site_url('masalah/add_masalah');
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Sumber masalah','Action');
			$list=$this->Master_model->get_masalah()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('masalah/edit_form/'.$list->id_masalah,"Ubah",array('class' => 'btn btn-info'));
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('masalah/delete_/'.$list->id_masalah,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->masalah,$action);
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
	function add_masalah()
	{
		$this->cek_login();
		$id_menu=8;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$form = array(			
						'masalah'					=> $this->input->post('masalah'),

						);
			$this->Transaksi_model->add_form($form,'master_masalah');
			$form=array('kegiatan'			=>		'Menambah sumber masalah '.$this->input->post('masalah')
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			redirect('masalah');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();
		$id_menu=8;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_masalah';
			$data['form']=site_url('masalah/edit_masalah/'.$id);
			$row=$this->Master_model->get_masalah2($id)->row();
			$data['default']['masalah']=$row->masalah;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Sumber Masalah','Action');
			$list=$this->Master_model->get_masalah()->result();
			foreach($list as $list){
				$action=anchor('line_produk/edit_masalah/'.$list->id_masalah,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->masalah,$action);
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
	function edit_masalah($id)
	{
		$this->cek_login();
		$id_menu=8;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$dt=$this->Master_model-> get_masalah2($id)->row();
			$form = array(			
						'masalah'					=> $this->input->post('masalah'),

						);
			$this->Transaksi_model->edit_form('id_masalah',$id,$form,'master_masalah');
			$form=array('kegiatan'			=>		'Mengedit sumber masalah '.$dt->masalah.' menjadi '.$this->input->post('masalah')
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			redirect('masalah');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=8;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$dt=$this->Master_model-> get_masalah2($id)->row();
			$cek=$this->Master_model-> penilaian_masalah($id)->num_rows();
			if($cek==0)
			{
				$this->Transaksi_model->delete_form2($id,'master_masalah','id_masalah');	
				$form=array('kegiatan'			=>		'Menghapus master sumber masalah '.$dt->masalah
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_masalah', 'Sumber masalah tidak dapat dihapus karena sudah digunakan');
			}
			redirect('masalah');
		}
		else
		{
			echo "Access Deny";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
