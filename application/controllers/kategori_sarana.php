<?php 
class kategori_sarana extends CI_Controller {

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
		$id_menu=4;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_kategori_sarana';
			$data['form']=site_url('kategori_sarana/add_kategori');
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Kategori','Action');
			$list=$this->Master_model->get_kategori_sarana()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('kategori_sarana/edit_form/'.$list->id_kategori,"Ubah",array('class' => 'btn btn-info'));
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('kategori_sarana/delete_/'.$list->id_kategori,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->kategori,$action);
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
	function add_kategori()
	{
		$this->cek_login();
		$id_menu=4;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$cek=$this->Master_model->cek_kategori_sarana($this->input->post('kategori'))->num_rows();
			if($cek==0)
			{
			$form = array(			
						'kategori'					=> strtoupper($this->input->post('kategori')),

						);
			$this->Transaksi_model->add_form($form,'kategori_sarana');
			$form=array('kegiatan'			=>		'Membuat kategori sarana '.$this->input->post('kategori')
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_kategori_sarana', 'Kategori sarana sudah ada');

			}
			redirect('kategori_sarana');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();
		$id_menu=4;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_kategori_sarana';
			$data['form']=site_url('kategori_sarana/edit_kategori/'.$id);
			$row=$this->Master_model->get_kategori_sarana2($id)->row();
			$data['default']['kategori']=$row->kategori;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Kategori','Action');
			$list=$this->Master_model->get_kategori_sarana()->result();
			foreach($list as $list){
				$action=anchor('line_produk/edit_kategori/'.$list->id_kategori,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->kategori,$action);
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
	function edit_kategori($id)
	{		
		$this->cek_login();
		$id_menu=4;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$cek=$this->Master_model->cek_kategori_sarana($this->input->post('kategori'))->num_rows();
			if($cek==0)
			{
				$form = array(			
						'kategori'					=> strtoupper($this->input->post('kategori')),

						);
				$this->Transaksi_model->edit_form('id_kategori',$id,$form,'kategori_sarana');
				$form=array('kegiatan'			=>		'Mengubah kategori sarana '.$this->input->post('kategori')
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_kategori_sarana', 'Kategori sarana sudah ada');

			}
			redirect('kategori_sarana');
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=4;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->D==1)
		{
			$dt=$this->Master_model->get_kategori_sarana2($id)->row();
			$cek=$this->Master_model->sarana_by_kategori($id)->num_rows();
			if($cek==0)
			{
				$this->Transaksi_model->delete_form2($id,'kategori_sarana','id_kategori');	
				$form=array('kegiatan'			=>		'Menghapus kategori sarana '.$dt->kategori
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			else
			{
				$this->session->set_flashdata('message_kategori_sarana', 'Kategori sarana tidak dapat dihapus karena sudah digunakan');
			}
			redirect('kategori_sarana');
		}
		else
		{
			echo "Access Deny";
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
