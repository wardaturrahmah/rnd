<?php 
class sarana extends CI_Controller {

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
		$id_menu=5;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_sarana';
			$data['form']=site_url('sarana/add_sarana');
			$data['kategori']=$this->Master_model->get_kategori_sarana()->result();
			$data['kategori_selected']='';
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama sarana','Kategori','Action');
			$list=$this->Master_model->get_sarana()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('sarana/edit_form/'.$list->id_sarana,"Ubah",array('class' => 'btn btn-info'));
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('sarana/delete_/'.$list->id_sarana,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->sarana,$list->kategori,$action);
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
	function add_sarana()
	{
		$this->cek_login();
		$id_menu=5;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$cek=$this->Master_model->cek_kode_sarana($this->input->post('sarana'))->num_rows();
			if($cek==0)
			{
			$form = array(			
						'sarana'					=> ucwords(strtolower($this->input->post('sarana'))),
						'kategori'				=> $this->input->post('kategori'),
						);
			$this->Transaksi_model->add_form($form,'sarana');
			$form=array('kegiatan'		=>		'Membuat sarana '.$this->input->post('sarana')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_sarana', 'sarana sudah ada');
			}
			redirect('sarana');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();	
		$id_menu=5;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_sarana';
			$data['form']=site_url('sarana/edit_sarana/'.$id);
			$data['kategori']=$this->Master_model->get_kategori_sarana()->result();
			$dt=$this->Master_model->get_sarana2($id)->row();
			$data['kategori_selected']=$dt->kategori;
			$data['default']['sarana']=$dt->sarana;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama sarana','Kategori','Action');
			$list=$this->Master_model->get_sarana()->result();
			foreach($list as $list){
				$action=anchor('sarana/edit_form/'.$list->id_sarana,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->sarana,$list->kategori,$action);
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
	function edit_sarana($id)
	{
		$this->cek_login();
		$id_menu=5;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$dt=$this->Master_model->get_sarana2($id)->row();
			$form = array(			
						'sarana'					=> ucwords(strtolower($this->input->post('sarana'))),
						'kategori'				=> $this->input->post('kategori'),
						);
			$this->Transaksi_model->edit_form('id_sarana',$id,$form,'sarana');
			$form=array('kegiatan'		=>		'Mengubah sarana '.$dt->sarana.' menjadi '.$this->input->post('sarana')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			redirect('sarana');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=5;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->D==1)
		{
			$dt=$this->Master_model->get_sarana2($id)->row();
			$cek=$this->Master_model->sarana_formula($id)->num_rows();
			if($cek==0)
			{
				$this->Transaksi_model->delete_form2($id,'sarana','id_sarana');	
				$form=array('kegiatan'		=>	'Menghapus sarana '.$dt->sarana
							,'pic'			=>	$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>	date("Y-m-d H:i:s")
							,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_sarana', 'Sarana tidak dapat dihapus karena sudah digunakan');
			}
			redirect('sarana');
		}
		else
		{
			echo "Access Deny";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
