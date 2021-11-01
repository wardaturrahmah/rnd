<?php 
class lineproduk extends CI_Controller {

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
		$id_menu=1;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->R==1)
		{
			$data['main_view']='v_lineproduk';
			$data['form']=site_url('lineproduk/add_lp');
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Line Produk','Action');
			$list=$this->Master_model->get_lp()->result();
			foreach($list as $list){
				$action='';
				if($auth_menu->U==1)
				{
					$action.=anchor('lineproduk/edit_form/'.$list->id_lp,"Ubah",array('class' => 'btn btn-info'));
				
				}
				if($auth_menu->D==1)
				{
					$action.=anchor('lineproduk/delete_/'.$list->id_lp,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->lineproduk,$action);
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
	function add_lp()
	{
		$this->cek_login();
		$id_menu=1;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->C==1)
		{
			$num=$this->Master_model->get_lp_name($this->input->post('lineproduk'))->num_rows();
			if($num>0)
			{
				$this->session->set_flashdata('message_lp', 'Line produk sudah ada.');
			}
			else
			{
				$form = array(			
						'lineproduk'					=> ucwords(strtolower($this->input->post('lineproduk'))),

						);
				$this->Transaksi_model->add_form($form,'lineproduk');
					
				$form=array('kegiatan'			=>		'Membuat line produk '.$this->input->post('lineproduk')
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				
			}
			redirect('lineproduk');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();
		$id_menu=1;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$data['main_view']='v_lineproduk';
			$data['form']=site_url('lineproduk/edit_lp/'.$id);
			$row=$this->Master_model->get_lp2($id)->row();
			$data['default']['lineproduk']=$row->lineproduk;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Line Produk','Action');
			$list=$this->Master_model->get_lp()->result();
			foreach($list as $list){
				$action=anchor('line_produk/edit_lp/'.$list->id_lp,"Ubah",array('class' => 'btn btn-info'));
				$this->table->add_row($list->lineproduk,$action);
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
	function edit_lp($id)
	{
		$this->cek_login();
		$id_menu=1;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->U==1)
		{
			$num=$this->Master_model->get_lp_name($this->input->post('lineproduk'))->num_rows();
			if($num>0)
			{
				$this->session->set_flashdata('message_lp', 'Line produk tidak dapat diedit. karena ada line produk yang sama');
			}
			else
			{
				$dt=$this->Master_model->get_lp2($id)->row();
				$form = array(			
							'lineproduk'					=> ucwords(strtolower($this->input->post('lineproduk'))),
							);
							
				$this->Transaksi_model->edit_form('id_lp',$id,$form,'lineproduk');
				$form=array('kegiatan'			=>		'Merubah line produk '.$dt->lineproduk.' menjadi '.$this->input->post('lineproduk')
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			redirect('lineproduk');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_($id)
	{
		$this->cek_login();
		$id_menu=1;
		$auth_menu=$this->checkaut_menu($id_menu);
		if($auth_menu->D==1)
		{
			$num=$this->Transaksi_model->produk_by_line($id)->num_rows();
			if($num==0)
			{
				$dt=$this->Master_model->get_lp2($id)->row();
				$this->Transaksi_model->delete_form2($id,'lineproduk','id_lp');	
				$form=array('kegiatan'			=>		'Menghapus line produk '.$dt->lineproduk
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				
			}
			else
			{
				$this->session->set_flashdata('message_lp', 'Line produk tidak dapat dihapus karena sudah dgunakan.');
			}
			redirect('lineproduk');
		}
		else
		{
			echo "Access Deny";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
