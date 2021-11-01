<?php 
class mkt extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('Transaksi_model', '', TRUE);
		$this->load->model('Master_model', '', TRUE);
		$this->load->model('Login_model', '', TRUE);
	}
	public function index()
	{
		$this->awal();
	}
	function cek_login()
	{
		if(($this->session->userdata('login_seas')!=TRUE) )
		{
			redirect();
		}	
	}
	function checkaut_item($id_item)
	{
		
		$auth = $this->Master_model->checkaut($id_item)->row();
		return $auth;
		
	}
	function checkaut_menu($id_menu)
	{
		$auth = $this->Master_model->checkaut_menu($id_menu)->row();
		return $auth;
	}
	function awal()
	{
		$this->cek_login();
		$id_menu_produk=9;
		$id_menu_kompetitor=10;
		$auth_menu_produk=$this->checkaut_menu($id_menu_produk);
		$auth_menu_kompetitor=$this->checkaut_menu($id_menu_kompetitor);
		if($auth_menu_produk->R==1)
		{
			$data['main_view']='v_awal';
			$num=$this->Transaksi_model->id_item()->num_rows();
			if($num>0)
			{
				$a=$this->Transaksi_model->id_item()->row();
				$id=$a->id+1;
			}
			else
			{
				$id=1;
			}
			$data['form']=site_url('tambah_produk/'.$id);
			$data['auth_menu_produk']=$auth_menu_produk;
			$data['auth_menu_kompetitor']=$auth_menu_kompetitor;
			$data['list']=$this->Transaksi_model->list_item_akses($this->session->userdata('id_seas'))->result();//ganti
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function list_produk_line($line)
	{
		$this->cek_login();
		$data['main_view']='v_list';
		
		$data['list']=$this->Transaksi_model->list_item_line($line)->result();
		$this->load->view('sidemenu',$data);
	}
	function list_produk_risetman($risetman)
	{
		$this->cek_login();
		$data['main_view']='v_list';
		
		$data['list']=$this->Transaksi_model->list_item_risetman($risetman)->result();
		$this->load->view('sidemenu',$data);
	}
	function add_form($id)
	{
		$this->cek_login();
		$id_menu_produk=9;
		$auth_menu_produk=$this->checkaut_menu($id_menu_produk);
		if($auth_menu_produk->C==1)
		{
			$data['main_view']='v_mkt';
			$data['default']['id_item']=$id;
			$data['form']=site_url('mkt/add_hdr');	
			$data['lineproduk']=$this->Master_model->get_lp()->result();
			$data['lineproduk_selected']='';
			$data['risetman']=$this->Master_model->get_risetman()->result();
			$data['risetman_selected']='';
			$data['konsep']=$this->Master_model->get_produk()->result();
			$data['konsep_selected']='';
			$data['link']=$this->Master_model->get_produk()->result();
			$data['link_Selected']='';
		/* 	$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']=''; */
			$data['status_selected']='';
			
			$form=array(
						'id'				=> $id,
						);
			$this->Transaksi_model->add_form($form,'produk');
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_hdr()
	{
		$this->cek_login();
		$id_menu_produk=9;
		$auth_menu_produk=$this->checkaut_menu($id_menu_produk);
		if($auth_menu_produk->C==1)
		{
			$id=$this->input->post('id_item');
			$form=array(
						
						'nama_item'			=> ucwords(strtolower($this->input->post('item'))),
						'line'				=> $this->input->post('p_line'),
					//	'risetman'			=> $this->input->post('risetman'),
						'kompetitor'		=> ucfirst(strtolower($this->input->post('kompetitor'))),
						'awal_riset'		=> date('Y-m-d',strtotime($this->input->post('tgl'))),
						'konsep_sebelumnya'		=> $this->input->post('konsep'),
						'status'		=> $this->input->post('status'),
						'tgl_status'		=> date('Y-m-d',strtotime($this->input->post('tgl_status'))),
						'pic' 				=> $this->session->userdata('nama_seas'),//ganti
						'jam_input'			=> date('Y-m-d H:i'),
						'ket_status'		=> ucfirst(strtolower($this->input->post('keterangan'))),
						);
			$this->Transaksi_model->edit_form('id',$id,$form,'produk');
			$this->Transaksi_model->in_akses($id);
			
			$form=array('akses'=>1);
			$form2=array('id_user'=>$this->session->userdata('id_seas'),'item'=>$id);//ganti
			$this->Transaksi_model->edit_form2($form2,$form,'akses_item');
			$this->Transaksi_model->update_akses_item2($id);
			
				
			//sini
			$form=array('kegiatan'			=>		'Membuat Item '.$this->input->post('item')
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$pn=$this->Transaksi_model->penilaian_all($id)->result();
			foreach($pn as $pn)
			{
				$form=array('kegiatan'			=>		'Menambah subvar'.$pn->subvar.' untuk penilaian panelis untuk produk '.$pn->nama_item
							,'pic'				=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'				=>		date("Y-m-d H:i:s")
							,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');	
			}
			
			if($this->input->post('link'))
			{
				foreach($this->input->post('link') as $link)
				{
					$form=array(
						'id_item'				=> $id,
						'link_item'			=> $link
						);
					$this->Transaksi_model->add_form($form,'ref_link');		
				}
			}
			else
			{
				$data['link_selected']='';
			}
			if($this->input->post('risetman'))
			{
				foreach($this->input->post('risetman') as $risetman)
				{
					$form=array(
						'id_item'				=> $id,
						'risetman'			=> $risetman
						);
					$this->Transaksi_model->add_form($form,'risetman_hdr');		
				}
			}
			else
			{
				$data['risetman_selected']='';
			}
			redirect('list_produk');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_form($id)
	{
		$this->cek_login();
		$id_menu_produk=9;
		$auth_menu_produk=$this->checkaut_menu($id_menu_produk);
		if($auth_menu_produk->U==1)
		{
			$auth=$this->checkaut_item($id);
			if(count($auth)>0)
			{
			$data['main_view']='v_mkt';
			$data['default']['id_item']=$id;
			$a=$this->Transaksi_model->resume_item($id)->row();
			$data['default']['item']=$a->nama_item;
			$data['lineproduk']=$this->Master_model->get_lp()->result();
			$data['lineproduk_selected']=$a->line;
			$data['risetman']=$this->Master_model->get_risetman()->result();
			//$data['risetman_selected']=$a->id_risetman;
			$data['status_selected']=$a->status;
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
			$data['default']['kompetitor']=$a->kompetitor;
			$data['default']['keterangan']=$a->ket_status;
			//$data['konsep']=$this->Master_model->get_produk_terminated()->result();
			$data['konsep']=$this->Master_model->get_produk()->result();
			$data['konsep_selected']=$a->konsep_sebelumnya;
			$data['link']=$this->Master_model->get_produk()->result();
			$data['link_Selected']='';
			$data['default']['tgl']=date('d-m-Y',strtotime($a->awal_riset));
			$data['default']['tgl_status']=date('d-m-Y',strtotime($a->tgl_status));
			$num_ref=$this->Master_model->get_ref_link($id)->num_rows();
			if($num_ref>0)
			{
				
				$links='';
				$linka=array();
				$ref=$this->Master_model->get_ref_link($id)->result();
				foreach($ref as $link)
				{
					$links.=$link->id.',';
					array_push($linka,$link->id);
				}
						$data['links']=rtrim($links,',');
						$data['linka']=$linka;

			}
			$num_rst=$this->Transaksi_model->risetman_hdr($id)->num_rows();
			if($num_rst>0)
			{
				
				$risetmans='';
				$risetmana=array();
				$risetman=$this->Transaksi_model->risetman_hdr($id)->result();
				foreach($risetman as $risetman)
				{
					$risetmans.=$risetman->risetman.',';
					array_push($risetmana,$risetman->risetman);
				}
						$data['risetmans']=rtrim($risetmans,',');
						$data['risetmana']=$risetmana;

			}
			
			$data['form']=site_url('mkt/edit_hdr');
			$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "Access deny";
		}
	}
	function edit_hdr()
	{
		$this->cek_login();
		$id_menu_produk=9;
		$auth_menu_produk=$this->checkaut_menu($id_menu_produk);
		if($auth_menu_produk->U==1)
		{
			$id=$this->input->post('id_item');
			$hdr=$this->Transaksi_model->resume_item($id)->row();
			$risetmanas=$this->Transaksi_model->risetman_hdr($id)->result();
			$ref=$this->Master_model->get_ref_link($id)->result();
			
			$form=array(
						
						'nama_item'			=> ucwords(strtolower($this->input->post('item'))),
						'line'				=> $this->input->post('p_line'),
						//'risetman'			=> $this->input->post('risetman'),
						'kompetitor'		=> ucfirst(strtolower($this->input->post('kompetitor'))),
						'awal_riset'		=> date('Y-m-d',strtotime($this->input->post('tgl'))),
						'tgl_status'		=> date('Y-m-d',strtotime($this->input->post('tgl_status'))),
						'konsep_sebelumnya'		=> $this->input->post('konsep'),
						'status'		=> $this->input->post('status'),
						'ket_status'		=> ucfirst(strtolower($this->input->post('keterangan'))),
						);
			$this->Transaksi_model->edit_form('id',$id,$form,'produk');
			
			$this->Transaksi_model->delete_form2($id,'ref_link','id_item');
			if($this->input->post('link'))
			{
				foreach($this->input->post('link') as $link)
				{
					$form=array(
						'id_item'				=> $id,
						'link_item'			=> $link
						);
					$this->Transaksi_model->add_form($form,'ref_link');		
				}
			}
			$this->Transaksi_model->delete_form2($id,'risetman_hdr','id_item');

			if($this->input->post('risetman'))
			{
				foreach($this->input->post('risetman') as $risetman)
				{
					$form=array(
						'id_item'				=> $id,
						'risetman'			=> $risetman
						);
					$this->Transaksi_model->add_form($form,'risetman_hdr');		
				}
			}
			
			
			if($hdr->nama_item!=ucwords(strtolower($this->input->post('item'))))
			{
				$form=array('kegiatan'		=>		'Mengubah nama Item '.$hdr->nama_item.' menjadi '.$this->input->post('item')
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			if($hdr->line!=$this->input->post('p_line'))
			{
				$form=array('kegiatan'		=>		'Mengubah produk line item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname'		=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
				$this->Transaksi_model->update_lp_akses($hdr->line,$id,0);
				$this->Transaksi_model->update_lp_akses($this->input->post('p_line'),$id,1);
			}
			//kurang risetman
			if($hdr->kompetitor!=ucfirst(strtolower($this->input->post('kompetitor'))))
			{
				$form=array('kegiatan'		=>		'Mengubah target riset item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			if($hdr->konsep_sebelumnya!=$this->input->post('konsep'))
			{
				$form=array('kegiatan'		=>		'Mengubah konsep sebelumnya item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			if($hdr->awal_riset!=date('Y-m-d',strtotime($this->input->post('tgl'))))
			{
				$form=array('kegiatan'		=>		'Mengubah tanggal awal riset item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			if($hdr->status!=$this->input->post('status'))
			{
				if($this->input->post('status')==1)
				{
					$sb="Launching";
				}
				else if($this->input->post('status')==2)
				{
					$sb="Bank Produk-ACC";
				}
				else if($this->input->post('status')==-1)
				{
					$sb="Terminate";
				}
				else if($this->input->post('status')==0)
				{
					$sb="Progress";
				}
				$form=array('kegiatan'		=>		'Mengubah status item '.$hdr->nama_item.' menjadi '.$sb
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			if($hdr->tgl_status!=date('Y-m-d',strtotime($this->input->post('tgl_status'))))
			{
				$form=array('kegiatan'		=>		'Mengubah tanggal status item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			if($hdr->ket_status!=ucfirst(strtolower($this->input->post('keterangan'))))
			{
					$form=array('kegiatan'		=>		'Mengubah keterangan status item '.$hdr->nama_item
								,'pic'			=>		$this->session->userdata('nama_seas')//ganti
								,'tgl'			=>		date("Y-m-d H:i:s")
								,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			
			$risetmana=array();
			foreach($risetmanas as $rst)
			{
				array_push($risetmana,$rst->risetman);
			}
			$result=array_diff($risetmana,$this->input->post('risetman'));
			$result2=array_diff($this->input->post('risetman'),$risetmana);
			$br= count($result)+count($result2);
			if($br>0)
			{
				$form=array('kegiatan'		=>		'Mengubah risetman Item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			
			$linka=array();
			foreach($ref as $rf)
			{
				array_push($linka,$rf->link_item);
			}
			$result3=array_diff($linka,$this->input->post('link'));
			$result4=array_diff($this->input->post('link'),$linka);
			$br2= count($result3)+count($result4);
			if($br2>0)
			{
				$form=array('kegiatan'		=>		'Mengubah referensi link Item '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			//link diganti
			
			redirect('list_produk');
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_penilaian()
	{
		$this->cek_login();
		$form=array('id_item'		=> $this->input->post('id_item')
					,'subvar'		=> ucfirst(strtolower($this->input->post('subvar')))
					,'varr'		=> $this->input->post('vari')
					,'skala'		=> str_replace(",",".",$this->input->post('skala'))
					);
		$this->Transaksi_model->add_form($form,'penilaian');
		
		$data=$this->Transaksi_model->penilaian($this->input->post('id_item'),$this->input->post('subvar'))->result();
		foreach($data as $dt)
		{
			if(!empty($dt->nama_item))
			{
				$form=array('kegiatan'		=>		'Menambah subvar '.$dt->subvar.' untuk penilaian panelis produk '.$dt->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');	
			}
		}
		echo json_encode($data);

	}
	function update_penilaian()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$subvar=$this->input->post('subvar');
		$varr=$this->input->post('varr');
		$skala=$this->input->post('skala');
		$form=array('subvar'	=> $subvar,
					'varr'			=> $varr,
					'skala'			=> str_replace(",",".",$this->input->post('skala')));
		$this->Transaksi_model->edit_form('id',$id,$form,'penilaian');
		
		$data=$this->Transaksi_model->penilaian2($id)->result();
		foreach($data as $dt)
		{
			if(!empty($dt->nama_item))
			{
				$form=array('kegiatan'		=>		'Mengubah subvar '.$dt->subvar.' untuk penilaian panelis produk '.$dt->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');	
			}
		}
		echo json_encode($data);

	}
	function add_panelis()
	{
		$this->cek_login();
		$cek=$this->Transaksi_model->cek_panelis($this->input->post('panelis'),$this->input->post('id_item'))->num_rows();
		if($cek==0)
		{
		$form=array('id_item'		=> $this->input->post('id_item')
					,'panelis'		=> $this->input->post('panelis')
					);
		$this->Transaksi_model->add_form($form,'panelis');
		$data=$this->Transaksi_model->panelis($this->input->post('id_item'),$this->input->post('panelis'))->result();
		echo json_encode($data);
		}
	}
	function get_tabel_penilaian()
	{
		$this->cek_login();
		$data=$this->Transaksi_model->penilaian_all($this->input->post('id_item'))->result();
		echo json_encode($data);
	}
	function get_penilaian($id)
	{
		$this->cek_login();
		$data=$this->Transaksi_model->penilaian2($this->input->post('id'))->result();
		echo json_encode($data);
	}
	function get_tabel_panelis()
	{
		$this->cek_login();
		$data=$this->Transaksi_model->panelis_all($this->input->post('id_item'))->result();
		echo json_encode($data);
	}
	function delete_penilaian()
	{
		$this->cek_login();
		
		$data['id']=$this->input->post('id');
		$cek=$this->Transaksi_model->cek_penilaian($this->input->post('id'))->result();
		$cek2=$this->Transaksi_model->cek_penilaian2($this->input->post('id'))->result();
		$dt=$this->Transaksi_model->penilaian2($this->input->post('id'))->row();
		if(count($cek)==0 and count($cek2)==0)
		{
			
			if(!empty($dt->nama_item))
			{
				$form=array('kegiatan'		=>		'Menghapus subvar '.$dt->subvar.' untuk penilaian panelis produk '.$dt->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');	
			}
			$this->Transaksi_model->delete_form($this->input->post('id'),'penilaian');
			
		}
		$dt=$this->Transaksi_model->penilaian2($this->input->post('id'))->row();
		echo json_encode($dt);
	}
	function delete_panelis()
	{
		$this->cek_login();
		$data['id']=$this->input->post('id');
		$this->Transaksi_model->delete_form($this->input->post('id'),'panelis');
	}
	
	function produk_kompetitor($id)
	{
		$this->cek_login();
		$id_menu_kompetitor=10;
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor=$this->checkaut_menu($id_menu_kompetitor);
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor->R==1)
		{
			$data['main_view']='v_kompetitor';
			$data['form']=site_url('mkt/add_kompetitor');
			$data['default']['id_produk']=$id;
			$a=$this->Transaksi_model->resume_item($id)->row();
			$data['judul']=$a->nama_item;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Kompetitor','Status','Foto','Action');
			$list=$this->Transaksi_model->list_kompetitor($id)->result();
			foreach($list as $list)
			{
				if($list->status_kompetitor==1)
				{
					$status="Approve";
				}
				else if($list->status_kompetitor==-1)
				{
					$status="Drop";
				}
				else
				{
					$status="";
				}
				if($list->foto!='')
				{
					$foto="<img src='../uploads/kompetitor/$list->foto' width='70' height='90' />";
				}
				else
				{
					$foto="";	
				}
				$action='';
				if($auth_menu_kompetitor->R==1)
				{
					
					$action.=anchor('mkt/kompetitor_dtl/'.$list->id_kompetitor.'-'.$id,"Lihat",array('class' => 'btn btn-dark'));
					
				}
				if($auth_menu_kompetitor_panelis->R==1)
				{	
					$action.=anchor('panelis_kompetitor/'.$list->id_kompetitor,"Panelis",array('class' => 'btn btn-info'));
				}
				if($auth_menu_kompetitor->U==1)
				{
					$action.=anchor('mkt/edit_kompetitor_form/'.$list->id_kompetitor.'-'.$id,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu_kompetitor->D==1)
				{
					$action.=anchor('mkt/delete_kompetitor/'.$list->id_kompetitor.'-'.$id,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list->nama,$status,$foto,$action);
			} 
			$data['auth_menu_kompetitor']=$auth_menu_kompetitor;
			$data['table'] = $this->table->generate();
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function add_kompetitor()
	{
		$this->cek_login();
		$id_menu_kompetitor=10;
		$auth_menu_kompetitor=$this->checkaut_menu($id_menu_kompetitor);
		if($auth_menu_kompetitor->C==1)
		{
			$configUpload['upload_path'] = 'uploads/kompetitor/';
			$configUpload['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
			$configUpload['max_size'] = '5000';
			$this->load->library('upload', $configUpload);
			$this->upload->do_upload('foto');	
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$file_name = $upload_data['file_name']; //uploded file name
		
			$form=array('id_produk'	=> $this->input->post('id_produk')
					,'nama'	=>$this->input->post('produk')
					,'foto' =>str_replace(" ","_",$this->input->post('nama_foto'))
					
					);
			$this->Transaksi_model->add_form($form,'kompetitor');
			$a=$this->Transaksi_model->resume_item($this->input->post('id_produk'))->row();
			$form=array('kegiatan'		=>		'Menambah '.$this->input->post('produk').' sebagai kompetitor '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('produk_kompetitor/'.$this->input->post('id_produk'));
		}
		else
		{
			echo "Access Deny";
		}

	}
	
	function delete_kompetitor($id)
	{
		$this->cek_login();
		$id_menu_kompetitor=10;
		$auth_menu_kompetitor=$this->checkaut_menu($id_menu_kompetitor);
		if($auth_menu_kompetitor->D==1)
		{
			$arr=explode('-',$id);
			$id=$arr[0];
			$id_item=$arr[1];
			$a=$this->Transaksi_model->kompetitor($id)->row();
			$form=array('kegiatan'		=>		'Menghapus produk '.$a->nama.' dari kompetitor '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$target='uploads/kompetitor/'.$a->foto;
			if(file_exists($target))
			{
				unlink($target);
			}
			$this->Transaksi_model->delete_kompetitor($id);
			redirect('produk_kompetitor/'.$arr[1]);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_kompetitor_form($id)
	{
		$this->cek_login();
		$id_menu_kompetitor=10;
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor=$this->checkaut_menu($id_menu_kompetitor);
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor->U==1)
		{
			$arr=explode('-',$id);
			$id=$arr[0];
			$id_item=$arr[1];
			$data['main_view']='v_kompetitor';
			$data['form']=site_url('mkt/edit_kompetitor');
			$data['default']['id_produk']=$id_item;
			$data['default']['id']=$id;
			$a=$this->Transaksi_model->resume_item($id_item)->row();
			$data['judul']=$a->nama_item;
			$tmpl = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Kompetitor','Status','Foto','Action');
			$list=$this->Transaksi_model->list_kompetitor($id_item)->result();
			foreach($list as $list)
			{
				if($list->status_kompetitor==1)
				{
					$status="Approve";
				}
				else if($list->status_kompetitor==-1)
				{
					$status="Drop";
				}
				else
				{
					$status="";
				}
				if($list->foto!='')
				{
					$foto="<img src='../../uploads/kompetitor/$list->foto' width='70' height='90' />";
				}
				else
				{
					$foto="";	
				}
				$action='';
				if($auth_menu_kompetitor->R==1)
				{
					
					$action.=anchor('mkt/kompetitor_dtl/'.$list->id_kompetitor.'-'.$id,"Lihat",array('class' => 'btn btn-dark'));
					
				}
				if($auth_menu_kompetitor_panelis->R==1)
				{	
					$action.=anchor('panelis_kompetitor/'.$list->id_kompetitor,"Panelis",array('class' => 'btn btn-info'));
				}
				if($auth_menu_kompetitor->U==1)
				{
					$action.=anchor('mkt/edit_kompetitor_form/'.$list->id_kompetitor.'-'.$id,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu_kompetitor->D==1)
				{
					$action.=anchor('mkt/delete_kompetitor/'.$list->id_kompetitor.'-'.$id,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
						
				$this->table->add_row($list->nama,$status,$foto,$action);
			} 
			$dt=$this->Transaksi_model->kompetitor($id)->row();
			$data['default']['foto']=$dt->foto;
			$data['default']['produk']=$dt->nama;
			$data['auth_menu_kompetitor']=$auth_menu_kompetitor;
			$data['table'] = $this->table->generate();
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_kompetitor()
	{
		$id_menu_kompetitor=10;
		$auth_menu_kompetitor=$this->checkaut_menu($id_menu_kompetitor);
		if($auth_menu_kompetitor->U==1)
		{
			$id=$this->input->post('id');
			$lama=$this->Transaksi_model->kompetitor($id)->row();
			if($lama->foto!=$this->input->post('nama_foto'))
			{
				$target='uploads/kompetitor/'.$lama->foto;
				if(file_exists($target))
				{
					unlink($target);
				}				
				$configUpload['upload_path'] = 'uploads/kompetitor/';
				$configUpload['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$configUpload['max_size'] = '5000';
				$this->load->library('upload', $configUpload);
				$this->upload->do_upload('foto');	
				$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
				$file_name = $upload_data['file_name']; //uploded file name
			}
			
			
			$form=array('id_produk'	=> $this->input->post('id_produk')
					,'nama'	=>$this->input->post('produk')
					,'foto' =>str_replace(" ","_",$this->input->post('nama_foto'))
				);
			$this->Transaksi_model->edit_form('id_kompetitor',$id,$form,'kompetitor');
			$a=$this->Transaksi_model->kompetitor($id)->row();
			$form=array('kegiatan'		=>		'Mengubah '.$this->input->post('produk').' sebagai kompetitor '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('produk_kompetitor/'.$this->input->post('id_produk'));
		}
		else
		{
			echo "Access Deny";
		}

	}
	
	function panelis_kompetitor($id)
	{
		$this->cek_login();
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->R==1)
		{		
			$data['main_view']='v_panelis_kompetitor';
			$data['form']=site_url('mkt/add_panelis_kompetitor');
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
			$data['ke_selected']='';
			$hdr=$this->Transaksi_model->kompetitor($id)->row();
			$data['default']['id_kompetitor']=$id;
			$data['status_kompetitor']=$hdr->status_kompetitor;
			$data['nama']=$hdr->nama;
			$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);

			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar');
			$list=$this->Transaksi_model->list_nilai2($id)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_formula=form_hidden('id_formula'.$k,$list->id_formula);
				$skala=form_input('skala'.$k,isset($default['skala'])?$default['skala']:"",$js2);
				$nilai=form_input('nilai'.$k,isset($default['nilai'])?$default['nilai']:"",$js2);
				$keterangan=form_textarea('keterangan'.$k,isset($default['keterangan'])?$default['keterangan']:"",$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_formula);
			} 
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal Panelis','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis_kompetitor($id,3)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				if($auth_menu_kompetitor_panelis->R==1)
				{
					$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>'get_tabel('.$list2->id_penilai.')'));
				}
				if($auth_menu_kompetitor_panelis->U==1)
				{
					$action.=anchor('edit_panelis_kompetitor/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu_kompetitor_panelis->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_kompetitor/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			$data['auth_menu_kompetitor_panelis']=$auth_menu_kompetitor_panelis;
			$data['form']=site_url('mkt/add_panelis_kompetitor');		
			$data['form4']=site_url('produk_kompetitor/'.$hdr->id);
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_panelis_kompetitor()
	{
		
		$this->cek_login();	
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->C==1)
		{	
			$form=array(	'panelis'			=>		$this->input->post('panelis')
							,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
							,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
							,'ke'				=>		$this->input->post('ke')
							,'id_formula'		=>		$this->input->post('id_formula1')
							,'kesimpulan'		=>		$this->input->post('kesimpulan')
							);
			$this->Transaksi_model->add_form($form,'penilaian_kompetitor_hdr');
			$a=$this->Transaksi_model->kompetitor($this->input->post('id_formula1'))->row();
			$form=array('kegiatan'		=>		'Menambah panelis kompetitor '.$a->nama.' Oleh '.$this->input->post('panelis').' pada produk '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			 $dt=$this->Transaksi_model->penilaian_kompetitor_hdr($this->input->post('panelis'),date('Y-m-d',strtotime($this->input->post('tgl'))),$this->input->post('id_formula1'),$this->input->post('ke'))->row();
			$form=array(	'keterangan'		=>		'Panelis Kompetitor oleh '.$this->input->post('panelis')
							,'id_formula'		=>		$this->input->post('id_formula1')
							,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
							,'tgl_input'		=>		date('Y-m-d')
							);
			$this->Transaksi_model->add_form($form,'stage_formula');
			$k=$this->input->post('k');
			echo $dt->id_penilai;
			for($i=1;$i<=$k;$i++)
			{
				$form=array('id_hdr'		=>		$dt->id_penilai
							,'id_penilaian'		=>		$this->input->post('id'.$i)
							,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
							,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))						
							,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
							);
				$this->Transaksi_model->add_form($form,'penilaian_kompetitor_dtl');  
				
			}
			
			redirect('panelis_kompetitor/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_panelis_kompetitor($id)
	{
		$this->cek_login();
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->U==1)
		{
			$arr=explode('-',$id);
			$id_hdr=$arr[0];
			$id_formula=$arr[1];
			$data['main_view']='v_panelis_kompetitor';
			$hdr=$this->Transaksi_model->hdr_penilaian_kompetitor2($id_hdr)->row();
			$data['status_kompetitor']=$hdr->status_kompetitor;
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']=$hdr->panelis;
			$data['nama']=$hdr->nama;
			$data['ke_selected']=$hdr->ke;
			$data['default']['kesimpulan']=$hdr->kesimpulan;
			$data['default']['tgl']=date('d-m-Y',strtotime($hdr->tanggal));
			$data['default']['tgl_real']=date('d-m-Y',strtotime($hdr->tgl_real));
			$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar');
			$list=$this->Transaksi_model->dtl_penilaian_kompetitor($id_hdr,3)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id_penilaian);
				$id_=form_hidden('id_'.$k,$list->id);
				$id_form=form_hidden('id_formula'.$k,$id_formula);
				$nilai=form_input('nilai'.$k,round($list->nilai,2),$js2);
				$skala=form_input('skala'.$k,round($list->skala,2),$js2);
				$keterangan=form_textarea('keterangan'.$k,$list->keterangan,$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_.''.$id_form);
			} 
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis_kompetitor($id_formula,3)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			}
			$data['table2'] = $this->table->generate();
			$data['auth_menu_kompetitor_panelis']=$auth_menu_kompetitor_panelis;
			$data['form']=site_url('mkt/edit_penilaian_kompetitor/'.$id_hdr);
			$data['form4']=site_url('produk_kompetitor/'.$hdr->id);

			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_penilaian_kompetitor($id)
	{
		
		$this->cek_login();
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->U==1)
		{
			$form=array(	'panelis'			=>		$this->input->post('panelis')
							,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
							,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
							,'kesimpulan'		=>		$this->input->post('kesimpulan')
							,'id_formula'		=>		$this->input->post('id_formula1')
							,'ke'		=>		$this->input->post('ke')
							);
			$this->Transaksi_model->edit_form('id_penilai',$id,$form,'penilaian_kompetitor_hdr');
			 $a=$this->Transaksi_model->kompetitor($this->input->post('id_formula1'))->row();
			$form=array('kegiatan'		=>		'Mengubah panelis kompetitor '.$a->nama.' Oleh '.$this->input->post('panelis').' pada produk '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$k=$this->input->post('k');
			for($i=1;$i<=$k;$i++)
			{
				/* $form=array(
							'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
							,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))						
							,'keterangan'		=>		ucfirst(strtolower($this->input->post('keterangan'.$i)))
							);
				$id_=array(
							'id_penilaian'		=>		$this->input->post('id'.$i),
							'id_hdr'			=>		$id
							);
						
			$this->Transaksi_model->edit_form2($id_,$form,'penilaian_kompetitor_dtl');
			 */
					$id_dtl=$this->input->post('id'.$i);
					if($id_dtl!='')
					{
						
						$form=array(
							'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
							,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))						
							,'keterangan'		=>		ucfirst(strtolower($this->input->post('keterangan'.$i)))
							);
						$id_=array(
									'id_penilaian'		=>		$this->input->post('id'.$i),
									'id_hdr'			=>		$id
									);
								
						$this->Transaksi_model->edit_form2($id_,$form,'penilaian_kompetitor_dtl');
							
					}
					else
					{
						$form=array('id_hdr'		=>		$id
								,'id_penilaian'		=>		$this->input->post('id_'.$i)
								,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
								,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
								,'keterangan'		=>		ucfirst(strtolower($this->input->post('keterangan'.$i)))
								);
						$this->Transaksi_model->add_form($form,'penilaian_kompetitor_dtl');
					}
				
			}	
			redirect('panelis_kompetitor/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_penilaian_kompetitor($id)
	{
		$this->cek_login();
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->D==1)
		{
			$arr=explode('-',$id);
			$id=$arr[0];
			$id_formula=$arr[1];
			
			$a=$this->Transaksi_model->hdr_penilaian_kompetitor2($id)->row();
			$form=array('kegiatan'		=>		'Menghapus panelis kompetitor '.$a->nama.' Oleh '.$a->panelis.' pada produk '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_form2($arr[0],'penilaian_kompetitor_dtl','id_hdr');
			$this->Transaksi_model->delete_form2($arr[0],'penilaian_kompetitor_hdr','id_penilai');
			redirect('panelis_kompetitor/'.$arr[1]);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function kompetitor_dtl($id)
	{
		$this->cek_login();
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->R==1)
		{
			$arr=explode("-",$id);
			$id_formula=$arr[0];
			$id_produk=$arr[1];
			$data['main_view']='v_resume_kompetitor';
			$data['id_formula']=$id_formula;
			$a=$this->Transaksi_model->resume_kompetitor($id_formula)->row();
			$data['dt']=$a;	
			$id=$a->id_item;
			$data['form2']=site_url('produk_kompetitor/'.$id_produk);
			$data['form3']=site_url('mkt/excel_kompetitor_dtl/'.$id_formula.'-'.$id_produk);
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
		
	}
	function excel_kompetitor_dtl($id)
	{
		
		$this->load->library("excel");
		$id_menu_kompetitor_panelis=11;
		$auth_menu_kompetitor_panelis=$this->checkaut_menu($id_menu_kompetitor_panelis);
		if($auth_menu_kompetitor_panelis->R==1)
		{
			$object = new PHPExcel();
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			$arr=explode("-",$id);
			$id_formula=$arr[0];
			$id_produk=$arr[1];
			$a=$this->Transaksi_model->resume_kompetitor($id_formula)->row();
			
			$object->getActiveSheet()->setTitle('Header');
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(25);
			
			$object->getActiveSheet()->setCellValue('A1','Nama Produk :');
			$object->getActiveSheet()->setCellValue('A2','P.Line : :');
			$object->getActiveSheet()->setCellValue('A3','Risetman :');
			$object->getActiveSheet()->setCellValue('A4','Target Riset :');
			$object->getActiveSheet()->setCellValue('A5','Awal Riset Produk:');
			$object->getActiveSheet()->setCellValue('A6','Nama Kompetitor :');
			$object->getActiveSheet()->setCellValue('A7','Foto Produk :');
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$object->getActiveSheet()->setCellValue('B1',$a->nama_item);
			$object->getActiveSheet()->setCellValue('B2',$a->lineproduk);
			$object->getActiveSheet()->setCellValue('B3',$a->risetman);
			$object->getActiveSheet()->setCellValue('B4',$a->kompetitor);
			$object->getActiveSheet()->setCellValue('B5',date("d-m-Y",strtotime($a->awal_riset)));
			$object->getActiveSheet()->setCellValue('B6',$a->nama);
			
			if($a->foto!="")
			{
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName("foto");
				$objDrawing->setDescription("foto");
				$objDrawing->setPath('./uploads/kompetitor/'.$a->foto);
				$objDrawing->setCoordinates('B7');
				$objDrawing->setHeight(120); 
				$objDrawing->setWidth(120);
				$objDrawing->setWorksheet($object->getActiveSheet());
			}
			for($i=1;$i<=7;$i++)
			{
				$object->getActiveSheet()->getCellByColumnAndRow(1,$i)->getStyle()->getAlignment()->setWrapText(true);
			}
			$sheet = $object->createSheet(1);
			
			// Rename sheet
			$sheet->setTitle("Panelis");
			$sheet->getColumnDimension('A')->setWidth(25);
			$sheet->setCellValue('A1','Nama Produk :');
			$sheet->setCellValue('A2','Risetman :');
			$sheet->setCellValue('A3','Awal Riset Produk:');
			$sheet->setCellValue('A4','Nama Kompetitor :');
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->setCellValue('B1',$a->nama_item);
			$sheet->setCellValue('B2',$a->risetman);
			$sheet->setCellValue('B3',date("d-m-Y",strtotime($a->awal_riset)));
			$sheet->setCellValue('B4',$a->nama);
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->getColumnDimension('C')->setWidth(25);
			$sheet->getColumnDimension('D')->setWidth(15);
			$sheet->getColumnDimension('E')->setWidth(15);
			$sheet->getColumnDimension('F')->setWidth(10);
			$sheet->getColumnDimension('G')->setWidth(10);
			$sheet->getColumnDimension('H')->setWidth(25);
			
			$sheet->getColumnDimension('I')->setWidth(25);
			$r1s=$this->Transaksi_model->penilaian_kompetitor_list($id_formula)->result();
			if(count($r1s)>0)
			{
				$sheet->setCellValue('A9', 'Panelis');
				$row=10;
				$sheet->setCellValueByColumnAndRow(0, $row, 'Nama');
				$sheet->setCellValueByColumnAndRow(1, $row, 'Tanggal Panelis');
				$sheet->setCellValueByColumnAndRow(2, $row, 'Tanggal Real');
				$sheet->setCellValueByColumnAndRow(3, $row, 'Var');
				$sheet->setCellValueByColumnAndRow(4, $row, 'Subvar');
				$sheet->setCellValueByColumnAndRow(5, $row, 'Nilai');
				$sheet->setCellValueByColumnAndRow(6, $row, 'Skala');
				$sheet->setCellValueByColumnAndRow(7, $row, 'Keterangan');
				$sheet->setCellValueByColumnAndRow(8, $row, 'Kesimpulan');
				for($i=0;$i<=8;$i++)
				{
					$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
				}
				$row++;
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				$mer=array();
				foreach($r1s as $r1)	
				{
					$panelis2=$r1->panelis.''.$r1->tanggal;
					if($panelis1!=$panelis2)
					{
						$pan=0;
					}
					$pan++;
					$mer[$panelis2]=$pan;
					$panelis1=$panelis2;
					
					$var2=$r1->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
				}
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				foreach($r1s as $r1)
				{
					$panelis2=$r1->panelis.''.$r1->tanggal;
					if($panelis1!=$panelis2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r1->panelis);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(1, $row, date("d-m-Y",strtotime($r1->tanggal)));
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(2, $row, date("d-m-Y",strtotime($r1->tgl_real)));
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$panelis2]-1);
					}
					$var2=$r1->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r1->varr);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					
					
					$sheet->setCellValueByColumnAndRow(4, $row, $r1->subvar);
					$sheet->setCellValueByColumnAndRow(5, $row, round($r1->nilai,2));
					$sheet->setCellValueByColumnAndRow(6, $row, round($r1->skala,2));
					$sheet->setCellValueByColumnAndRow(7, $row, $r1->keterangan);
					if($panelis1!=$panelis2)
					{
						$sheet->setCellValueByColumnAndRow(8, $row, $r1->kesimpulan);
						$sheet->mergeCellsByColumnAndRow(8,$row,8,$row+$mer[$panelis2]-1);
						
					}
					$panelis1=$panelis2;
					$sheet->getCellByColumnAndRow(7,$row)->getStyle()->getAlignment()->setWrapText(true);
					$sheet->getCellByColumnAndRow(8,$row)->getStyle()->getAlignment()->setWrapText(true);
					for($i=0;$i<=8;$i++)
					{
						$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					}
					$row++;
				}
				
				
			}	
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Tabel Resume Kompetitor.xls"');
			$object_writer->save('php://output');
		}
		else
		{
			echo "Access Deny";
			
		}
	}

	function resume_produk($id_item)
	{
		$this->cek_login();
		$id_menu9=9;//produk
		$auth_menu9=$this->checkaut_menu($id_menu9);//produk
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$id_menu13=13;//panelis risetman
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$id_menu14=14;//panelis internal
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$id_menu15=15;//panelis taste specialist
		$auth_menu15=$this->checkaut_menu($id_menu15);
		$id_menu16=16;//kesimpulan panelis taste specialist
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$id_menu17=17;//formula terbaik
		$auth_menu17=$this->checkaut_menu($id_menu17);
		$id_menu18=18;//pending produk
		$auth_menu18=$this->checkaut_menu($id_menu18);
		if($auth_menu9->R==1)
		{
			$data['auth_menu9']=$auth_menu9;
			$data['auth_menu12']=$auth_menu12;
			$data['auth_menu13']=$auth_menu13;
			$data['auth_menu14']=$auth_menu14;
			$data['auth_menu15']=$auth_menu15;
			$data['auth_menu16']=$auth_menu16;
			$data['auth_menu17']=$auth_menu17;
			$data['auth_menu18']=$auth_menu18;
			$auth_item=$this->checkaut_item($id_item);
			if(count($auth_item)>0)
			{
				$data['main_view']='v_resume';
				$a=$this->Transaksi_model->resume_item($id_item)->row();
				$data['nama_item']=$a->nama_item;

				$data['line']=$a->lineproduk;
				$data['risetman']=$a->risetman;
				$data['kompetitor']=$a->kompetitor;
				$data['awal_riset']=$a->awal_riset;
				if(!empty($a->nama_konsep_sebelumnya)){ 
				$data['nama_konsep_sebelumnya']=$a->nama_konsep_sebelumnya;
				$data['id_konsep_sebelumnya']=$a->id_konsep_sebelumnya;	
				}
				else
				{
				$data['nama_konsep_sebelumnya']='';
				$data['id_konsep_sebelumnya']='';	
				}
				$num=$this->Transaksi_model->resume_kriteria($id_item,'base')->num_rows();
				$data['base']='';
				if($num>0)
				{
					$b=$this->Transaksi_model->resume_kriteria($id_item,'base')->result();
					$data['base']='';
					foreach($b as $b)
					{
						$data['base'].=$b->subvar.'('.$b->skala.')'.',';	
					}
				}
				$num2=$this->Transaksi_model->resume_kriteria($id_item,'Rasa Aroma')->num_rows();
				$data['rasa_aroma']='';
				if($num2>0)
				{			
					$c=$this->Transaksi_model->resume_kriteria($id_item,'Rasa Aroma')->result();	
					foreach($c as $c)
					{
						$data['rasa_aroma'].=$c->subvar.'('.$c->skala.')'.',';	
					}
				}
				$data['total_rasa']='';
				$num3=$this->Transaksi_model->resume_kriteria($id_item,'Total Rasa')->num_rows();
				if($num3>0)
				{
					$d=$this->Transaksi_model->resume_kriteria($id_item,'Total Rasa')->result();			
					foreach($d as $d)
					{
						$data['total_rasa'].=$d->subvar.'('.$d->skala.')'.',';	
					}
				}
				
				
				$data['link']=$this->Master_model->get_ref_link($id_item)->result();
				$list=$this->Transaksi_model->list_formula($id_item)->result();
				$data['list']=$list;
				
				$id_formula=$this->Transaksi_model->id_formula2();
				$data['form']=site_url('tambah_formula/'.$id_item);
				$data['form2']=site_url('list_produk');	
				$data['form3']=site_url('approve/'.$id_item.'_1');	
				$data['form4']=site_url('mkt/formula_terbaik/'.$id_item);	
				$data['form5']=site_url('mkt/pending/'.$id_item);	
				$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "access deny";
		}
		
	}
	function formula_form($id_item)
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->C==1)
		{
			$auth_item=$this->checkaut_item($id_item);
			if(count($auth_item)>0)
			{
				$data['main_view']='v_formula';
				$data['default']['id_item']=$id_item;
				$hdr=$this->Transaksi_model->resume_item($id_item)->row();
				$data['nama_item']=$hdr->nama_item;	
				$num_rst=$this->Transaksi_model->risetman_hdr($id_item)->num_rows();
				if($num_rst>0)
				{
					
					$risetmans='';
					$risetmana=array();
					$risetman=$this->Transaksi_model->risetman_hdr($id_item)->result();
					foreach($risetman as $risetman)
					{
						$risetmans.=$risetman->risetman.',';
						array_push($risetmana,$risetman->risetman);
					}
							$data['risetmans']=rtrim($risetmans,',');
							$data['risetmana']=$risetmana;

				}
				$data['risetman_hdr']=$this->Master_model->get_risetman()->result();
				$data['form']=site_url('mkt/add_formula');
				$data['bahan']=$this->Master_model->get_bahan()->result();
				$data['bahan_selected']='';
				$data['sarana']=$this->Master_model->get_sarana()->result();
				$data['sarana_selected']='';
				$data['risetman']=$this->Master_model->get_risetman()->result();
				$data['risetman_selected']='';
				$data['formula']=$this->Transaksi_model->list_formula($id_item)->result();
				$data['formula_selected']='';
				$num_ref=$this->Master_model->get_ref_link($id_item)->num_rows();
				if($num_ref>0)
				{
					
					$links='';
					$linka=array();
					//$data['link']=array();
					$ref=$this->Master_model->get_ref_link($id_item)->result();
					foreach($ref as $link)
					{
						
						$data['linka']='';
						$links.=$link->id.',';
					}
							$data['links']=rtrim($links,',');
							$data['link']=$this->Transaksi_model->list_link_formula($data['links'])->result();
							$data['linka']=$linka;

				}
				
				if($this->session->flashdata('message_formula')!='') 
				{
					$data['default']['id_formula']=$this->session->flashdata('id_formula');	
					$data['default']['kode']=$this->session->flashdata('kode');	
					$data['default']['tgl']=$this->session->flashdata('tgl_riset');	
					$data['default']['risetman_hdr']=$this->session->flashdata('risetman_hdr');	
					$data['default']['tujuan']=$this->session->flashdata('tujuan');	
					$data['risetman_selected']=$this->session->flashdata('risetman');
				}
				else
				{
					$num=$this->Transaksi_model->id_formula2()->num_rows();
					if($num>0)
					{
						$a=$this->Transaksi_model->id_formula2()->row();
						$id_formula=$a->id+1;	
					}
					else
					{
						$id_formula=1;
					}
							$form=array('id_item'		=> $id_item
								,'id'		=> $id_formula
								);
					$this->Transaksi_model->add_form($form,'formula2');
					$data['default']['id_formula']=$id_formula;	
				}
				$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		
		
	}
	function add_formula()
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->C==1)
		{
			$id_item=$this->input->post('id_item');
			$id_formula=$this->input->post('id_formula');
			$formula=$this->Transaksi_model->formula_list_ex($id_item,$id_formula)->result();
			$this->Transaksi_model->delete_form2($id_formula,'cek_formula','id_formula');
			foreach($formula as $formula)
			{
				$cek=$this->Transaksi_model->cek_formula($id_formula,$formula->id)->num_rows();
				$cek2=$this->Transaksi_model->cek_formula($formula->id,$id_formula)->num_rows();
				if($cek==0 && $cek2==0)
				{
					$form=array('id_formula'		=> $id_formula
						,'id_sama'		=> $formula->id
						);
					$this->Transaksi_model->add_form($form,'cek_formula');
				}
				
			}
			$cek3=$this->Transaksi_model->cek_formula2($id_formula)->num_rows();
			if($cek3==0)
			{
			$form=array('id_item'		=> $id_item
						,'id'		=> $id_formula
						,'kode'		=> $this->input->post('kode')
						,'tgl_riset'		=> date('Y-m-d',strtotime($this->input->post('tgl')))
						,'risetman'		=> $this->input->post('risetman')
						,'tujuan'		=> ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('tujuan')))))
						);
			$this->Transaksi_model->edit_form('id',$id_formula,$form,'formula2');
			$a=$this->Transaksi_model->hdr_formula($id_formula)->row();		
			$form=array('kegiatan'		=>		'Membuat Formula '.$this->input->post('kode').' produk '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');		
			
			$form=array('id_formula'	=> $id_formula
						,'keterangan'	=> 'Membuat formula '.$this->input->post('kode')
						,'tanggal'		=> date('Y-m-d',strtotime($this->input->post('tgl')))
						,'tgl_input'	=> date('Y-m-d')
						);
			$this->Transaksi_model->add_form($form,'stage_formula');
			if($this->input->post('risetman_hdr'))
			{
				foreach($this->input->post('risetman_hdr') as $risetman)
				{
					$form=array(
						'id_formula'				=> $id_formula,
						'risetman'			=> $risetman
						);
					$this->Transaksi_model->add_form($form,'risetman_formula');		
				}
			}
			if($this->input->post('link'))
			{
				foreach($this->input->post('link') as $link)
				{
					$form=array(
						'id_formula'				=> $id_formula,
						'link_formula'			=> $link
						);
					$this->Transaksi_model->add_form($form,'ref_formula');		
				}
			}
			else
			{
				$data['link_selected']='';
			}
			
			//redirect('resume_produk/'.$id_item);
			redirect('panelis_risetman/'.$id_formula);
			}
			else
			{
				$kode=$this->Transaksi_model->cek_formula2($id_formula)->row();
				$pesan='Bahan formula identik dengan ' .$kode->kode.'. Formula tidak dapat disimpan';
				$this->session->set_flashdata('message_formula', $pesan);
				$this->session->set_flashdata('id_formula', $id_formula);
				$this->session->set_flashdata('kode', $this->input->post('kode'));
				$this->session->set_flashdata('tgl_riset', $this->input->post('tgl'));
				$this->session->set_flashdata('risetman', $this->input->post('risetman'));
				$this->session->set_flashdata('risetman_hdr', $this->input->post('risetman_hdr'));
				$this->session->set_flashdata('tujuan', $this->input->post('tujuan'));
				redirect('tambah_formula/'.$id_item);
				
			}
		}
		else
		{
			echo "Access Deny";
		}

	}
	function edit_formula_form($id_formula)
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->U==1)
		{
			$data['main_view']='v_formula';
			$a=$this->Transaksi_model->formula($id_formula)->row();
			$auth=$this->checkaut_item($a->id_item);
			if(count($auth)>0)
			{
				$data['default']['id_item']=$a->id_item;	
				$data['default']['kode']=$a->kode;	
				$data['nama_item']=$a->nama_item;	
				$data['default']['tgl']=date('d-m-Y',strtotime($a->tgl_riset));	
				$data['risetman']=$this->Master_model->get_risetman()->result();
				$data['risetman_selected']=$a->risetman;
				$data['risetman_hdr']=$this->Master_model->get_risetman()->result();
				
				$data['default']['risetman_hdr']=$a->risetman_hdr;
				$data['default']['tujuan']=$a->tujuan;	
				$data['default']['id_formula']=$id_formula;	
				$data['bahan']=$this->Master_model->get_bahan()->result();
				$data['bahan_selected']='';
				$data['sarana']=$this->Master_model->get_sarana()->result();
				$data['sarana_selected']='';
				$data['formula']=$this->Transaksi_model->list_formula($a->id_item)->result();
				$data['formula_selected']='';
				$data['form']=site_url('mkt/edit_formula');		
				$num_rst=$this->Transaksi_model->risetman_formula($id_formula)->num_rows();
				if($num_rst>0)
				{
					
					$risetmans='';
					$risetmana=array();
					$risetman=$this->Transaksi_model->risetman_formula($id_formula)->result();
					foreach($risetman as $risetman)
					{
						$risetmans.=$risetman->risetman.',';
						array_push($risetmana,$risetman->risetman);
					}
							$data['risetmans']=rtrim($risetmans,',');
							$data['risetmana']=$risetmana;

				}
				$num_ref=$this->Master_model->get_ref_link($a->id_item)->num_rows();
				if($num_ref>0)
				{
					
					$links='';
					$linka=array();
					//$data['link']=array();
					$ref=$this->Master_model->get_ref_link($a->id_item)->result();
					foreach($ref as $link)
					{
						
						$links.=$link->id.',';
						array_push($linka,$link->id);
					}
							$data['links']=rtrim($links,',');
							$data['link']=$this->Transaksi_model->list_link_formula($data['links'])->result();
					$num_ref=$this->Master_model->get_ref_formula($id_formula)->num_rows();
					if($num_ref>0)
					{
						
						$links='';
						$linka=array();
						$ref=$this->Master_model->get_ref_formula($id_formula)->result();
						foreach($ref as $link)
						{
							$links.=$link->link_formula.',';
							array_push($linka,$link->link_formula);
						}
								$data['linka']=$linka;

					}
				}
				$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "access deny";
		}
		
		
		
	}
	function edit_formula()
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->U==1)
		{
			$id_item=$this->input->post('id_item');
			$id_formula=$this->input->post('id_formula');
			$form=array('id_item'		=> $id_item
						,'kode'		=> $this->input->post('kode')
						,'tgl_riset'		=> date('Y-m-d',strtotime($this->input->post('tgl')))
						,'risetman'		=> $this->input->post('risetman')
						,'tujuan'		=> ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('tujuan')))))
						);
			$this->Transaksi_model->edit_form('id',$id_formula,$form,'formula2');
			$a=$this->Transaksi_model->hdr_formula($id_formula)->row();		
			$form=array('kegiatan'		=>		'Mengubah Formula '.$this->input->post('kode').' produk '.$a->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');	
			$this->Transaksi_model->delete_form2($id_formula,'risetman_formula','id_formula');

			if($this->input->post('risetman_hdr'))
			{
				foreach($this->input->post('risetman_hdr') as $risetman)
				{
					$form=array(
						'id_formula'		=> $id_formula,
						'risetman'			=> $risetman
						);
					$this->Transaksi_model->add_form($form,'risetman_formula');
				}
			}
			$this->Transaksi_model->delete_form2($id_formula,'ref_formula','id_formula');
			if($this->input->post('link'))
			{
				foreach($this->input->post('link') as $link)
				{
					$form=array(
						'id_formula'				=> $id_formula,
						'link_formula'			=> $link
						);
					$this->Transaksi_model->add_form($form,'ref_formula');		
				}
			}
			redirect('resume_produk/'.$id_item);
		}
		else
		{
			echo "Access Deny";
		}
		
	}
	
	function formula_dtl($id_formula)
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->R==1)
		{
			$data['main_view']='v_resume_formula';
			$data['id_formula']=$id_formula;
			$a=$this->Transaksi_model->resume_formula($id_formula)->row();
			$data['dt']=$a;	
			$id=$a->id_item;
			$auth_item=$this->checkaut_item($id);
			if(count($auth_item)>0)
			{
				$data['form2']=site_url('resume_produk/'.$id);
				$data['form3']=site_url('mkt/excel_formula_dtl/'.$id_formula);
				$data['link']=$this->Master_model->get_ref_formula($id_formula)->result();
				$this->load->view('sidemenu',$data);
		
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "access deny";
		}
		
		
	}
	function excel_formula_dtl($id_formula)
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->R==1)
		{
			$this->load->library("excel");
			$object = new PHPExcel();
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			$a=$this->Transaksi_model->resume_formula($id_formula)->row();
			
			$object->getActiveSheet()->setTitle("Formula ".$a->kode);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(25);
			$object->getActiveSheet()->setCellValue('A1','Nama Produk :');
			$object->getActiveSheet()->setCellValue('A2','P.Line : :');
			$object->getActiveSheet()->setCellValue('A3','Risetman :');
			$object->getActiveSheet()->setCellValue('A4','Target Riset :');
			$object->getActiveSheet()->setCellValue('A5','Awal Riset Produk:');
			$object->getActiveSheet()->setCellValue('A6','Konsep Sebelumnya :');
			$object->getActiveSheet()->setCellValue('A7','Seri Formula :');
			$object->getActiveSheet()->setCellValue('A8','Tanggal Riset Formula :');
			$object->getActiveSheet()->setCellValue('A9','Sumber Formula :');
			$object->getActiveSheet()->setCellValue('A10','Tujuan Formula :');
			$object->getActiveSheet()->setCellValue('A11','Link Formula :');
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$object->getActiveSheet()->setCellValue('B1',$a->nama_item);
			$object->getActiveSheet()->setCellValue('B2',$a->lineproduk);
			$object->getActiveSheet()->setCellValue('B3',$a->risetman);
			$object->getActiveSheet()->setCellValue('B4',$a->kompetitor);
			$object->getActiveSheet()->setCellValue('B5',date("d-m-Y",strtotime($a->awal_riset)));
			$object->getActiveSheet()->setCellValue('B6',$a->konsep_sebelumnya);
			$object->getActiveSheet()->setCellValue('B7',$a->kode);
			$object->getActiveSheet()->setCellValue('B8',date("d-m-Y",strtotime($a->tgl_riset)));
			$object->getActiveSheet()->setCellValue('B9',$a->risetman);
			$object->getActiveSheet()->setCellValue('B10',$a->tujuan);
			$link_formula="";
			$link=$this->Master_model->get_ref_formula($id_formula)->result();
			foreach($link as $link)
			{
				$link_formula.=$link->nama_item.' formula '.$link->kode.' ,';
			}
				$link_formula=rtrim($link_formula,',');
			$object->getActiveSheet()->setCellValue('B11',$link_formula);
			for($i=1;$i<=11;$i++)
			{
				$object->getActiveSheet()->getCellByColumnAndRow(1,$i)->getStyle()->getAlignment()->setWrapText(true);
			}
			$bahan=$this->Transaksi_model->formula_bahan_all($id_formula)->result();
			if(count($bahan)>0)
			{	
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 13, 'Bahan');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 13, 'Kadar');
				$row=14;
				foreach($bahan as $bahan)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $bahan->kode_bahan);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $bahan->kadar);
					$row++;
				}
			}
			$sarana=$this->Transaksi_model->formula_sarana_all($id_formula)->result();
			if(count($sarana)>0)
			{
				$row++;
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sarana');
				foreach($sarana as $sarana)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $sarana->sarana);
					$row++;
				}
			}
			$sheet = $object->createSheet(1);
			
			// Rename sheet
			$sheet->setTitle("Panelis");
			$sheet->getColumnDimension('A')->setWidth(25);
			$sheet->setCellValue('A1','Nama Produk :');
			$sheet->setCellValue('A2','Risetman :');
			$sheet->setCellValue('A3','Awal Riset Produk:');
			$sheet->setCellValue('A4','Seri Formula :');
			$sheet->setCellValue('A5','Tanggal Riset Formula :');
			$sheet->setCellValue('A6','Sumber Formula :');
			$sheet->setCellValue('A7','Tujuan Formula :');
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->setCellValue('B1',$a->nama_item);
			$sheet->setCellValue('B2',$a->risetman);
			$sheet->setCellValue('B3',date("d-m-Y",strtotime($a->awal_riset)));
			$sheet->setCellValue('B4',$a->kode);
			$sheet->setCellValue('B5',date("d-m-Y",strtotime($a->tgl_riset)));
			$sheet->setCellValue('B6',$a->risetman);
			$sheet->setCellValue('B7',$a->tujuan);
			$sheet->getColumnDimension('H')->setWidth(25);
			$r1s=$this->Transaksi_model->penilaian_formula_list2($id_formula,1)->result();
			if(count($r1s)>0)
			{
				$sheet->setCellValue('A9', 'Panelis Risetman');
				$row=10;
				$sheet->setCellValueByColumnAndRow(0, $row, 'Nama');
				$sheet->setCellValueByColumnAndRow(1, $row, 'Tanggal Panelis');
				$sheet->setCellValueByColumnAndRow(2, $row, 'Tanggal Real');
				$sheet->setCellValueByColumnAndRow(3, $row, 'Var');
				$sheet->setCellValueByColumnAndRow(4, $row, 'Subvar');
				$sheet->setCellValueByColumnAndRow(5, $row, 'Nilai');
				$sheet->setCellValueByColumnAndRow(6, $row, 'Skala');
				$sheet->setCellValueByColumnAndRow(7, $row, 'Keterangan');
				for($i=0;$i<=7;$i++)
				{
					$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
				}
				$row++;
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				$mer=array();
				foreach($r1s as $r1)	
				{
					$panelis2=$r1->panelis.''.$r1->tanggal;
					if($panelis1!=$panelis2)
					{
						$pan=0;
					}
					$pan++;
					$mer[$panelis2]=$pan;
					$panelis1=$panelis2;
					
					$var2=$r1->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
				}
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				foreach($r1s as $r1)
				{
					$panelis2=$r1->panelis.''.$r1->tanggal;
					if($panelis1!=$panelis2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r1->panelis);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(1, $row, date("d-m-Y",strtotime($r1->tanggal)));
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(2, $row, date("d-m-Y",strtotime($r1->tgl_real)));
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$panelis2]-1);
					}
					$panelis1=$panelis2;
					$var2=$r1->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r1->varr);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					
					
					$sheet->setCellValueByColumnAndRow(4, $row, $r1->subvar);
					$sheet->setCellValueByColumnAndRow(5, $row, round($r1->nilai,2));
					$sheet->setCellValueByColumnAndRow(6, $row, round($r1->skala,2));
					$sheet->setCellValueByColumnAndRow(7, $row, $r1->keterangan);
					$sheet->getCellByColumnAndRow(7,$row)->getStyle()->getAlignment()->setWrapText(true);
					for($i=0;$i<=7;$i++)
					{
						$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					}
					$row++;
				}
				
				
			}	
			$numkes=$this->Transaksi_model->kesimpulan($id_formula,1)->num_rows();
			if($numkes>0)
			{
				$kes=$this->Transaksi_model->kesimpulan($id_formula,1)->row();
				$kesimpulan=$kes->kesimpulan;
			}
			else
			{
				$kesimpulan="";
			}
			$numsm=$this->Transaksi_model->get_penilaian_masalah($id_formula,1)->num_rows();
			if($numsm>0)
			{
				$sm=$this->Transaksi_model->get_penilaian_masalah($id_formula,1)->result();
				$sumber_masalah="";

				foreach($sm as $sm)
				{
					$sumber_masalah.=$sm->masalah.',';
				}
				$sumber_masalah=rtrim($sumber_masalah,',');
			}
			else
			{
				$sumber_masalah="";
			}
			
			$desc_sm=$this->Transaksi_model->get_kesimpulan($id_formula,1)->row();
			if(count($desc_sm)>0)
			{
				$desc=$desc_sm->deskripsi;
			}
			else
			{
				$desc='';
			}
			$numac=$this->Transaksi_model->kesimpulan($id_formula,1)->num_rows();
			if($numac>0)
			{
				$ac=$this->Transaksi_model->kesimpulan($id_formula,1)->row();
				$action=$ac->action_plan;
			}
			else
			{
				$action="";
			}
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
			$sheet->setCellValueByColumnAndRow(1, $row, $kesimpulan);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
			$sheet->setCellValueByColumnAndRow(1, $row, $sumber_masalah);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
			$sheet->setCellValueByColumnAndRow(1, $row, $desc);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Action Plan');
			$sheet->setCellValueByColumnAndRow(1, $row, $action);
			
			$r2s=$this->Transaksi_model->penilaian_formula_list2($id_formula,2)->result();
			if(count($r2s)>0)
			{
				$row+=2;
				$sheet->setCellValueByColumnAndRow(0, $row, 'Panelis Internal');
				$row++;
				$sheet->setCellValueByColumnAndRow(0, $row, 'Nama');
				$sheet->setCellValueByColumnAndRow(1, $row, 'Tanggal Panelis');
				$sheet->setCellValueByColumnAndRow(2, $row, 'Tanggal Real');
				$sheet->setCellValueByColumnAndRow(3, $row, 'Var');
				$sheet->setCellValueByColumnAndRow(4, $row, 'Subvar');
				$sheet->setCellValueByColumnAndRow(5, $row, 'Nilai');
				$sheet->setCellValueByColumnAndRow(6, $row, 'Skala');
				$sheet->setCellValueByColumnAndRow(7, $row, 'Keterangan');
				for($i=0;$i<=7;$i++)
				{
					$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
				}
				$row++;
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				$mer=array();
				foreach($r2s as $r2)	
				{
					$panelis2=$r2->panelis.''.$r2->tanggal;
					if($panelis1!=$panelis2)
					{
						$pan=0;
					}
					$pan++;
					$mer[$panelis2]=$pan;
					$panelis1=$panelis2;
					
					$var2=$r2->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
				}
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				foreach($r2s as $r2)
				{
					$panelis2=$r2->panelis.''.$r2->tanggal;
					if($panelis1!=$panelis2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r2->panelis);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(1, $row, date("d-m-Y",strtotime($r2->tanggal)));
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(2, $row, date("d-m-Y",strtotime($r2->tgl_real)));
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$panelis2]-1);
					}
					$panelis1=$panelis2;
					$var2=$r2->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r2->varr);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					$sheet->setCellValueByColumnAndRow(4, $row, $r2->subvar);
					$sheet->setCellValueByColumnAndRow(5, $row, round($r2->nilai,2));
					$sheet->setCellValueByColumnAndRow(6, $row, round($r2->skala,2));
					$sheet->setCellValueByColumnAndRow(7, $row, $r2->keterangan);
					$sheet->getCellByColumnAndRow(7,$row)->getStyle()->getAlignment()->setWrapText(true);
					for($i=0;$i<=7;$i++)
					{
						$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					}
					$row++;
				}
				
				
			}	
			$numkes=$this->Transaksi_model->kesimpulan($id_formula,2)->num_rows();
			if($numkes>0)
			{
				$kes=$this->Transaksi_model->kesimpulan($id_formula,2)->row();
				$kesimpulan=$kes->kesimpulan;
			}
			else
			{
				$kesimpulan="";
			}
			$numsm=$this->Transaksi_model->get_penilaian_masalah($id_formula,2)->num_rows();
			if($numsm>0)
			{
				$sm=$this->Transaksi_model->get_penilaian_masalah($id_formula,2)->result();
				$sumber_masalah="";

				foreach($sm as $sm)
				{
					$sumber_masalah.=$sm->masalah.',';
				}
				$sumber_masalah=rtrim($sumber_masalah,',');
			}
			else
			{
				$sumber_masalah="";
			}
			
			$desc_sm=$this->Transaksi_model->get_kesimpulan($id_formula,2)->row();
			if(count($desc_sm)>0)
			{
				$desc=$desc_sm->deskripsi;
			}
			else
			{
				$desc='';
			}
			$numac=$this->Transaksi_model->kesimpulan($id_formula,2)->num_rows();
			if($numac>0)
			{
				$ac=$this->Transaksi_model->kesimpulan($id_formula,2)->row();
				$action=$ac->action_plan;
			}
			else
			{
				$action="";
			}
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
			$sheet->setCellValueByColumnAndRow(1, $row, $kesimpulan);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
			$sheet->setCellValueByColumnAndRow(1, $row, $sumber_masalah);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
			$sheet->setCellValueByColumnAndRow(1, $row, $desc);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Action Plan');
			$sheet->setCellValueByColumnAndRow(1, $row, $action);
			
			$r3s=$this->Transaksi_model->penilaian_formula_list2($id_formula,3)->result();
			if(count($r3s)>0)
			{
				$row+=2;
				$sheet->setCellValueByColumnAndRow(0, $row, 'Panelis Taste Specialist');
				$row++;
				$sheet->setCellValueByColumnAndRow(0, $row, 'Nama');
				$sheet->setCellValueByColumnAndRow(1, $row, 'Tanggal Panelis');
				$sheet->setCellValueByColumnAndRow(2, $row, 'Tanggal Real');
				$sheet->setCellValueByColumnAndRow(3, $row, 'Var');
				$sheet->setCellValueByColumnAndRow(4, $row, 'Subvar');
				$sheet->setCellValueByColumnAndRow(5, $row, 'Nilai');
				$sheet->setCellValueByColumnAndRow(6, $row, 'Skala');
				$sheet->setCellValueByColumnAndRow(7, $row, 'Keterangan');
				
				for($i=0;$i<=7;$i++)
				{
					$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
				}
				$row++;
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				$mer=array();
				foreach($r3s as $r3)	
				{
					$panelis2=$r3->panelis.''.$r3->tanggal;
					if($panelis1!=$panelis2)
					{
						$pan=0;
					}
					$pan++;
					$mer[$panelis2]=$pan;
					$panelis1=$panelis2;
					
					$var2=$r3->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
				}
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				foreach($r3s as $r3)
				{
					$panelis2=$r3->panelis.''.$r3->tanggal;
					if($panelis1!=$panelis2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r3->panelis);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(1, $row, date("d-m-Y",strtotime($r3->tanggal)));
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$panelis2]-1);
						$sheet->setCellValueByColumnAndRow(2, $row, date("d-m-Y",strtotime($r3->tgl_real)));
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$panelis2]-1);
					}
					$panelis1=$panelis2;
					$var2=$r3->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r3->varr);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					$sheet->setCellValueByColumnAndRow(4, $row, $r3->subvar);
					$sheet->setCellValueByColumnAndRow(5, $row, round($r3->nilai,2));
					$sheet->setCellValueByColumnAndRow(6, $row, round($r3->skala,2));
					$sheet->setCellValueByColumnAndRow(7, $row, $r3->keterangan);
					$sheet->getCellByColumnAndRow(7,$row)->getStyle()->getAlignment()->setWrapText(true);
					for($i=0;$i<=7;$i++)
					{
						$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					}
					$row++;
				}
				
				
			}	
			$numsm=$this->Transaksi_model->get_penilaian_masalah($id_formula,3)->num_rows();
			if($numsm>0)
			{
				$sm=$this->Transaksi_model->get_penilaian_masalah($id_formula,3)->result();
				$sumber_masalah="";

				foreach($sm as $sm)
				{
					$sumber_masalah.=$sm->masalah.',';
				}
				$sumber_masalah=rtrim($sumber_masalah,',');
			}
			else
			{
				$sumber_masalah="";
			}
			
			$desc_sm=$this->Transaksi_model->get_kesimpulan($id_formula,3)->row();
			if(count($desc_sm)>0)
			{
				$desc=$desc_sm->deskripsi;
			}
			else
			{
				$desc='';
			}
			$numac=$this->Transaksi_model->kesimpulan($id_formula,3)->num_rows();
			if($numac>0)
			{
				$ac=$this->Transaksi_model->kesimpulan($id_formula,3)->row();
				$action=$ac->action_plan;
			}
			else
			{
				$action="";
			}
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
			$sheet->setCellValueByColumnAndRow(1, $row, $sumber_masalah);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
			$sheet->setCellValueByColumnAndRow(1, $row, $desc);
			$row++;
			$sheet->setCellValueByColumnAndRow(0, $row, 'Action Plan');
			$sheet->setCellValueByColumnAndRow(1, $row, $action);
			
			$sheet = $object->createSheet(2);
			
			// Rename sheet
			$sheet->setTitle("Kesimpulan TS");
			$sheet->getColumnDimension('A')->setWidth(25);
			$sheet->setCellValue('A1','Nama Produk :');
			$sheet->setCellValue('A2','Risetman :');
			$sheet->setCellValue('A3','Awal Riset Produk:');
			$sheet->setCellValue('A4','Seri Formula :');
			$sheet->setCellValue('A5','Tanggal Riset Formula :');
			$sheet->setCellValue('A6','Sumber Formula :');
			$sheet->setCellValue('A7','Tujuan Formula :');
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->getColumnDimension('C')->setWidth(25);
			$sheet->getColumnDimension('D')->setWidth(25);
			$sheet->setCellValue('B1',$a->nama_item);
			$sheet->setCellValue('B2',$a->risetman);
			$sheet->setCellValue('B3',date("d-m-Y",strtotime($a->awal_riset)));
			$sheet->setCellValue('B4',$a->kode);
			$sheet->setCellValue('B5',date("d-m-Y",strtotime($a->tgl_riset)));
			$sheet->setCellValue('B6',$a->risetman);
			$sheet->setCellValue('B7',$a->tujuan);
			$row=9;
			
			$sheet->setCellValueByColumnAndRow(0, $row, 'Panelis');
			$sheet->setCellValueByColumnAndRow(1, $row, 'Var');
			$sheet->setCellValueByColumnAndRow(2, $row, 'kesimpulan');
			$sheet->setCellValueByColumnAndRow(3, $row, 'Action Plan');
			for($i=0;$i<=3;$i++)
			{
				$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
			}
			$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($a->id_item)->result();
			$panelis1='';
			$panelis2='';
			foreach($kes_hdr as $kes_hdr)
			{
				$row++;
				if($kes_hdr->parameter=='base')
				{
					$parameter="Base";
				}
				else if($kes_hdr->parameter=='rasa_aroma')
				{
					$parameter="Rasa Aroma";
				}
				else if($kes_hdr->parameter=='total_rasa')
				{
					$parameter="Total Rasa";
				}
				$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
				if($numk2>0)
				{
					$kes=$this->Transaksi_model->kesimpulan_ts_dtl($id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
					$kesimpulan=$kes->kesimpulan;
				}
				else
				{
					$kesimpulan="";
				}
				$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
				if($numk2>0)
				{
					$kes=$this->Transaksi_model->kesimpulan_ts_dtl($id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
					$saran=$kes->saran;
				}
				else
				{
					$saran="";
				}
				
				$panelis2=$kes_hdr->panelis;
				if($panelis1!=$panelis2)
				{
					$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+2);
					$sheet->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
				}
				$panelis1=$panelis2;
				
				$sheet->setCellValueByColumnAndRow(1, $row, $parameter);
				$sheet->setCellValueByColumnAndRow(2, $row, $kesimpulan);
				$sheet->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->setCellValueByColumnAndRow(3, $row, $saran);
				$sheet->getCellByColumnAndRow(3,$row)->getStyle()->getAlignment()->setWrapText(true);
				for($i=0;$i<=3;$i++)
				{
					$sheet->getCellByColumnAndRow($i,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
				}
			} 
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Tabel Resume Formula.xls"');
			$object_writer->save('php://output');
		}
		else
		{
			echo "Access Deny ";
		}
	}
	
	function add_bahan_formula()
	{
		 $this->cek_login();
		 $cek=$this->Transaksi_model->kadar_bahan($this->input->post('id_formula'),$this->input->post('bahan'))->num_rows();
		 if($cek==0)
		 {
			$form=array('id_formula'		=> $this->input->post('id_formula')
						,'kode_bahan'		=> $this->input->post('bahan')
						,'kadar'		=> $this->input->post('kadar')
						);
			$this->Transaksi_model->add_form($form,'bahan_Formula'); 
			$data=$this->Transaksi_model->formula_bahan($this->input->post('id_formula'),$this->input->post('bahan'))->result();
			echo json_encode($data);
		 }
	}
	function delete_bahan_formula()
	{
		$this->cek_login();
		
		$data['id']=$this->input->post('id');
		$this->Transaksi_model->delete_form($this->input->post('id'),'bahan_formula');
	}
	
	function get_tabel_bahan_formula()
	{
		$this->cek_login();
		$data=$this->Transaksi_model->formula_bahan_all($this->input->post('id_formula'))->result();
		echo json_encode($data);
	}
	function transfer_bahan()
	{
		$this->cek_login();
		$id_formula=$this->input->post('id_formula');
		$kode_formula=$this->input->post('kode_formula');
		
		$this->Transaksi_model->transfer_bahan($id_formula,$kode_formula);
		$data=$this->Transaksi_model->formula_bahan_all($this->input->post('id_formula'))->result();
		echo json_encode($data);

	}
	function update_bahan_formula()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model->formula_bahan2($id)->result();
		echo json_encode($data);

	}
	function update_bahan_formula2()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$bahan=$this->input->post('bahan');
		$kadar=$this->input->post('kadar');
		$form=array('kode_bahan'	=> $bahan,
					'kadar'			=> $kadar);
		$this->Transaksi_model->edit_form('id',$id,$form,'bahan_formula');
		$data=$this->Transaksi_model->formula_bahan2($id)->result();
		echo json_encode($data);

	}
	
	function total_kadar()
	{
		$data=$this->Transaksi_model->total_kadar($this->input->post('id_formula'))->result();
		echo json_encode($data);
	}
	function add_sarana_formula()
	{
		// $this->cek_login();
		$form=array('id_formula'		=> $this->input->post('id_formula')
					,'id_sarana'		=> $this->input->post('sarana')
					);
		$this->Transaksi_model->add_form($form,'sarana_formula'); 
		$data=$this->Transaksi_model->formula_sarana($this->input->post('id_formula'),$this->input->post('sarana'))->result();
		echo json_encode($data);
		
		

	}
	function delete_sarana_formula()
	{
		$this->cek_login();		
		$data['id']=$this->input->post('id');
		$this->Transaksi_model->delete_form($this->input->post('id'),'sarana_formula');
	}
	
	function get_tabel_sarana_formula()
	{
		$this->cek_login();
		$data=$this->Transaksi_model->formula_sarana_all($this->input->post('id_formula'))->result();
		echo json_encode($data);
	}
	function transfer_sarana()
	{
		$this->cek_login();
		$id_formula=$this->input->post('id_formula');
		$kode_formula=$this->input->post('kode_formula');
		
		$this->Transaksi_model->transfer_sarana($id_formula,$kode_formula);
		$data=$this->Transaksi_model->formula_sarana_all($this->input->post('id_formula'))->result();
		echo json_encode($data);

	}
	function update_sarana_formula()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model->formula_sarana2($id)->result();
		echo json_encode($data);

	}
	function update_sarana_formula2()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$sarana=$this->input->post('sarana');
		$form=array('id_sarana'	=> $sarana);
		$this->Transaksi_model->edit_form('id',$id,$form,'sarana_formula');
		$data=$this->Transaksi_model->formula_sarana2($id)->result();
		echo json_encode($data);

	}

	function delete_formula($id)
	{
		$this->cek_login();
		$id_menu12=12;//formula
		$auth_menu12=$this->checkaut_menu($id_menu12);
		$data['auth_menu12']=$auth_menu12;
		if($auth_menu12->D==1)
		{
			$arr=explode('-',$id);
			$id_formula=$arr[0];
			$id_item=$arr[1];
			$auth=$this->checkaut_item($id_item);
			if(count($auth)>0)
			{
			$hdr=$this->Transaksi_model->hdr_formula($id_formula)->row();
			$form=array('kegiatan'		=>		'Menghapus Formula '.$hdr->kode.' produk '.$hdr->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_formula($id_formula);
			redirect('resume_produk/'.$id_item);
			}
			else
			{
				redirect('resume_produk/'.$id_item);
			}
			}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_produk($id_item)
	{
		$this->cek_login();
		$id_menu9=9;//formula
		$auth_menu9=$this->checkaut_menu($id_menu9);
		$data['auth_menu9']=$auth_menu9;
		if($auth_menu9->D==1)
		{
			$it=$this->Transaksi_model->resume_item($id_item)->row();
			$form=array('kegiatan'		=>		'Menghapus Item '.$it->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_produk($id_item);
			redirect('list_produk');
		}
		
	}
	function formula_terbaik($id)
	{
		$this->cek_login();
		$id_menu17=17;//formula
		$auth_menu17=$this->checkaut_menu($id_menu17);
		$data['auth_menu17']=$auth_menu17;
		if($auth_menu17->R==1)
		{
			$auth=$this->checkaut_item($id);
			if(count($auth)>0)
			{
				$data['main_view']='v_panelis_terbaik';
				$data['default']['id_item']=$id;
					$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
										  'row_alt_start'  => '<tr class="zebra">',
											'row_alt_end'    => '</tr>'
								);
				$this->table->set_template($tmpl);
				$this->table->set_empty("&nbsp;");
				$hdr=$this->Transaksi_model->resume_item($id)->row();
				$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk;

				$this->table->set_heading('Var','Subvar','Formula');
				$fl=$this->Transaksi_model->list_formula($id)->result();
				if(count($fl)>0)
				{
					$options[]='-';
					foreach($fl as $fl)
					{
					$options[$fl->id]=$fl->kode;
					}
				}
				else
				{
					$options='';
				}
				$list=$this->Transaksi_model->penilaian_all($id)->result();
				$k=0;
				foreach($list as $list)
				{
					$k++;			
					$js='class="nilai" style="width: 100%"';
					$id_item=form_hidden('id_item'.$k,$list->id_item);
					$id_penilaian=form_hidden('id'.$k,$list->id);
					$nilai=form_dropdown('nilai'.$k,$options,'',$js);
					$this->table->add_row($list->varr,$list->subvar,$nilai.$id_penilaian);
				} 
				$data['table'] = $this->table->generate();
				$data['table2']=$this->Transaksi_model->rekap_formula_terbaik($id)->result();
				$data['form']=site_url('mkt/add_formula_terbaik');		
				$data['form4']=site_url('resume_produk/'.$hdr->id);
				$data['default']['k']=$k;
				$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "access deny";
		}
	}
	function add_formula_terbaik()
	{
		$this->cek_login();
		$id_menu17=17;//formula
		$auth_menu17=$this->checkaut_menu($id_menu17);
		$data['auth_menu17']=$auth_menu17;
		if($auth_menu17->C==1)
		{
			$hdr=$this->Transaksi_model->resume_item($this->input->post('id_item'))->row();
			$form=array('id_item' => $this->input->post('id_item'),
			'tanggal'=> date("Y-m-d",strtotime($this->input->post('tgl'))));
			$this->Transaksi_model->add_form($form,'formula_terbaik_hdr');
			$tgl=date("Y-m-d",strtotime($this->input->post('tgl')));
			$id_item=$this->input->post('id_item');
			$dt=$this->Transaksi_model->formula_terbaik_hdr($tgl,$id_item)->row();
			
			$k=$this->input->post('k');
			for($i=1;$i<=$k;$i++)
			{
				$form2=array('id_hdr'=>$dt->id,
							'id_penilaian'=>$this->input->post('id'.$i),
							'id_formula'=>$this->input->post('nilai'.$i),
							);
				$this->Transaksi_model->add_form($form2,'formula_terbaik_dtl');
			}
			$hdr=$this->Transaksi_model->resume_item($this->input->post('id_item'))->row();
			$form=array('kegiatan'		=>	'Menambahkan transaksi formula terbaik produk'.$hdr->nama_item.' tanggal '.date("Y-m-d",strtotime($this->input->post('tgl')))
						,'pic'			=>	$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>	date("Y-m-d H:i:s")
						,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('mkt/formula_terbaik/'.$id_item);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_formula_terbaik($id)
	{
		$this->cek_login();
		$id_menu17=17;//formula
		$auth_menu17=$this->checkaut_menu($id_menu17);
		$data['auth_menu17']=$auth_menu17;
		if($auth_menu17->D==1)
		{
			$arr=explode("-",$id);
			$id_hdr=$arr[0];
			$id_item=$arr[1];
			$this->Transaksi_model->delete_form2($arr[0],'formula_terbaik_dtl','id_hdr');
			$this->Transaksi_model->delete_form2($arr[0],'formula_terbaik_hdr','id');
			$hdr=$this->Transaksi_model->resume_item($id_item)->row();
			$form=array('kegiatan'		=>		'Menghapus transaksi formula terbaik produk '.$hdr->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('mkt/formula_terbaik/'.$id_item);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_formula_terbaik_form($id)
	{
		$this->cek_login();
		$id_menu17=17;//formula
		$auth_menu17=$this->checkaut_menu($id_menu17);
		$data['auth_menu17']=$auth_menu17;
		if($auth_menu17->U==1)
		{
			$data['main_view']='v_panelis_terbaik';
			
				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			
			$a=$this->Transaksi_model->dtl_formula_terbaik($id)->row();
			$hdr=$this->Transaksi_model->resume_item($a->id_item)->row();
		
					$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk;

			$this->table->set_heading('Var','Subvar','Formula');
			$fl=$this->Transaksi_model->list_formula($a->id_item)->result();
			
			if(count($fl)>0)
			{
				$options[]='-';
				foreach($fl as $fl)
				{
				$options[$fl->id]=$fl->kode;
				}
			}
			else
			{
				$options='';
			}
			$list=$this->Transaksi_model->dtl_formula_terbaik($id)->result();
			$k=0;
			foreach($list as $list)
			{
				$k++;			
				$js='class="nilai" style="width: 100%"';
				$id_hdr=form_hidden('id_hdr'.$k,$id);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_=form_hidden('id_'.$k,$list->id_penilaian);
				$nilai=form_dropdown('nilai'.$k,$options,$list->id_formula,$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai.$id_penilaian.$id_.$id_hdr);
			}
			$data['default']['id_item']=$a->id_item;
			$data['default']['tgl']=date("d-m-Y",strtotime($a->tanggal));
			$data['table'] = $this->table->generate();
			$data['table2']=$this->Transaksi_model->rekap_formula_terbaik($a->id_item)->result();
			$data['form']=site_url('mkt/edit_formula_terbaik');		
			$data['form4']=site_url('resume_produk/'.$hdr->id);
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_formula_terbaik()
	{
		$this->cek_login();
		$id_menu17=17;//formula
		$auth_menu17=$this->checkaut_menu($id_menu17);
		$data['auth_menu17']=$auth_menu17;
		if($auth_menu17->U==1)
		{
			$form=array(
			'tanggal'=> date("Y-m-d",strtotime($this->input->post('tgl'))));
			$id_=array(
						'id'			=>		$this->input->post('id_hdr1'),
						);
			
			$this->Transaksi_model->edit_form2($id_,$form,'formula_terbaik_hdr');
			$tgl=date("Y-m-d",strtotime($this->input->post('tgl')));
			$id_item=$this->input->post('id_item');

			$k=$this->input->post('k');
			for($i=1;$i<=$k;$i++)
			{
				if($this->input->post('id_'.$i)!="")
				{
				$form2=array(
							'id_formula'=>$this->input->post('nilai'.$i),
							);
				$id_=array(
						'id_penilaian'		=>		$this->input->post('id'.$i),
						'id_hdr'			=>		$this->input->post('id_hdr'.$k)
						);
			
				$this->Transaksi_model->edit_form2($id_,$form2,'formula_terbaik_dtl');
				}
				else
				{
					$form2=array('id_hdr'=>$this->input->post('id_hdr'.$k),
							'id_penilaian'=>$this->input->post('id'.$i),
							'id_formula'=>$this->input->post('nilai'.$i),
							);
					
				
					$this->Transaksi_model->add_form($form2,'formula_terbaik_dtl');
				}
				//$this->Transaksi_model->add_form($form2,'formula_terbaik_dtl');
			}
			$hdr=$this->Transaksi_model->resume_item($id_item)->row();
			$form=array('kegiatan'		=>	'Mengubah transaksi formula terbaik produk '.$hdr->nama_item
						,'pic'			=>	$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>	date("Y-m-d H:i:s")
						,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('mkt/formula_terbaik/'.$id_item);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function pending($id)
	{
		$this->cek_login();
		$id_menu18=18;//formula
		$auth_menu18=$this->checkaut_menu($id_menu18);
		$data['auth_menu18']=$auth_menu18;
		$auth=$this->checkaut_item($id);
		if($auth_menu18->R==1)
		{
			if(count($auth)>0)
			{

				$data['main_view']='v_pending';
				$data['default']['id_item']=$id;
				$hdr=$this->Transaksi_model->resume_item($id)->row();
				$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk;				
				$data['table2']=$this->Transaksi_model->pending_item($id)->result();
				$data['form']=site_url('mkt/add_pending');		
				$data['form4']=site_url('resume_produk/'.$hdr->id);
				$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "access deny";
		}
		
	}
	function add_pending()
	{
		$this->cek_login();
		$id_menu18=18;
		$auth_menu18=$this->checkaut_menu($id_menu18);
		$data['auth_menu18']=$auth_menu18;
		if($auth_menu18->C==1)
		{
			$form=array(	'id_produk'			=>		$this->input->post('id_item')
								,'tgl_awal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_akhir'			=>		date('Y-m-d',strtotime($this->input->post('tgl2')))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'))))
								,'pending_by'		=>		str_replace("'","''",$this->input->post('pending_by'))
								,'approved_by'		=>		str_replace("'","''",$this->input->post('approved_by'))
							);
				
			$this->Transaksi_model->add_form($form,'pending');
			$hdr=$this->Transaksi_model->resume_item($this->input->post('id_item'))->row();
			$form=array('kegiatan'		=>	'Menambahkan transaksi pending produk '.$hdr->nama_item
						,'pic'			=>	$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>	date("Y-m-d H:i:s")
						,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('mkt/pending/'.$this->input->post('id_item')); 
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_pending_form($id)
	{
		$this->cek_login();
		$id_menu18=18;
		$auth_menu18=$this->checkaut_menu($id_menu18);
		$data['auth_menu18']=$auth_menu18;
		$auth=$this->checkaut_item($id);
		if($auth_menu18->U==1)
		{
			$data['main_view']='v_pending';
			$dt=$this->Transaksi_model->pending_by_id($id)->row();
			$data['default']['id_item']=$dt->id_produk;
			$hdr=$this->Transaksi_model->resume_item($dt->id_produk)->row();
			$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk;
			$data['default']['tgl']=date('d-m-Y',strtotime($dt->tgl_awal));
			$data['default']['tgl2']=date('d-m-Y',strtotime($dt->tgl_akhir));
			$data['default']['keterangan']=$dt->keterangan;
			$data['default']['pending_by']=$dt->pending_by;
			$data['default']['approved_by']=$dt->approved_by;		$data['table2']=$this->Transaksi_model->pending_item($id)->result();
			$data['form']=site_url('mkt/edit_pending/'.$id);		
			$data['form4']=site_url('resume_produk/'.$hdr->id);
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_pending($id)
	{
		
		$this->cek_login();
		$id_menu18=18;
		$auth_menu18=$this->checkaut_menu($id_menu18);
		$data['auth_menu18']=$auth_menu18;
		$auth=$this->checkaut_item($id);
		if($auth_menu18->U==1)
		{
			$form=array(	'id_produk'			=>		$this->input->post('id_item')
								,'tgl_awal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_akhir'			=>		date('Y-m-d',strtotime($this->input->post('tgl2')))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'))))
								,'pending_by'		=>		str_replace("'","''",$this->input->post('pending_by'))
								,'approved_by'		=>		str_replace("'","''",$this->input->post('approved_by'))
							);
				
			$this->Transaksi_model->edit_form('id',$id,$form,'pending');
			$hdr=$this->Transaksi_model->resume_item($this->input->post('id_item'))->row();
			$form=array('kegiatan'		=>	'Mengubah transaksi pending produk'.$hdr->nama_item
						,'pic'			=>	$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>	date("Y-m-d H:i:s")
						,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('mkt/pending/'.$this->input->post('id_item')); 
		}
		else
		{
			echo "Access Deny";
			
		}
	}
	function delete_pending($id)
	{
		$this->cek_login();
		$id_menu18=18;
		$auth_menu18=$this->checkaut_menu($id_menu18);
		$data['auth_menu18']=$auth_menu18;
		$auth=$this->checkaut_item($id);
		if($auth_menu18->D==1)
		{
			$arr=explode('-',$id);
			//$hdr=$this->Transaksi_model->pending_by_id($arr[0])->row();
			$hdr=$this->Transaksi_model->resume_item($arr[1])->row();

			$form=array('kegiatan'			=>		'Hapus Transaksi Pending Produk'.$hdr->nama_item
						,'pic'				=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'				=>		date("Y-m-d H:i:s")
						,'realname' 		=>		$this->session->userdata('realname_seas')//ganti
					);
				$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_form2($arr[0],'pending','id');
			redirect('mkt/pending/'.$arr[1]);
		}
		else
		{
			echo "Access Deny";
		}
			
			
	}
	function panelis_form($id)
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		$auth=$this->checkaut_item($id);
		if($auth_menu13->R==1)
		{
			$data['main_view']='v_panelis1';
			$data['default']['id_formula']=$id;
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
			$data['masalah']=$this->Master_model->get_masalah()->result();

				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$hdr=$this->Transaksi_model->hdr_formula($id)->row();
			if($hdr->approve1==1)
			{
				$status='Approve';
			}
			else if($hdr->approve1==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
					$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status.' karena '.$hdr->keterangan1;

			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar');
			$list=$this->Transaksi_model->list_nilai($id)->result();
			$k=0;
			foreach($list as $list)
			{
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_formula=form_hidden('id_formula'.$k,$list->id_formula);
				$nilai=form_input('nilai'.$k,isset($default['nilai'])?$default['nilai']:"",$js2);
				$skala=form_input('skala'.$k,isset($default['skala'])?$default['skala']:"",$js2);
				$keterangan=form_textarea('keterangan'.$k,isset($default['keterangan'])?$default['keterangan']:"",$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_formula);
			} 
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id,1)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				if($auth_menu13->U==1)
				{
					$action.=anchor('edit_panelis_risetman/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu13->R==1)
				{
					$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$list2->id_penilai.')'));
				}
				if($auth_menu13->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_panelis/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}					
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			$num3=$this->Transaksi_model->kesimpulan($id,1)->num_rows();
			if($num3>0)
			{
				$data['note3']=$num3;
				$data['list3']=$this->Transaksi_model->kesimpulan($id,1)->row();
				$data['masalah3']=$this->Transaksi_model->get_penilaian_masalah($id,1)->result();
				
			}
		
			
			$data['form']=site_url('mkt/add_penilaian_panelis');		
			$data['form2']=site_url('mkt/ubah_status/approve1_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve1_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/add_kesimpulan_risetman');
			$data['form6']=site_url('mkt/edit_kesimpulan_risetman_form/'.$id);
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_penilaian_panelis()
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->C==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve1',$this->input->post('id_formula1'))->row();
			if(empty($cek->status))
			{
				$form=array(	'panelis'			=>		$this->input->post('panelis')
								,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
								,'ke'				=>		1
								,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('saran'))))
								,'id_formula'		=>		$this->input->post('id_formula1')
							);
				
				$this->Transaksi_model->add_form($form,'penilaian_hdr');
				$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula1'))->row();
				$form=array('kegiatan'		=>		'Panelis Risetman Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$this->input->post('panelis')
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				$dt=$this->Transaksi_model->penilaian_hdr($this->input->post('panelis'),date('Y-m-d',strtotime($this->input->post('tgl'))),$this->input->post('id_formula1'),1)->row();
				$form=array(	'keterangan'		=>		'Panelis Risetman oleh '.$this->input->post('panelis')
								,'id_formula'		=>		$this->input->post('id_formula1')
								,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_input'		=>		date('Y-m-d')
								);
				$this->Transaksi_model->add_form($form,'stage_formula');
				$k=$this->input->post('k');
				echo $dt->id_penilai;
				for($i=1;$i<=$k;$i++)
				{
					$form=array('id_hdr'		=>		$dt->id_penilai
								,'id_penilaian'		=>		$this->input->post('id'.$i)
								,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
								,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
								);
					$this->Transaksi_model->add_form($form,'penilaian_dtl'); 
					
				}
			}
			redirect('panelis_risetman/'.$this->input->post('id_formula1')); 
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_penilaian_panelis($id)
	{		
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->D==1)
		{
			$arr=explode('-',$id);
			$cek=$this->Transaksi_model->cek_status('Approve1',$arr[1])->row();
			if(empty($cek->status))
			{
				$hdr=$this->Transaksi_model->hdr_penilaian2($arr[0])->row();
				$form=array('kegiatan'		=>	'Hapus Panelis Risetman Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$hdr->panelis
							,'pic'			=>	$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>	date("Y-m-d H:i:s")
							,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_form2($arr[0],'penilaian_dtl','id_hdr');
			$this->Transaksi_model->delete_form2($arr[0],'penilaian_hdr','id_penilai');
			}
			redirect('panelis_risetman/'.$arr[1]);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_panelis_form($id)
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->U==1)
		{
			$arr=explode('-',$id);
			$id_hdr=$arr[0];
			$id_formula=$arr[1];
			$data['main_view']='v_panelis1';
			$hdr=$this->Transaksi_model->hdr_penilaian2($id_hdr)->row();
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']=$hdr->panelis;
			$data['masalah']=$this->Master_model->get_masalah()->result();
			$data['default']['tgl']=date('d-m-Y',strtotime($hdr->tanggal));
			$data['default']['tgl_real']=date('d-m-Y',strtotime($hdr->tgl_real));
			$data['default']['saran']=$hdr->action_plan;
				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			if($hdr->approve1==1)
			{
				$status='Approve';
			}
			else if($hdr->approve1==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
				$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status.' karena '.$hdr->keterangan1;
				$data['default']['id_formula']=$hdr->id_formula;
			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar');
			$list=$this->Transaksi_model->dtl_penilaian2($id_hdr)->result();
			$k=0;
			foreach($list as $list)
			{
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id_penilaian);
				$id_=form_hidden('id_'.$k,$list->id);
				$id_form=form_hidden('id_formula'.$k,$id_formula);
				$nilai=form_input('nilai'.$k,round($list->nilai,1),$js2);
				$skala=form_input('skala'.$k,round($list->skala,1),$js2);
				$keterangan=form_textarea('keterangan'.$k,$list->keterangan,$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_.''.$id_form);
			} 		
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id_formula,1)->result();
			
			foreach($list2 as $list2)
			{
				/* $action=anchor('edit_panelis_risetman/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'))
					.' '.anchor('mkt/delete_penilaian_panelis/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				 */
				$action='';
				$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$list2->id_penilai.')'));
				if($auth_menu13->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_panelis/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				}
				
				
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/edit_penilaian_panelis/'.$id_hdr);		
			$data['form2']=site_url('mkt/ubah_status/approve2_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve2_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/add_kesimpulan_risetman');
			$data['form6']=site_url('mkt/edit_kesimpulan_risetman_form/'.$id);
			
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_penilaian_panelis($id)
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->U==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve1',$this->input->post('id_formula1'))->row();
			if(empty($cek->status))
			{
				$form=array(	'panelis'			=>		$this->input->post('panelis')
								,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
								,'ke'				=>		1
								,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('saran'))))
								,'id_formula'		=>		$this->input->post('id_formula1')
								);
				$this->Transaksi_model->edit_form('id_penilai',$id,$form,'penilaian_hdr');
				
				$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula1'))->row();
				$form=array('kegiatan'		=>	'Edit Panelis Risetman Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$this->input->post('panelis')
							,'pic'			=>	$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>	date("Y-m-d H:i:s")
							,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				$k=$this->input->post('k');
				echo $k;
				for($i=1;$i<=$k;$i++)
				{
					$id_dtl=$this->input->post('id'.$i);
					if($id_dtl!='')
					{
						
						$form=array(
									'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
									,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
									,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
									);
						$id_=array(
									'id_penilaian'		=>		$this->input->post('id'.$i),
									'id_hdr'			=>		$id
									);
						
						$this->Transaksi_model->edit_form2($id_,$form,'penilaian_dtl');
							
					}
					else
					{
						$form=array('id_hdr'		=>		$id
								,'id_penilaian'		=>		$this->input->post('id_'.$i)
								,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
								,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
								);
						$this->Transaksi_model->add_form($form,'penilaian_dtl');
					}
					
				}
			}
			redirect('panelis_risetman/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function add_kesimpulan_risetman()
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->C==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve1',$this->input->post('id_formula'))->row();
			if(empty($cek->status))
			{
				$num3=$this->Transaksi_model->kesimpulan($this->input->post('id_formula'),1)->num_rows();
				if($num3==0)
				{
				//untuk sumber masalah
				$ro=$this->Master_model->get_masalah()->result();
				foreach($ro as $ro)
				{
					if($this->input->post('masalah'.$ro->id_masalah)!=''){
						$form=array('id_hdr'			=>		$this->input->post('id_formula')
									,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
									,'ke'				=>		1
									);
						$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
					}
				}
					$form2=array('id_formula'			=>		$this->input->post('id_formula')
								,'kesimpulan'			=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('kesimpulan')))))
								,'action_plan'			=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('action_plan')))))
								,'deskripsi'			=>		str_replace("'","''",rtrim($this->input->post('deskripsi')))
								,'ke'					=>		1
									);
						$this->Transaksi_model->add_form($form2,'kesimpulan_internal'); 
						$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula'))->row();
						$form=array('kegiatan'		=>	'Menambah kesimpulan risetman formula '.$hdr->kode.' produk '.$hdr->nama_item
									,'pic'			=>	$this->session->userdata('nama_seas')//ganti
									,'tgl'			=>	date("Y-m-d H:i:s")
									,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
									);
						$this->Transaksi_model->add_form($form,'log_act');
				}
			}
			redirect('panelis_risetman/'.$this->input->post('id_formula'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_kesimpulan_risetman_form($id)
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->U==1)
		{
			$id_formula=$id;
			$data['main_view']='v_panelis1';
			$hdr=$this->Transaksi_model->hdr_formula($id_formula)->row();
			if($hdr->approve1==1)
			{
				$status='Approve';
			}
			else if($hdr->approve1==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['default']['id_formula']=$id_formula;
			$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
			$data['masalah']=$this->Master_model->get_masalah()->result();
						$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			
			$this->table->set_heading('Parameter','Nilai','Komentar');
			$list=$this->Transaksi_model->list_nilai($id)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_formula=form_hidden('id_formula'.$k,$list->id_formula);
				$nilai=form_input('nilai'.$k,isset($default['nilai'])?$default['nilai']:"");
				$keterangan=form_textarea('keterangan'.$k,isset($default['keterangan'])?$default['keterangan']:"");
				$this->table->add_row($list->subvar,$nilai,$keterangan.''.$id_penilaian.''.$id_formula);
			} 
			$data['table'] = $this->table->generate();
			$data['default']['k'] = $k;
			$ro=$this->Transaksi_model->get_penilaian_masalah($id,1)->result();
			foreach($ro as $ro)
			{
				$data['default']['masalah'.$ro->id_masalah]='checked';
			}
			$ro2=$this->Transaksi_model->get_kesimpulan($id,1)->row();
			$data['default']['kesimpulan']=$ro2->kesimpulan;
			$data['default']['action_plan']=$ro2->action_plan;
			$data['default']['deskripsi']=$ro2->deskripsi;
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id,1)->result();
			
			foreach($list2 as $list2)
			{
				/* $action=anchor('edit_panelis_risetman/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'))
					.' '.anchor('mkt/delete_penilaian_panelis/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				 */
				$action=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$list2->id_penilai.')'))
					.' '.anchor('mkt/delete_penilaian_panelis/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				 
				$this->table->add_row($list2->panelis,$list2->tanggal,$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/add_penilaian_panelis');		
			$data['form2']=site_url('mkt/ubah_status/approve2_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve2_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/edit_kesimpulan_risetman/'.$id);
			$data['form6']=site_url('mkt/edit_kesimpulan_risetman_form/'.$id);
			
			//$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_kesimpulan_risetman($id)
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->U==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve1',$id)->row();
			if(empty($cek->status))
			{
				
				
				$this->Transaksi_model->delete_masalah($id,1);
				$ro=$this->Master_model->get_masalah()->result();
					foreach($ro as $ro)
					{
						if($this->input->post('masalah'.$ro->id_masalah)!=''){
							$form=array('id_hdr'			=>		$id
										,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
										,'ke'		=>		1
										);
							$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
						}
					}
				$form=array(	'kesimpulan'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('kesimpulan')))))
								,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('action_plan')))))
								,'deskripsi'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('deskripsi')))))
								);
				$id2=array(	'ke'				=>		1
								,'id_formula'		=>		$id
								);							
				$this->Transaksi_model->edit_form2($id2,$form,'kesimpulan_internal');
				$hdr=$this->Transaksi_model->hdr_formula($id)->row();
				$form=array('kegiatan'		=>		'Mengedit kesimpulan risetman formula '.$hdr->kode.' produk '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			redirect('panelis_risetman/'.$id);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	
	function panelis2_form($id)
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->R==1)
		{
			$data['main_view']='v_panelis2';
			$data['default']['id_formula']=$id;
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
			$data['masalah']=$this->Master_model->get_masalah()->result();

				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$hdr=$this->Transaksi_model->hdr_formula($id)->row();
			if($hdr->approve2==1)
			{
				$status='Approve';
			}
			else if($hdr->approve2==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status.' karena '.$hdr->keterangan2;
			$data['status']=$status;
			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar');
			$list=$this->Transaksi_model->list_nilai($id)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_formula=form_hidden('id_formula'.$k,$list->id_formula);
				$nilai=form_input('nilai'.$k,isset($default['nilai'])?$default['nilai']:"",$js2);
				$skala=form_input('skala'.$k,isset($default['skala'])?$default['skala']:"",$js2);
				$keterangan=form_textarea('keterangan'.$k,isset($default['keterangan'])?$default['keterangan']:"",$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_formula);
			} 
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id,2)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				if($auth_menu14->U==1)
				{
					$action.=anchor('edit_panelis_internal/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu14->R==1)
				{
					$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$list2->id_penilai.')'));
				}
				if($auth_menu14->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_panelis2/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				}
				
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			$num3=$this->Transaksi_model->kesimpulan($id,2)->num_rows();
			if($num3>0)
			{
				$data['note3']=$num3;
				$data['list3']=$this->Transaksi_model->kesimpulan($id,2)->row();
				$data['masalah3']=$this->Transaksi_model->get_penilaian_masalah($id,2)->result();
				
			}
		
			
			$data['form']=site_url('mkt/add_penilaian_panelis2');		
			$data['form2']=site_url('mkt/ubah_status/approve2_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve2_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/add_kesimpulan_internal');
			$data['form6']=site_url('mkt/edit_kesimpulan_internal_form/'.$id);
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function add_penilaian_panelis2()
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->C==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve2',$this->input->post('id_formula1'))->row();
			if(empty($cek->status))
			{
				$form=array(	'panelis'			=>		$this->input->post('panelis')
								,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
								,'ke'				=>		2
								,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('saran'))))
								,'id_formula'		=>		$this->input->post('id_formula1')
								);
				$this->Transaksi_model->add_form($form,'penilaian_hdr');
				$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula1'))->row();
				$form=array('kegiatan'		=>		'Panelis Internal Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$this->input->post('panelis')
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
				$this->Transaksi_model->add_form($form,'log_act');
				$dt=$this->Transaksi_model->penilaian_hdr($this->input->post('panelis'),date('Y-m-d',strtotime($this->input->post('tgl'))),$this->input->post('id_formula1'),2)->row();
				$form=array(	'keterangan'		=>		'Panelis Internal oleh '.$this->input->post('panelis')
								,'id_formula'		=>		$this->input->post('id_formula1')
								,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_input'		=>		date('Y-m-d')
								);
				$this->Transaksi_model->add_form($form,'stage_formula');
				$k=$this->input->post('k');
				echo $dt->id_penilai;
				for($i=1;$i<=$k;$i++)
				{
					$form=array('id_hdr'		=>		$dt->id_penilai
								,'id_penilaian'		=>		$this->input->post('id'.$i)
								,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
								,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
								);
					$this->Transaksi_model->add_form($form,'penilaian_dtl'); 
					
				}
			}
			redirect('panelis_internal/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	
	function edit_panelis2_form($id)
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->U==1)
		{
			$arr=explode('-',$id);
			$id_hdr=$arr[0];
			$id_formula=$arr[1];
			$data['main_view']='v_panelis2';
			$hdr=$this->Transaksi_model->hdr_penilaian2($id_hdr)->row();
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']=$hdr->panelis;
			$data['masalah']=$this->Master_model->get_masalah()->result();
			$data['default']['tgl']=date('d-m-Y',strtotime($hdr->tanggal));
			$data['default']['tgl_real']=date('d-m-Y',strtotime($hdr->tgl_real));
			$data['default']['saran']=$hdr->action_plan;
				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			if($hdr->approve2==1)
			{
				$status='Approve';
			}
			else if($hdr->approve2==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['status']=$status;
			$data['default']['id_formula']=$hdr->id_formula;
					$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status.' karena '.$hdr->keterangan2;
	 
			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar');
			$list=$this->Transaksi_model->dtl_penilaian2($id_hdr)->result();
			$k=0;
			foreach($list as $list)
			{
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id_penilaian);
				$id_=form_hidden('id_'.$k,$list->id);
				$id_form=form_hidden('id_formula'.$k,$id_formula);
				$nilai=form_input('nilai'.$k,round($list->nilai,2),$js2);
				$skala=form_input('skala'.$k,round($list->skala,2),$js2);
				$keterangan=form_textarea('keterangan'.$k,$list->keterangan,$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_.''.$id_form);
			} 		
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id_formula,2)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$list2->id_penilai.')'));
				if($auth_menu14->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_panelis2/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	;
				}
				
				
				/* $action=anchor('edit_panelis_internal/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'))
					.' '.anchor('mkt/delete_penilaian_panelis2/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				 */
				 $this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/edit_penilaian_panelis2/'.$id_hdr);		
			$data['form2']=site_url('mkt/ubah_status/approve2_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve2_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/add_kesimpulan_internal');
			$data['form6']=site_url('mkt/edit_kesimpulan_internal_form/'.$id);
			
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_penilaian_panelis2($id)
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->U==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve2',$this->input->post('id_formula1'))->row();
			if(empty($cek->status))
			{
				$form=array(	'panelis'			=>		$this->input->post('panelis')
								,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
								,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
								,'ke'				=>		2
								,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('saran'))))
								,'id_formula'		=>		$this->input->post('id_formula1')
								);
				$this->Transaksi_model->edit_form('id_penilai',$id,$form,'penilaian_hdr');
				$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula1'))->row();
				$form=array('kegiatan'	=>		'Edit Panelis Internal Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$this->input->post('panelis')
							,'pic'		=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'		=>		date("Y-m-d H:i:s")
							,'realname' =>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				$k=$this->input->post('k');
				echo $k;
				for($i=1;$i<=$k;$i++)
				{
					$id_dtl=$this->input->post('id'.$i);
					if($id_dtl!='')
					{
						
						$form=array(
									'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
									,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
									,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
									);
						$id_=array(
									'id_penilaian'		=>		$this->input->post('id'.$i),
									'id_hdr'			=>		$id
									);
						
						$this->Transaksi_model->edit_form2($id_,$form,'penilaian_dtl');
							
					}
					else
					{
						$form=array('id_hdr'		=>		$id
								,'id_penilaian'		=>		$this->input->post('id_'.$i)
								,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
								,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
								);
						$this->Transaksi_model->add_form($form,'penilaian_dtl');
					}
				}
			}
			redirect('panelis_internal/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_penilaian_panelis2($id)
	{		
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->D==1)
		{
			$arr=explode('-',$id);
			$cek=$this->Transaksi_model->cek_status('Approve3',$arr[1])->row();
			if(empty($cek->status))
			{
				$hdr=$this->Transaksi_model->hdr_penilaian2($arr[0])->row();
				$form=array('kegiatan'	=>	'Hapus Panelis Internal Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$hdr->panelis
							,'pic'		=>	$this->session->userdata('nama_seas')//ganti
							,'tgl'		=>	date("Y-m-d H:i:s")
							,'realname' =>	$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_form2($arr[0],'penilaian_dtl','id_hdr');
			$this->Transaksi_model->delete_form2($arr[0],'penilaian_hdr','id_penilai');
			}
			redirect('panelis_internal/'.$arr[1]);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function add_kesimpulan_internal()
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->C==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve2',$this->input->post('id_formula'))->row();
			if(empty($cek->status))
			{
				//untuk sumber masalah
				$ro=$this->Master_model->get_masalah()->result();
				foreach($ro as $ro)
				{
					if($this->input->post('masalah'.$ro->id_masalah)!=''){
						$form=array('id_hdr'			=>		$this->input->post('id_formula')
									,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
									,'ke'				=>		2
									);
						$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
					}
				}
					$form2=array('id_formula'			=>		$this->input->post('id_formula')
								,'kesimpulan'			=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('kesimpulan')))))
								,'action_plan'			=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('action_plan')))))
								,'deskripsi'			=>		str_replace("'","''",rtrim($this->input->post('deskripsi')))
								,'ke'					=>		2
									);
						$this->Transaksi_model->add_form($form2,'kesimpulan_internal'); 
						$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula'))->row();
						$form=array('kegiatan'		=>	'Menambah kesimpulan Internal formula '.$hdr->kode.' produk '.$hdr->nama_item
									,'pic'			=>	$this->session->userdata('nama_seas')//ganti
									,'tgl'			=>	date("Y-m-d H:i:s")
									,'realname' 	=>	$this->session->userdata('realname_seas')//ganti
									);
						$this->Transaksi_model->add_form($form,'log_act');
			}
			redirect('panelis_internal/'.$this->input->post('id_formula'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_kesimpulan_internal_form($id)
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->U==1)
		{
			$id_formula=$id;
			$data['main_view']='v_panelis2';
			$hdr=$this->Transaksi_model->hdr_formula($id_formula)->row();
			if($hdr->approve2==1)
			{
				$status='Approve';
			}
			else if($hdr->approve2==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
			$data['masalah']=$this->Master_model->get_masalah()->result();
						$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			
			$this->table->set_heading('Parameter','Nilai','Komentar');
			$list=$this->Transaksi_model->list_nilai($id)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_formula=form_hidden('id_formula'.$k,$list->id_formula);
				$nilai=form_input('nilai'.$k,isset($default['nilai'])?$default['nilai']:"");
				$keterangan=form_textarea('keterangan'.$k,isset($default['keterangan'])?$default['keterangan']:"");
				$this->table->add_row($list->subvar,$nilai,$keterangan.''.$id_penilaian.''.$id_formula);
			} 
			$data['table'] = $this->table->generate();
			$data['default']['k'] = $k;
			$ro=$this->Transaksi_model->get_penilaian_masalah($id,2)->result();
			foreach($ro as $ro)
			{
				$data['default']['masalah'.$ro->id_masalah]='checked';
			}
			$ro2=$this->Transaksi_model->get_kesimpulan($id,2)->row();
			$data['default']['kesimpulan']=$ro2->kesimpulan;
			$data['default']['action_plan']=$ro2->action_plan;
			$data['default']['deskripsi']=$ro2->deskripsi;
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id,2)->result();
			
			foreach($list2 as $list2)
			{
				$action=anchor('edit_panelis_internal/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'))
					.' '.anchor('mkt/delete_penilaian_panelis2/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				$this->table->add_row($list2->panelis,$list2->tanggal,$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/add_penilaian_panelis2');		
			$data['form2']=site_url('mkt/ubah_status/approve2_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve2_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/edit_kesimpulan_internal/'.$id);
			$data['form6']=site_url('mkt/edit_kesimpulan_internal_form/'.$id);
			$data['status']=$status;
			//$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_kesimpulan_internal($id)
	{
		$this->cek_login();
		$id_menu14=14;
		$auth_menu14=$this->checkaut_menu($id_menu14);
		$data['auth_menu14']=$auth_menu14;
		if($auth_menu14->U==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve2',$id)->row();
			if(empty($cek->status))
			{
				
				
				$this->Transaksi_model->delete_masalah($id,2);
				$ro=$this->Master_model->get_masalah()->result();
					foreach($ro as $ro)
					{
						if($this->input->post('masalah'.$ro->id_masalah)!=''){
							$form=array('id_hdr'			=>		$id
										,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
										,'ke'		=>		2
										);
							$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
						}
					}
				$form=array(	'kesimpulan'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('kesimpulan')))))
								,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('action_plan')))))
								,'deskripsi'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('deskripsi')))))
								);
				$id2=array(	'ke'				=>		2
								,'id_formula'		=>		$id
								);							
				$this->Transaksi_model->edit_form2($id2,$form,'kesimpulan_internal');
				$hdr=$this->Transaksi_model->hdr_formula($id)->row();
				$form=array('kegiatan'		=>		'Mengedit kesimpulan internal formula '.$hdr->kode.' produk '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			redirect('panelis_internal/'.$id);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	
	
	function panelis3_form($id)
	{
		$this->cek_login();
		$id_menu15=15;
		$auth_menu15=$this->checkaut_menu($id_menu15);
		$data['auth_menu15']=$auth_menu15;
		if($auth_menu15->R==1)
		{
			$data['main_view']='v_panelis3';
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']='';
					$data['masalah']=$this->Master_model->get_masalah()->result();

				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$hdr=$this->Transaksi_model->hdr_formula($id)->row();
			if($hdr->approve3==1)
			{
				$status='Approve';
			}
			else if($hdr->approve3==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['status']=$status;
			$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;

			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar+Saran');
			$list=$this->Transaksi_model->list_nilai($id)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id);
				$id_formula=form_hidden('id_formula'.$k,$list->id_formula);
				$nilai=form_input('nilai'.$k,isset($default['nilai'])?$default['nilai']:"",$js2);
				$skala=form_input('skala'.$k,isset($default['skala'])?$default['skala']:"",$js2);
				$keterangan=form_textarea('keterangan'.$k,isset($default['keterangan'])?$default['keterangan']:"",$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_formula);
			} 
			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id,3)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				if($auth_menu15->U==1)
				{
					$action.=anchor('edit_panelis_ts/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu15->R==1)
				{
					$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$list2->id_penilai.')'));
				}
				if($auth_menu15->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_panelis3/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/add_penilaian_panelis3');		
			$data['form2']=site_url('mkt/ubah_status/approve3_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve3_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);

			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function add_penilaian_panelis3()
	{
		$this->cek_login();
		$id_menu15=15;
		$auth_menu15=$this->checkaut_menu($id_menu15);
		$data['auth_menu15']=$auth_menu15;
		if($auth_menu15->C==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve3',$this->input->post('id_formula1'))->row();
			if(empty($cek->status))
			{
			$form=array(	'panelis'			=>		$this->input->post('panelis')
							,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
							,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
							,'ke'				=>		3
							,'kesimpulan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('kesimpulan'))))
							,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('saran'))))
							,'id_formula'		=>		$this->input->post('id_formula1')
							);
			$this->Transaksi_model->add_form($form,'penilaian_hdr');
			$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula1'))->row();
			$form=array('kegiatan'		=>		'Panelis TS Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$this->input->post('panelis')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
					);
			$this->Transaksi_model->add_form($form,'log_act');
			$dt=$this->Transaksi_model->penilaian_hdr($this->input->post('panelis'),date('Y-m-d',strtotime($this->input->post('tgl'))),$this->input->post('id_formula1'),3)->row();
			$form=array(	'keterangan'		=>		'Panelis Taste Specialist oleh '.$this->input->post('panelis')
							,'id_formula'		=>		$this->input->post('id_formula1')
							,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
							,'tgl_input'		=>		date('Y-m-d')
							);
			$this->Transaksi_model->add_form($form,'stage_formula');
			$k=$this->input->post('k');
			echo $k;
			for($i=1;$i<=$k;$i++)
			{
				$form=array('id_hdr'		=>		$dt->id_penilai
							,'id_penilaian'		=>		$this->input->post('id'.$i)
							,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
							,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
							,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
							);
				$this->Transaksi_model->add_form($form,'penilaian_dtl'); 
				
			}
			$ro=$this->Master_model->get_masalah()->result();
				foreach($ro as $ro)
				{
					if($this->input->post('masalah'.$ro->id_masalah)!=''){
						$form=array('id_hdr'			=>		$dt->id_penilai
									,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
									);
						$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
					}
				}
			}
			redirect('panelis_ts/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	
	function edit_panelis3_form($id)
	{
		$this->cek_login();
		$id_menu15=15;
		$auth_menu15=$this->checkaut_menu($id_menu15);
		$data['auth_menu15']=$auth_menu15;
		if($auth_menu15->U==1)
		{
			$arr=explode('-',$id);
			$id_hdr=$arr[0];
			$id_formula=$arr[1];
			$data['main_view']='v_panelis3';
			$hdr=$this->Transaksi_model->hdr_penilaian2($id_hdr)->row();
			$data['panelis']=$this->Master_model->get_panelis()->result();
			$data['panelis_selected']=$hdr->panelis;
			$data['masalah']=$this->Master_model->get_masalah()->result();

			$data['default']['tgl']=date('d-m-Y',strtotime($hdr->tanggal));
			$data['default']['tgl_real']=date('d-m-Y',strtotime($hdr->tgl_real));
			$data['default']['kesimpulan']=$hdr->kesimpulan;
			$data['default']['saran']=$hdr->action_plan;
				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			if($hdr->approve3==1)
			{
				$status='Approve';
			}
			else if($hdr->approve3==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['status']=$status;
			$data['default']['id_formula']=$hdr->id_formula;
					$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;
	 
			$this->table->set_heading('Var','Subvar','Nilai','Skala','Komentar+Saran');
			$list=$this->Transaksi_model->dtl_penilaian2($id_hdr)->result();
			$k=0;
			foreach($list as $list){
				$k++;
				$js='onKeyUp=check_length("keterangan'.$k.'");';
				$js2="onkeypress='return harusAngka(event)'";
				$id_item=form_hidden('id_item'.$k,$list->id_item);
				$id_penilaian=form_hidden('id'.$k,$list->id_penilaian);
				$id_=form_hidden('id_'.$k,$list->id);
				
				$id_form=form_hidden('id_formula'.$k,$id_formula);
				$nilai=form_input('nilai'.$k,round($list->nilai,2),$js2);
				$skala=form_input('skala'.$k,round($list->skala,2),$js2);
				$keterangan=form_textarea('keterangan'.$k,$list->keterangan,$js);
				$this->table->add_row($list->varr,$list->subvar,$nilai,$skala,$keterangan.''.$id_penilaian.''.$id_.''.$id_form);
			} 
			$data['table'] = $this->table->generate();
			$ro=$this->Transaksi_model->get_penilaian_masalah($id_hdr,3)->result();
			foreach($ro as $ro)
			{
				$data['default']['masalah'.$ro->id_masalah]='checked';
			}
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Tanggal Real','Action');
			$list2=$this->Transaksi_model->rekap_panelis($id_formula,3)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				$action.=anchor('edit_panelis_ts/'.$list2->id_penilai.'-'.$list2->id_formula,"Ubah",array('class' => 'btn btn-success'));
				if($auth_menu15->D==1)
				{
					$action.=anchor('mkt/delete_penilaian_panelis3/'.$list2->id_penilai.'-'.$list2->id_formula,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	;
				}
				
				$this->table->add_row($list2->panelis,date('d-m-Y',strtotime($list2->tanggal)),date('d-m-Y',strtotime($list2->tgl_real)),$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/edit_penilaian_panelis3/'.$id_hdr);		
			$data['form2']=site_url('mkt/ubah_status/approve3_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve3_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);

			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_penilaian_panelis3($id)
	{
		$this->cek_login();
		$id_menu15=15;
		$auth_menu15=$this->checkaut_menu($id_menu15);
		$data['auth_menu15']=$auth_menu15;
		if($auth_menu15->U==1)
		{
			$cek=$this->Transaksi_model->cek_status('Approve3',$this->input->post('id_formula1'))->row();
			if(empty($cek->status))
			{
			$form=array(	'panelis'			=>		$this->input->post('panelis')
							,'tanggal'			=>		date('Y-m-d',strtotime($this->input->post('tgl')))
							,'tgl_real'			=>		date('Y-m-d',strtotime($this->input->post('tgl_real')))
							,'ke'				=>		3
							,'kesimpulan'		=>		ucfirst(strtolower($this->input->post('kesimpulan')))
							,'action_plan'		=>		ucfirst(strtolower($this->input->post('saran')))
							,'id_formula'		=>		$this->input->post('id_formula1')
							);
			$this->Transaksi_model->edit_form('id_penilai',$id,$form,'penilaian_hdr');
			$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula1'))->row();
			$form=array('kegiatan'		=>		'Edit Panelis TS Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$this->input->post('panelis')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$k=$this->input->post('k');
			//echo $k;
			for($i=1;$i<=$k;$i++)
			{
				$id_dtl=$this->input->post('id'.$i);
				if($id_dtl!='')
				{
					
					$form=array(
								'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
								,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
								,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
								);
					$id_=array(
								'id_penilaian'		=>		$this->input->post('id'.$i),
								'id_hdr'			=>		$id
								);
					
					$this->Transaksi_model->edit_form2($id_,$form,'penilaian_dtl');
						
				}
				else
				{
					$form=array('id_hdr'		=>		$id
							,'id_penilaian'		=>		$this->input->post('id_'.$i)
							,'nilai'			=>		str_replace(",",".",$this->input->post('nilai'.$i))
							,'skala'			=>		str_replace(",",".",$this->input->post('skala'.$i))
							,'keterangan'		=>		ucfirst(strtolower(str_replace("'","''",$this->input->post('keterangan'.$i))))
							);
					$this->Transaksi_model->add_form($form,'penilaian_dtl');
				}
			}
			$this->Transaksi_model->delete_form2($id,'penilaian_masalah','id_hdr');
				$ro=$this->Master_model->get_masalah()->result();
					foreach($ro as $ro)
					{
						if($this->input->post('masalah'.$ro->id_masalah)!=''){
							$form=array('id_hdr'			=>		$id
										,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
										);
							$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
						}
					}
			}
			redirect('panelis_ts/'.$this->input->post('id_formula1'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_penilaian_panelis3($id)
	{
		$this->cek_login();
		$id_menu15=15;
		$auth_menu15=$this->checkaut_menu($id_menu15);
		$data['auth_menu15']=$auth_menu15;
		if($auth_menu15->D==1)
		{
			$arr=explode('-',$id);
			$cek=$this->Transaksi_model->cek_status('Approve3',$arr[1])->row();
			if(empty($cek->status))
			{
				$hdr=$this->Transaksi_model->hdr_penilaian2($arr[0])->row();
				$form=array('kegiatan'		=>		'Hapus Panelis TS Formula '.$hdr->kode.' produk '.$hdr->nama_item.' Oleh '.$hdr->panelis
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				$this->Transaksi_model->delete_form2($arr[0],'penilaian_dtl','id_hdr');
				$this->Transaksi_model->delete_form2($arr[0],'penilaian_hdr','id_penilai');
			}
			redirect('panelis_ts/'.$arr[1]);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function kesimpulan_form($id)
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->R==1)
		{
			$data['main_view']='v_kesimpulan';
			$data['panelis_selected']='';
			$data['default']['id_formula']=$id;
			$data['masalah']=$this->Master_model->get_masalah()->result();

			$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$hdr=$this->Transaksi_model->hdr_formula($id)->row();
			if($hdr->approve3==1)
			{
				$status='Approve';
			}
			else if($hdr->approve3==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['status']=$status;
			$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;

			$this->table->set_heading('Var','Kesimpulan','Action Plan');		
				$id_formula=form_hidden('id_formula',$id);
				$nilai1=form_hidden('nilai1','base');
				$js1='onKeyUp=check_length("kesimpulan1");';
				$js2='onKeyUp=check_length("kesimpulan2");';
				$js3='onKeyUp=check_length("kesimpulan3");';
				$js11='onKeyUp=check_length("saran1");';
				$js21='onKeyUp=check_length("saran2");';
				$js31='onKeyUp=check_length("saran3");';
				$keterangan1=form_textarea('kesimpulan1',isset($default['kesimpulan1'])?$default['kesimpulan1']:"",$js1);
				$saran1=form_textarea('saran1',isset($default['saran1'])?$default['saran1']:"",$js11);
				$this->table->add_row('Base'.$nilai1,$keterangan1.''.$id_formula,$saran1);
				$nilai2=form_hidden('nilai2','rasa_aroma');
				$keterangan2=form_textarea('kesimpulan2',isset($default['kesimpulan2'])?$default['kesimpulan2']:"",$js2);
				$saran2=form_textarea('saran2',isset($default['saran2'])?$default['saran2']:"",$js21);
				$this->table->add_row('Rasa & Aroma'.$nilai2,$keterangan2.''.$id_formula,$saran2);
				$nilai3=form_hidden('nilai3','total_rasa');
				$keterangan3=form_textarea('kesimpulan3',isset($default['kesimpulan3'])?$default['kesimpulan3']:"",$js3);
				$saran3=form_textarea('saran3',isset($default['saran3'])?$default['saran3']:"",$js31);
				$this->table->add_row('Total Rasa'.$nilai3,$keterangan3.''.$id_formula,$saran3);

			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Action');
			$list2=$this->Transaksi_model->hdr_kesimpulan_ts($id,3)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				if($auth_menu16->U==1)
				{
					$action.=anchor('edit_kesimpulan/'.$list2->id,"Ubah",array('class' => 'btn btn-success'));
				}
				if($auth_menu16->R==1)
				{
					$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>'get_tabel('.$list2->id.')'));
				}
				if($auth_menu16->D==1)
				{
					$action.=anchor('mkt/delete_kesimpulan/'.$list2->id,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
				}
				$this->table->add_row($list2->panelis,$action);
			} 
			$data['table2'] = $this->table->generate();
			$num3=$this->Transaksi_model->kesimpulan($id,3)->num_rows();
			if($num3>0)
			{
				$data['note3']=$num3;
				$data['list3']=$this->Transaksi_model->kesimpulan($id,3)->row();
				$data['masalah3']=$this->Transaksi_model->get_penilaian_masalah($id,3)->result();
				
			}
			$data['form']=site_url('mkt/add_kesimpulan_panelis3');		
			$data['form2']=site_url('mkt/ubah_status/approve3_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve3_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/add_masalah');
			$data['form6']=site_url('mkt/edit_masalah_form/'.$id);
			//$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function add_kesimpulan_panelis3()
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->C==1)
		{
			$num=$this->Transaksi_model->id_kesimpulan_ts()->num_rows();
			if($num>0)
			{
				$ro=$this->Transaksi_model->id_kesimpulan_ts()->row();
				$id_kesimpulan=$ro->id+1;
				
			}
			else
			{
				$id_kesimpulan=1;
			}
			for($i=1;$i<=3;$i++)
			{
				$form=array(	'panelis'			=>		$this->input->post('panelis')
								,'ke'				=>		3
								,'parameter'		=>		$this->input->post('nilai'.$i)
								,'kesimpulan'		=>		ucfirst(strtolower($this->input->post('kesimpulan'.$i)))
								,'saran'			=>		ucfirst(strtolower($this->input->post('saran'.$i)))
								,'id_formula'		=>		$this->input->post('id_formula')
								,'id'				=>	$id_kesimpulan	
								);
				$this->Transaksi_model->add_form($form,'kesimpulan_ts');
			}
			$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula'))->row();
			$form=array('kegiatan'		=>		'Menambah kesimpulan TS formula '.$hdr->kode.' produk '.$hdr->nama_item.' oleh '.$this->input->post('panelis')
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			redirect('kesimpulan/'.$this->input->post('id_formula'));
		}
		else
		{
			echo "Access Deny";
		}
	}
	function add_masalah()
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->C==1)
		{
			$cek=$this->Transaksi_model->kesimpulan($this->input->post('id_formula'),3)->num_rows();
			if($cek==0)
			{
			$form=array(	'id_formula'		=>		$this->input->post('id_formula')
							,'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('action_plan')))))
							,'deskripsi'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('deskripsi')))))
							,'ke'				=>		3
							);
			$this->Transaksi_model->add_form($form,'kesimpulan_internal');
			$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula'))->row();
			$form=array('kegiatan'		=>		'Menambah Action Plan TS formula '.$hdr->kode.' produk '.$hdr->nama_item
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$ro=$this->Master_model->get_masalah()->result();
				foreach($ro as $ro)
				{
					if($this->input->post('masalah'.$ro->id_masalah)!=''){
						$form=array('id_hdr'			=>		$this->input->post('id_formula')
									,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
									,'ke'				=>		3
									);
						$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
					}
				}
			}
			redirect('kesimpulan/'.$this->input->post('id_formula'));
		}
		else
		{
			echo "Access Deny";
		}

	}
	function edit_kesimpulan_form($id2)
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->U==1)
		{
			$arr=$this->Transaksi_model->kesimpulan_ts($id2)->row();
			$id=$arr->id_formula;
			$id_=$arr->id;
			$panelis=$arr->panelis;
			$ke=$arr->ke;
			$this->cek_login();
			$data['main_view']='v_kesimpulan';
			$data['panelis_selected']=$panelis;
			$data['default']['id_formula']=$id;
			$data['masalah']=$this->Master_model->get_masalah()->result();

			$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$hdr=$this->Transaksi_model->hdr_formula($id)->row();
			if($hdr->approve3==1)
			{
				$status='Approve';
			}
			else if($hdr->approve3==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
			$data['status']=$status;
					$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;

				$this->table->set_heading('Var','Kesimpulan','Saran');
				$r1=$this->Transaksi_model->edit_kesimpulan_ts($id_,'base')->row();
				$id_formula=form_hidden('id_formula',$id);
				$id_kesimpulan=form_hidden('id_kesimpulan',$id_);
				$nilai1=form_hidden('nilai1','base');
				$keterangan1=form_textarea('kesimpulan1',$r1->kesimpulan);
				$saran1=form_textarea('saran1',$r1->saran);
				$this->table->add_row('Base'.$nilai1,$keterangan1.''.$id_formula.''.$id_kesimpulan,$saran1);
				$r2=$this->Transaksi_model->edit_kesimpulan_ts($id_,'rasa_aroma')->row();
				$nilai2=form_hidden('nilai2','rasa_aroma');
				$keterangan2=form_textarea('kesimpulan2',$r2->kesimpulan);
				$saran2=form_textarea('saran2',$r2->saran);
				$this->table->add_row('Rasa & Aroma'.$nilai2,$keterangan2.''.$id_formula.''.$id_kesimpulan,$saran2);
				$r3=$this->Transaksi_model->edit_kesimpulan_ts($id_,'total_rasa')->row();
				$nilai3=form_hidden('nilai3','total_rasa');
				$keterangan3=form_textarea('kesimpulan3',$r3->kesimpulan);
				$saran3=form_textarea('saran3',$r3->saran);
				$this->table->add_row('Total Rasa'.$nilai3,$keterangan3.''.$id_formula.''.$id_kesimpulan,$saran3);

			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Action');
			$list2=$this->Transaksi_model->hdr_kesimpulan_ts($id,3)->result();
			
			foreach($list2 as $list2)
			{
				$action='';
				$action.=anchor('edit_kesimpulan/'.$list2->id.'',"Ubah",array('class' => 'btn btn-success'));
				if($auth_menu16->D==1)
				{
					$action=anchor('mkt/delete_kesimpulan/'.$list2->id_formula.'_'.$list2->panelis.'_3',"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
				}
			
			$this->table->add_row($list2->panelis,$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/edit_kesimpulan_panelis3');		
			$data['form2']=site_url('mkt/ubah_status/approve3_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve3_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/add_masalah');

			//$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	
	function edit_kesimpulan_panelis3()
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->U==1)
		{
			for($i=1;$i<=3;$i++)
			{
				$form=array(	'kesimpulan'		=>		ucfirst(strtolower($this->input->post('kesimpulan'.$i)))
								,'saran'			=>		ucfirst(strtolower($this->input->post('saran'.$i)))
								,'panelis'			=>		$this->input->post('panelis')
								,'ke'				=>		3
								,'parameter'		=>		$this->input->post('nilai'.$i)
								,'id_formula'		=>		$this->input->post('id_formula')
								);
				$id=array('parameter'		=>		$this->input->post('nilai'.$i)
								,'id'		=>		$this->input->post('id_kesimpulan')
								);
				$this->Transaksi_model->edit_form2($id,$form,'kesimpulan_ts');
				
			}
		
				$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula'))->row();
				$form=array('kegiatan'		=>		'Mengubah kesimpulan TS formula '.$hdr->kode.' produk '.$hdr->nama_item.' oleh '.$this->input->post('panelis')
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			redirect('kesimpulan/'.$this->input->post('id_formula'));
			
		}
		else
		{
			echo "Access Deny";
		}
	}
	function delete_kesimpulan($id2)
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->D==1)
		{
			$arr=$this->Transaksi_model->kesimpulan_ts($id2)->row();
			$id_formula=$arr->id_formula;
			$hdr=$this->Transaksi_model->hdr_formula($id_formula)->row();
			$form=array('kegiatan'		=>		'Menghapus kesimpulan TS formula '.$hdr->kode.' produk '.$hdr->nama_item.' oleh '.$arr->panelis
						,'pic'			=>		$this->session->userdata('nama_seas')//ganti
						,'tgl'			=>		date("Y-m-d H:i:s")
						,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
						);
			$this->Transaksi_model->add_form($form,'log_act');
			$this->Transaksi_model->delete_kesimpulan_ts($id2);
			redirect('kesimpulan/'.$id_formula);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_masalah_form($id)
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->U==1)
		{
			$data['main_view']='v_kesimpulan';
			$data['panelis_selected']='';
			$data['default']['id_formula']=$id;
			$data['masalah']=$this->Master_model->get_masalah()->result();
			$ro=$this->Transaksi_model->get_penilaian_masalah($id,3)->result();
			foreach($ro as $ro)
			{
				$data['default']['masalah'.$ro->id_masalah]='checked';
			}
			$ro2=$this->Transaksi_model->get_kesimpulan($id,3)->row();
			$data['default']['kesimpulan']=$ro2->kesimpulan;
			$data['default']['action_plan']=$ro2->action_plan;
			$data['default']['deskripsi']=$ro2->deskripsi;
			$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl);
			$this->table->set_empty("&nbsp;");
			$hdr=$this->Transaksi_model->hdr_formula($id)->row();
			if($hdr->approve3==1)
			{
				$status='Approve';
			}
			else if($hdr->approve3==-1)
			{
				$status='Drop';
			}
			else
			{
				$status='-';
			}
					$data['default']['judul']=' Produk '.$hdr->nama_item.', line produk '.$hdr->lineproduk.', Seri formula '.$hdr->kode.' , Status '.$status;

			$this->table->set_heading('Parameter','Kesimpulan','Saran');
				$id_formula=form_hidden('id_formula',$id);
				$nilai1=form_hidden('nilai1','base');
				$keterangan1=form_textarea('kesimpulan1','');
				$saran1=form_textarea('saran1','');
				$this->table->add_row('Base'.$nilai1,$keterangan1.''.$id_formula,$saran1);
				$nilai2=form_hidden('nilai2','rasa_aroma');
				$keterangan2=form_textarea('kesimpulan2','');
				$saran2=form_textarea('saran2','');
				$this->table->add_row('Rasa & Aroma'.$nilai2,$keterangan2.''.$id_formula,$saran2);
				$nilai3=form_hidden('nilai3','total_rasa');
				$keterangan3=form_textarea('kesimpulan3','');
				$saran3=form_textarea('saran3','');
				$this->table->add_row('Total Rasa'.$nilai3,$keterangan3.''.$id_formula,$saran3);

			$data['table'] = $this->table->generate();
			$tmpl2 = array( 'table_open'    => '<table id="datatable" class="table table-striped table-bordered">',
									  'row_alt_start'  => '<tr class="zebra">',
										'row_alt_end'    => '</tr>'
							);
			$this->table->set_template($tmpl2);
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Nama Panelis','Tanggal','Action');
			$list2=$this->Transaksi_model->hdr_kesimpulan_ts($id,3)->result();
			
			foreach($list2 as $list2)
			{
			$action=anchor('edit_kesimpulan/'.$list2->panelis.'_'.$list2->tanggal.'_3',"Ubah",array('class' => 'btn btn-success'))
					.' '.anchor('mkt/delete_kesimpulan/'.$list2->id_formula.'_'.$list2->panelis.'_3',"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));	
			$this->table->add_row($list2->panelis,$list2->tanggal,$action);
			} 
			$data['table2'] = $this->table->generate();
			
			$data['form']=site_url('mkt/add_kesimpulan_panelis3');		
			$data['form2']=site_url('mkt/ubah_status/approve3_1_'.$id);		
			$data['form3']=site_url('mkt/ubah_status/approve3_-1_'.$id);
			$data['form4']=site_url('resume_produk/'.$hdr->id_item);
			$data['form5']=site_url('mkt/edit_masalah');
			$data['status']=$status;
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
		
	}
	
	function edit_masalah()
	{
		$this->cek_login();
		$id_menu16=16;
		$auth_menu16=$this->checkaut_menu($id_menu16);
		$data['auth_menu16']=$auth_menu16;
		if($auth_menu16->U==1)
		{
			$id=$this->input->post('id_formula');
			$cek=$this->Transaksi_model->cek_status('Approve3',$id)->row();
			if(empty($cek->status))
			{
				
				
				$this->Transaksi_model->delete_masalah($id,3);
				$ro=$this->Master_model->get_masalah()->result();
					foreach($ro as $ro)
					{
						if($this->input->post('masalah'.$ro->id_masalah)!=''){
							$form=array('id_hdr'			=>		$id
										,'id_masalah'		=>		$this->input->post('masalah'.$ro->id_masalah)
										,'ke'		=>		3
										);
							$this->Transaksi_model->add_form($form,'penilaian_masalah'); 
						}
					}
				$form=array(	'action_plan'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('action_plan')))))
								,'deskripsi'		=>		ucfirst(strtolower(str_replace("'","''",rtrim($this->input->post('deskripsi')))))
								);
				$id2=array(	'ke'				=>		3
								,'id_formula'		=>		$id
								);							
				$this->Transaksi_model->edit_form2($id2,$form,'kesimpulan_internal');
				$hdr=$this->Transaksi_model->hdr_formula($id)->row();
				$form=array('kegiatan'		=>		'Mengubah Action Plan TS formula '.$hdr->kode.' produk '.$hdr->nama_item
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
			}
			redirect('kesimpulan/'.$id);
		}
		else
		{
			echo "Access Deny";
		}

	}	
	function keterangan()
	{
		$this->cek_login();
		if($this->input->post('keterangan')!='')
		{
			$stage=$this->input->post('stage');
			$approve=$this->input->post('approve');
			$id_formula=$this->input->post('id_formula');
			$ke=$this->input->post('ke');
			$stage=$this->input->post('stage');
			$keterangan_=$this->input->post('keterangan');
			$kolom=$stage;
			$nilai=$approve;
			if($kolom=='approve1')
			{
				$tujuan='panelis_risetman';
				$keterangan='Panelis Risetman';
			}
			else if($kolom=='approve2')
			{
				$tujuan='panelis_internal';
				$keterangan='Panelis Internal';
			}
			else if($kolom=='approve3')
			{
				$tujuan='panelis3_form';
				$keterangan='Panelis Taste Specialist';
			}
			
			$cek=$this->Transaksi_model->get_kesimpulan($id_formula,$ke)->num_rows();
			if($cek>0)
			{
				if($nilai==1)
				{
					$ket2='Approve';
				}
				else if($nilai==-1)
				{
					$ket2='Drop';
				}
				else if($nilai==0)
				{
					$ket2='Unapprove';
				}
				$form=array(	'keterangan'		=>		$keterangan.' '.$ket2.' karena '.$keterangan_
								,'id_formula'		=>		$id_formula
								,'tanggal'			=>		date('Y-m-d')
								,'tgl_input'		=>		date('Y-m-d')
								);
				$this->Transaksi_model->add_form($form,'stage_formula');
				$hdr=$this->Transaksi_model->hdr_formula($id_formula)->row();
				$form=array('kegiatan'		=>		$ket2.' '.$keterangan.' formula '.$hdr->kode.' produk '.$hdr->nama_item.' karena '.$keterangan_
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				$form=array($kolom=>$nilai
							,'keterangan'.$ke 	=>$keterangan_);
				$this->Transaksi_model->edit_form('id',$id_formula,$form,'formula2');
				$ro=$this->Transaksi_model->formula($id_formula)->row();
				$data['id_item']=$ro->id_item;
				echo json_encode($data);
			}
			else
			{	
				$this->session->set_flashdata('message_approve', 'Mohon isi kesimpulan, sumber masalah,dan action plan');
				$data['id_item']=0;
				echo json_encode($data);

			}
		}
	}
	
	function keterangan_kompetitor()
	{
		$this->cek_login();
		if($this->input->post('keterangan')!='')
		{
			$approve=$this->input->post('approve');
			$id_formula=$this->input->post('id_kompetitor');
			$keterangan_=$this->input->post('keterangan');
		
				if($approve==1)
				{
					$ket2='Approve';
				}
				else if($approve==-1)
				{
					$ket2='Drop';
				}
				else if($approve==0)
				{
					$ket2='Unapprove';
				}
				$form=array(	'keterangan'		=>		$ket2.' panelis kompetitor karena '.$keterangan_
								,'id_formula'		=>		$id_formula
								,'tanggal'			=>		date('Y-m-d')
								,'tgl_input'		=>		date('Y-m-d')
								);
				$this->Transaksi_model->add_form($form,'stage_formula');
				$hdr=$this->Transaksi_model->kompetitor($id_formula)->row();
				$form=array('kegiatan'		=>		$ket2.' '.$hdr->nama.' sebagai kompetitor produk '.$hdr->nama_item.' karena '.$keterangan_
							,'pic'			=>		$this->session->userdata('nama_seas')//ganti
							,'tgl'			=>		date("Y-m-d H:i:s")
							,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
							);
				$this->Transaksi_model->add_form($form,'log_act');
				$form=array('status'=>$approve
							,'keterangan' 	=>$keterangan_);
				$this->Transaksi_model->edit_form('id_kompetitor',$id_formula,$form,'kompetitor');
				$ro=$this->Transaksi_model->kompetitor($id_formula)->row();
				$data['id_item']=$ro->id_produk;
				echo json_encode($data);
			
		}
	}
	
	function get_tabel()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$ke=$this->input->post('ke');
		$data=$this->Transaksi_model-> dtl_penilaian3($id,$ke)->result();
		
		echo json_encode($data);
	}
	function get_formula_terbaik()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model-> dtl_formula_terbaik($id)->result();
		
		echo json_encode($data);
	}
	function get_tabel_kompetitor()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$ke=$this->input->post('ke');
		$data=$this->Transaksi_model->dtl_penilaian_kompetitor($id,$ke)->result();
		
		echo json_encode($data);
	}
	function get_tabel2()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model->kesimpulan_ts($id)->result();
		echo json_encode($data);
	}
	function get_tabel_formula()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model->formula_bahan_all($id)->result();
		
		echo json_encode($data);
	}
	function get_hdr_formula()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model->formula($id)->result();
		
		echo json_encode($data);
	}
	function approve_($id_)
	{
		$this->cek_login();
		$id_menu13=13;
		$auth_menu13=$this->checkaut_menu($id_menu13);
		$data['auth_menu13']=$auth_menu13;
		if($auth_menu13->A==1)
		{
			$arr=explode('_',$id_);
			$id_item=$arr[0];
			$auth=$this->checkaut_item($id_item);
			if(count($auth)>0)
			{
			$ke=$arr[1];
			$data['main_view']='v_approve';
			$list=$this->Transaksi_model->resume_item($id_item)->row();
			$data['produk']=$list->nama_item;
			$k=0;
			if($this->input->post('tgl')!='')
			{
				$tmpl = array( 'table_open'    => '<table id="" class="table table-striped table-bordered">',
										  'row_alt_start'  => '<tr class="zebra">',
											'row_alt_end'    => '</tr>'
								);
				$this->table->set_template($tmpl);
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('Seri Formula','Status','Keterangan','Kesimpulan');
				$tgl=date('Y-m-d',strtotime($this->input->post('tgl')));
				$data['default']['tgl']=date('d-m-Y',strtotime($this->input->post('tgl')));
				$num=$this->Transaksi_model->list_formula2($id_item,$tgl,$ke)->num_rows();
				if($num>0)
				{
					$list=$this->Transaksi_model->list_formula2($id_item,$tgl,$ke)->result();			
					foreach($list as $list)
					{
						if($ke==1)
						{
							$stage2='approve1';
							$stagek2='keterangan1';
							$approve=$list->approve1;
							$keterangan=$list->keterangan1;
						}
						else if($ke==2)
						{
							$stage2='approve2';
							$stagek2='keterangan2';
							$approve=$list->approve2;
							$keterangan=$list->keterangan2;
						}
						else if($ke==3)
						{
							$stage2='approve3';
							$stagek2='keterangan3';
							$approve=$list->approve3;
							$keterangan=$list->keterangan3;
						}
						$k++;
						$status='';
						if($auth_menu13->A==1)
						{
							$status.=form_radio(array('name' => 'status'.$k, 'value' => '1', 'checked' => ('1' == $approve) ? TRUE : FALSE, 'id' => 'male')).form_label('Approve', 'Approve')
							.' '.form_radio(array('name' => 'status'.$k, 'value' => '-1', 'checked' => ('-1' == $approve) ? TRUE : FALSE, 'id' => 'female')).form_label('Drop', 'Drop');
						}
						if($auth_menu13->UA==1)
						{
							$status.=form_radio(array('name' => 'status'.$k, 'value' => '0', 'checked' => ('0' == $approve) ? TRUE : FALSE, 'id' => 'female')).form_label('Unapprove', 'Unapprove');;
						}
						
			  
						$id=form_hidden('id_item',  $id_item);
						$id_formula=form_hidden('id_formula'.$k,  $list->id);
						$stage=form_hidden('stage'.$k,  $stage2);
						$stagek=form_hidden('stagek'.$k,  $stagek2);
						$ket=form_input('keterangan'.$k,  $keterangan);
						$cek=$this->Transaksi_model->rekap_panelis($list->id,$ke)->num_rows();
						if($cek>0)
						{
							
						}
						else
						{
							$status=form_hidden('status'.$k,  0);
							$ket=form_hidden('keterangan'.$k,  NULL);
						}
						
						$this->table->add_row($list->kode,$status.''.$id,$ket.''.$id_formula.''.$stage.''.$stagek,$list->kesimpulan);
					} 
					$data['table'] = $this->table->generate();
				}
			}
			$data['form']=site_url('approve/'.$id_item.'_'.$ke);
			$data['form2']=site_url('mkt/update_approve/');
			$data['form3']=site_url('resume_produk/'.$id_item);
			$data['default']['k']=$k;
			$this->load->view('sidemenu',$data);
			}
			else
			{
				echo "access deny";
			}
		}
		else
		{
			echo "Access Deny";
		}
	}
	function update_approve()
	{
		$this->cek_login();
		$k=$this->input->post('k');
		$id_item=$this->input->post('id_item');

			for($i=1;$i<=$k;$i++)
			{
				$id_formula=$this->input->post('id_formula'.$i);
				$ket=$this->input->post('keterangan'.$i);
				$stage=$this->input->post('stage'.$i);
				$stagek=$this->input->post('stagek'.$i);
				$status=$this->input->post('status'.$i);
				$form=array($stagek => $ket,
							$stage	=> $status,
							);
				$this->Transaksi_model->edit_form('id',$id_formula,$form,'formula2');
					if($stage=='approve1')
					{
						$keterangan='Panelis Risetman';
					}
					else if($stage=='approve2')
					{
						$keterangan='Panelis Internal';
					}
					else if($stage=='approve3')
					{
						$keterangan='Panelis Taste Specialist';
					}
					if($status==1)
					{
						$ket2='Approve';
					}
					else if($status==-1)
					{
						$ket2='Drop';
					}
					else if($status==0)
					{
						$ket2='Unapprove';
					}
					$hdr=$this->Transaksi_model->hdr_formula($this->input->post('id_formula'.$i))->row();
					$form=array('kegiatan'		=>		$ket2.' '.$keterangan.' formula '.$hdr->kode.' produk '.$hdr->nama_item.' karena '.$ket
								,'pic'			=>		$this->session->userdata('nama_seas')//ganti
								,'tgl'			=>		date("Y-m-d H:i:s")
								,'realname' 	=>		$this->session->userdata('realname_seas')//ganti
								);
					$this->Transaksi_model->add_form($form,'log_act');
				if($status!=0)
				{
				
					
					
					$form=array(	'keterangan'		=>		$keterangan.' '.$ket2
									,'id_formula'		=>		$id_formula
									,'tanggal'			=>		date('Y-m-d')
									,'tgl_input'		=>		date('Y-m-d')
									);
					$this->Transaksi_model->add_form($form,'stage_formula');
				}
			}
		redirect('resume_produk/'.$id_item);
	}
	function awal_akses()
	{
		$this->cek_login();
		$id_menu39=39;
		$auth_menu39=$this->checkaut_menu($id_menu39);
		$data['auth_menu39']=$auth_menu39;
		if($auth_menu39->R==1)
		{
			$data['list']=$this->Transaksi_model->list_item_akses($this->session->userdata('id_seas'))->result();//ganti
			$data['main_view']='v_awal_akses';
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function akses($id)
	{
		$this->cek_login();
		$id_menu39=39;
		$auth_menu39=$this->checkaut_menu($id_menu39);
		$data['auth_menu39']=$auth_menu39;
		if($auth_menu39->R==1)
		{
			$akses=$this->Transaksi_model->akses_item($id,$this->session->userdata('id_seas'))->result();//ganti
			foreach($akses as $ak)
			{
				$data['produk']=$ak->nama_item;
			}
			$data['id']=$id;
			$data['akses']=$akses;
			$data['form']=site_url('mkt/edit_akses_item/'.$id);
			$data['form3']=site_url('mkt/awal_akses');
			$data['main_view']='v_akses';
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_akses_item($id)
	{
		$this->cek_login();
		$id_menu39=39;
		$auth_menu39=$this->checkaut_menu($id_menu39);
		$data['auth_menu39']=$auth_menu39;
		if($auth_menu39->U==1)
		{
			$user=$this->Master_model->list_under_spv($this->session->userdata('id_seas'))->result();//ganti
			foreach($user as $us)
			{
				if($us->id!=$this->session->userdata('id_seas'))//ganti
				{
					$akses=$this->input->post('c-'.$us->Username);
					if($akses=="")
					{
						$flag=0;
					}
					else
					{
						$flag=1;
					}
					echo $flag;
					$form=array('akses'=>$flag);
					$form2=array('id_user'=>$us->id,'item'=>$id);
					$this->Transaksi_model->edit_form2($form2,$form,'akses_item');
				}
			}
			redirect('mkt/akses/'.$id);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function awal_akses_lp()
	{
		$this->cek_login();
		$id_menu39=39;
		$auth_menu39=$this->checkaut_menu($id_menu39);
		$data['auth_menu39']=$auth_menu39;
		if($auth_menu39->R==1)
		{
			$data['list']=$this->Transaksi_model->list_line_akses($this->session->userdata('id_seas'))->result();//ganti
			$data['main_view']='v_awal_akses_lp';
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function akses_lp($id)
	{
		$this->cek_login();
		$id_menu39=39;
		$auth_menu39=$this->checkaut_menu($id_menu39);
		$data['auth_menu39']=$auth_menu39;
		if($auth_menu39->R==1)
		{
			$akses=$this->Transaksi_model->akses_lp2($id,$this->session->userdata('id_seas'))->result();//ganti
			foreach($akses as $ak)
			{
				$data['produk']=$ak->lineproduk;
			}
			$data['id']=$id;
			$data['akses']=$akses;
			$data['form']=site_url('mkt/edit_akses_lp/'.$id);
			$data['form3']=site_url('mkt/awal_akses_lp');
			$data['main_view']='v_akses_lp';
			$this->load->view('sidemenu',$data);
		}
		else
		{
			echo "Access Deny";
		}
	}
	function edit_akses_lp($id)
	{
		$this->cek_login();
		$id_menu39=39;
		$auth_menu39=$this->checkaut_menu($id_menu39);
		$data['auth_menu39']=$auth_menu39;
		if($auth_menu39->U==1)
		{
			$user=$this->Master_model->list_under_spv($this->session->userdata('id_seas'))->result();//ganti
			foreach($user as $us)
			{
				echo $us->id.'-'.$id.'-'.$flag;
				if($us->id!=$this->session->userdata('id_seas'))//ganti
				{
					$akses=$this->input->post('c-'.$us->id);
					if($akses=="")
					{
						$flag=0;
					}
					else
					{
						$flag=1;
					}
					echo $flag;
					$form=array('akses'=>$flag);
					$form2=array('id_user'=>$us->id,'id_lp'=>$id);
					$this->Transaksi_model->edit_form2($form2,$form,'akses_lp');
				}
			}
			redirect('mkt/akses_lp/'.$id);
		}
		else
		{
			echo "Access Deny";
		}
	}

}

