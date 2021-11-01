<?php 
class bahan extends CI_Controller {

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
		$id_menu=3;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_bahan';
			$data['form']=site_url('bahan/add_bahan');
			$data['kategori']=$this->Master_model->get_kategori()->result();
			$data['kategori_selected']='';
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Kode','Nama Bahan','Kategori','Action');
			$list=$this->Master_model->get_bahan()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('bahan/edit_form/'.$list->id_bahan,"Ubah",array('class' => 'btn btn-info'));
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('bahan/delete_/'.$list->id_bahan,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->kode,$list->bahan,$list->kategori,$action);
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
	function add_bahan()
	{
		$this->cek_login();
		$id_menu=3;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$cek=$this->Master_model->cek_kode_bahan($this->input->post('kode'))->num_rows();
			if($cek==0)
			{
				$form = array(			
						'bahan'					=> $this->input->post('bahan'),
						'kategori'				=> $this->input->post('kategori'),
						'kode'					=> strtoupper($this->input->post('kode')),
						);
				$this->Transaksi_model->add_form($form,'bahan');
				$form=array('kegiatan'			=>		'Menambah bahan '.$this->input->post('kode')
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_bahan', 'Bahan sudah ada');
			}
			redirect('bahan');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();	
		$id_menu=3;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_bahan';
			$data['form']=site_url('bahan/edit_bahan/'.$id);
			$data['kategori']=$this->Master_model->get_kategori()->result();
			$dt=$this->Master_model->get_bahan2($id)->row();
			$data['kategori_selected']=$dt->kategori;
			$data['default']['bahan']=$dt->bahan;
			$data['default']['kode']=$dt->kode;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Kode','Nama Bahan','Kategori','Action');
			$list=$this->Master_model->get_bahan()->result();
			foreach($list as $list){
				$action=anchor('bahan/edit_form/'.$list->id_bahan,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->kode,$list->bahan,$list->kategori,$action);
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
	function edit_bahan($id)
	{
		$this->cek_login();
		$id_menu=3;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$dt=$this->Master_model->get_bahan2($id)->row();
			$form = array(			
						'bahan'					=> $this->input->post('bahan'),
						'kategori'				=> $this->input->post('kategori'),
						'kode'					=> strtoupper($this->input->post('kode')),

						);
			$this->Transaksi_model->edit_form('id_bahan',$id,$form,'bahan');
			$form=array('kegiatan'			=>		'Mengubah bahan '.$dt->kode.' menjadi '.$this->input->post('kode')
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			redirect('bahan');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=3;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->D==1)
		{
			$dt=$this->Master_model->get_bahan2($id)->row();
			$cek=$this->Master_model->cek_bahan_formula($dt->kode)->num_rows();
			if($cek==0)
			{
				$this->Transaksi_model->delete_form2($id,'bahan','id_bahan');	
				$form=array('kegiatan'			=>		'Menghapus bahan '.$dt->kode
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_bahan', 'Bahan tidak dapat dihapus karena sudah dgunakan.');
			}
			redirect('bahan');
		}
		else
		{
			echo "Access Deny";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
