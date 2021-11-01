<?php
class tabel extends CI_Controller {
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
	function awal()
	{
		$this->cek_login();
		$data['main_view']='v_tabel';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']='';
		$data['panelis_selected']=1;
		$data['form']=site_url('tabel');
		$data['form2']=site_url('tabel/excel_tabel');
		if($this->input->post('item'))
		{
			$data['items']=$this->input->post('item');
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['ke']=$this->input->post('panelis');
			$data['type']=$this->input->post('type');
		}
		else
		{
			$data['items']=0;
			$data['ke']=1;
			$data['type']=1;
		}
		$this->load->view('sidemenu',$data);
	}	
	function excel_tabel()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$panelis_selected=$this->input->post('panelis');
		$ke=$this->input->post('panelis');
		$type=$this->input->post('type');
		if($type==1)
		{
			if($panelis_selected==0)
			{
				$ke=3;
			}
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$num=$this->Transaksi_model->tabel_kode($item,$ke)->num_rows();
			if($num>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					$hdr=$this->Transaksi_model->tabel_kode($item,$ke)->result();
					$row++;
					
					
					$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					
					foreach($hdr as $hd)
					{
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					
					$data=$this->Transaksi_model->nilai_lpa($item,$panelis_selected)->result();
					$var1='';
					$var2='';
					$panelis1='';
					$panelis2='';
					foreach($data as $dt)
					{
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$sv=0;
						}
						$sv++;
						$var[$dt->varr]=$sv;
						$var1=$var2;
						
						$panelis2=$dt->panelis;
						if($panelis2!=$panelis1)
						{
							$pan=0;
						}
						$pan++;
						$var[$dt->panelis]=$pan;
						$panelis1=$panelis2;
					}
					$var1='';
					$var2='';
					$panelis1='';
					$panelis2='';
					foreach($data as $dt)
					{
						$panelis2=$dt->panelis;
						if($panelis2!=$panelis1)
						{
							if($panelis_selected==0)
							{
								if($dt->ke==1)
								{
									$step="(risetman)";
								}
								else if($dt->ke==2)
								{
									$step="(internal)";
								}
								else if($dt->ke==3)
								{
									$step="(TS)";
								}
								
							}
							else
							{
								$step="";
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis.$step);
							$mer=$row+$var[$dt->panelis]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
						}
						$panelis1=$panelis2;
						
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
							$mer=$row+$var[$dt->varr]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
						}
						$var1=$var2;
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$nilai1=0;
						$nilai2=0;
						$k=0;
						$col=3;
						foreach($hdr as $hd)
						{
							
							$k++;
							$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
							$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
							$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
							$nilai2=round((float)$dt->$vnilai,2);
							$skala2=round((float)$dt->$skala,2);
							if($nilai2>$nilai1)
							{
								$tanda="+";
							}
							else if($nilai2<$nilai1)
							{
								$tanda="-";
							}
							else
							{
								$tanda="";
							}
							if($k==1)
							{
								$tanda="";
							}
							
							if($nilai2<=70.9)
							{
								
								$font="ff0000";
								$nilai=$nilai2;
								$bgcolor="ffc0cb";
							}
							else if(71<=$nilai2 and $nilai2<73)
							{
								$font="000000";
								$nilai=$nilai2;
								$bgcolor="ffff00";
							}
							else if($nilai2==73)
							{
								$font="364522";
								$nilai=$nilai2;
								$bgcolor="00ff00";
							}
							else if($nilai2>73)
							{
								$font="ffffff";
								$nilai=$nilai2;
								$bgcolor="0000ff";
							}
							
							
							$nilai1=$nilai2;
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => $bgcolor)),
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => $font)),
								)
								);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}					
						$row++;
					}
					if($ke==3)
					{						 
						$col=0;
						$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
						if($numk>0)
						{
							$total=($num*3)+3;
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
						$row++;
						$panelis1='';
						$panelis2='';
						$var1='';
						$var2='';
						foreach($kes_hdr as $kes_hdr)
						{
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							$row++;
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$saran=$kes->saran;
								}
								else
								{
									$saran="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
								
							}
							$row++;
						}
						}
					}
					else
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
						$col=3;
						foreach($hdr as $hd)
						{
							$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numkes>0)
							{
								$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$kesimpulan=$kes->kesimpulan;
							}
							else
							{
								$kesimpulan="";
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
					}
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdr as $hd)
					{
						$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
						if($numsm>0)
						{
							$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdr as $hd)
					{
						
						$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
						if($num_desc_sm>0)
						{
						$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
						$desc_sm=$desc_sm->deskripsi;
						}
						else
						{
							$desc_sm="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdr as $hd)
					{
						$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
						if($numac>0)
						{
							$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
							$action=$ac->action_plan;
						}
						else
						{
							$action="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}

					
						
					
			}
		}
		else
		{
			if($panelis_selected==0)
			{
				$ke=3;
			}
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$num=$this->Transaksi_model->tabel_kode($item,$ke)->num_rows();
			if($num>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					$hdr=$this->Transaksi_model->tabel_kode($item,$ke)->result();
					$row++;
					
					
					$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Panelis');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Var');
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Subvar');
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					
					foreach($hdr as $hd)
					{
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					
					$data=$this->Transaksi_model->nilai_lpa2($item,$panelis_selected)->result();
					$var1='';
					$var2='';
					$subvar1='';
					$subvar2='';
					foreach($data as $dt)
					{
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$sv=0;
						}
						$sv++;
						$var[$dt->varr]=$sv;
						$var1=$var2;
						
						$subvar2=$dt->subvar;
						if($subvar2!=$subvar1)
						{
							$pan=0;
						}
						$pan++;
						$var[$dt->subvar]=$pan;
						$subvar1=$subvar2;
					}
					$var1='';
					$var2='';
					$subvar1='';
					$subvar2='';
					foreach($data as $dt)
					{
						
						
						
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
							$mer=$row+$var[$dt->varr]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
						}
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$var1=$var2;
						
						$subvar2=$dt->subvar;
						if($subvar2!=$subvar1)
						{
							
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $dt->subvar);
							$mer=$row+$var[$dt->subvar]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
						}
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$subvar1=$subvar2;
						if($panelis_selected==0)
						{
							if($dt->ke==1)
							{
								$step="(risetman)";
							}
							else if($dt->ke==2)
							{
								$step="(internal)";
							}
							else if($dt->ke==3)
							{
								$step="(TS)";
							}
							
						}
						else
						{
							$step="";
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$dt->panelis.$step);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$nilai1=0;
						$nilai2=0;
						$k=0;
						$col=3;
						foreach($hdr as $hd)
						{
							
							$k++;
							$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
							$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
							$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
							$nilai2=round((float)$dt->$vnilai,2);
							$skala2=round((float)$dt->$skala,2);
							if($nilai2>$nilai1)
							{
								$tanda="+";
							}
							else if($nilai2<$nilai1)
							{
								$tanda="-";
							}
							else
							{
								$tanda="";
							}
							if($k==1)
							{
								$tanda="";
							}
							
							if($nilai2<=70.9)
							{
								
								$font="ff0000";
								$nilai=$nilai2;
								$bgcolor="ffc0cb";
							}
							else if(71<=$nilai2 and $nilai2<73)
							{
								$font="000000";
								$nilai=$nilai2;
								$bgcolor="ffff00";
							}
							else if($nilai2==73)
							{
								$font="364522";
								$nilai=$nilai2;
								$bgcolor="00ff00";
							}
							else if($nilai2>73)
							{
								$font="ffffff";
								$nilai=$nilai2;
								$bgcolor="0000ff";
							}
							
							
							$nilai1=$nilai2;
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => $bgcolor)),
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => $font)),
								)
								);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}					
						$row++;
					}
					if($ke==3)
					{						 
						$col=0;
						$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
						if($numk>0)
						{
							$total=($num*3)+3;
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
						$row++;
						$panelis1='';
						$panelis2='';
						$var1='';
						$var2='';
						foreach($kes_hdr as $kes_hdr)
						{
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							$row++;
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$saran=$kes->saran;
								}
								else
								{
									$saran="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
								
							}
							$row++;
						}
						}
					}
					else
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
						$col=3;
						foreach($hdr as $hd)
						{
							$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numkes>0)
							{
								$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$kesimpulan=$kes->kesimpulan;
							}
							else
							{
								$kesimpulan="";
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
					}
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdr as $hd)
					{
						$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
						if($numsm>0)
						{
							$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdr as $hd)
					{
						
						$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
						if($num_desc_sm>0)
						{
						$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
						$desc_sm=$desc_sm->deskripsi;
						}
						else
						{
							$desc_sm="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdr as $hd)
					{
						$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
						if($numac>0)
						{
							$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
							$action=$ac->action_plan;
						}
						else
						{
							$action="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}

					
						
					
			}
		
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel Penilaian All.xls"');
		$object_writer->save('php://output');
	}
	
	function LPA10()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_10';
		$data['item']=$this->Master_model->get_produk()->result();
		$data['item_selected']='';
		$data['panelis_selected']='';
		$data['form']=site_url('tabel/LPA10');
		$data['form2']=site_url('tabel/excel_tabel_10');
		if($this->input->post('item'))
		{
			$data['items']=$this->input->post('item');
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['ke']=$this->input->post('panelis');
		}
		else
		{
			$data['items']=0;
			$data['ke']=1;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_10()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$ke=$this->input->post('panelis');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		
		$num=$this->Transaksi_model->tabel_kode10($item,$ke)->num_rows();
		if($num>0)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);

			$jdl=$this->Transaksi_model->resume_item($item)->row();
			$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
			$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
			$object->getActiveSheet()->setCellValue('A1','Line Produk');
			$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
			$object->getActiveSheet()->setCellValue('A2','Nama Produk');
			$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
			$object->getActiveSheet()->setCellValue('A3','Awal Riset');
			$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
			$object->getActiveSheet()->setCellValue('A4','Risetman');
			$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
			$object->getActiveSheet()->setCellValue('A5','Target Riset');
			$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
			$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->setCellValue('A6','Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B6',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
				$object->getActiveSheet()->setCellValue('A7','Lama Waktu Panelis Terakhir');
				$tanggal  = strtotime($jdl2->tgl_panelis);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B7',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
				$object->getActiveSheet()->setCellValue('A8','Total Formula');
				$object->getActiveSheet()->setCellValue('B8',$total_formula);
				
				
				$object->getActiveSheet()->setCellValue('A11','Panelis');
				$object->getActiveSheet()->setCellValue('B11','Var');
				$object->getActiveSheet()->setCellValue('C11','Subvar');
				$object->getActiveSheet()->getStyle('A11:C11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A11:C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A11:C11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=3;
				$numkom=$this->Transaksi_model->tabel_kode_kompetitor($item)->num_rows();
				$hdk=$this->Transaksi_model->tabel_kode_kompetitor($item)->result();
				$hd2=$this->Transaksi_model->tabel_kode10($item,$ke)->result();
				foreach($hdk as $hd)
				{
					
					$object->getActiveSheet()->mergeCellsByColumnAndRow($col,11,$col+1,11);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 11, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,11)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,11)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col+=2;
				}
				foreach($hd2 as $hd)
				{
					
					$object->getActiveSheet()->mergeCellsByColumnAndRow($col,11,$col+1,11);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 11, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,11)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,11)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,11)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col+=2;
				}
				
				//isi
				
				$row=11;
				$list=$this->Transaksi_model->tabel_temp1($item,$ke)->result();
				foreach($list as $list)
				{
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $list->panelis);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $list->varr);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $list->subvar);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$nilai1=0;
					$nilai2=0;
					$k=0;
					$col=3;
					foreach($hdk as $hd)
					{
						$num=$this->Transaksi_model->tabel_temp3_kompetitor($hd->kode,$list->panelis,$list->subvar,$item,$ke,$hd->tanggal)->num_rows();
						if($num>0)
						{
							$a=$this->Transaksi_model->tabel_temp3_kompetitor($hd->kode,$list->panelis,$list->subvar,$item,$ke,$hd->tanggal)->row();
							$nilai2=round($a->nilai,2);
							$komen=$a->keterangan;
						}
						else
						{
							$nilai2=0;
							$komen="";
						}     
					
						
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai2);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $komen);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col+=2;
					}
				
					foreach($hd2 as $hd)
					{
						
						$k++;
								$num=$this->Transaksi_model->tabel_temp3($hd->kode,$list->panelis,$list->subvar,$item,$ke,$hd->tanggal)->num_rows();
								if($num>0)
								{
									$a=$this->Transaksi_model->tabel_temp3($hd->kode,$list->panelis,$list->subvar,$item,$ke,$hd->tanggal)->row();
									$nilai2=round($a->nilai,2);
									$komen=$a->keterangan;
								}
								else
								{
									$nilai2=0;
									$komen="";
								}     
								
								if($nilai2>$nilai1)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2<$nilai1)
								{
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if($nilai2==0)
								{
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								
								if($k==1)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffffff";
								}
							
								$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $komen);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=2;
					}
				
				}
			
				if($ke==3)
				{
					/* $row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,8,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Saran Taste Specialist');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(8,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$ts=$this->Transaksi_model->panelis_ts($item,$ke)->result();
					foreach($ts as $ts)
					{
						$row++;
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $ts->panelis);
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hd2 as $hd)
						{
							
							$nums=$this->Transaksi_model->saran($hd->id_formula,$ke,$ts->panelis)->num_rows();
							if($nums>0)
							{
								$sarants1=$this->Transaksi_model->saran($hd->id_formula,$ke,$ts->panelis)->row();
								$sarants=$sarants1->action_plan;
							}
							else
							{
								$sarants="";
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sarants);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=2;
						}
					} */
					$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
					if($numk>0)
					{
						
						$row++;
						$total=($numkom*2)+8;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,$total,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($total,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
						foreach($kes_hdr as $kes_hdr)
						{
							$row++;
							
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								$kesimpulan="";							
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$col+=2;
							}
							foreach($hd2 as $hd)
							{
								
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$col+=2;
							}
							$row++;
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,'Saran');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								$saran="";							
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$col+=2;
							}
							foreach($hd2 as $hd)
							{
								
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$saran=$kes->saran;
								}
								else
								{
									$saran="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$col+=2;
							}
						}
						$row++;
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Sumber Masalah');
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);

						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							$sumber_masalah="";							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$col+=2;
						}
						foreach($hd2 as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
							if($numsm>0)
							{
								$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
							//$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$sumber_masalah);
							//$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row,$desc_sm->deskripsi);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$col+=2;
						}
						$row++;
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Deskripsi Sumber Masalah');
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);

						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							$sumber_masalah="";							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$col+=2;
						}
						foreach($hd2 as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
							$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
							if($num_desc_sm>0)
							{
								$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
								$desc=nl2br(htmlspecialchars($desc_sm->deskripsi));
							}
							else
							{
								$desc="";
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$desc);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$col+=2;
						}
						$row++;
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Action plan');
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);

						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
						
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$hd->keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$col+=2;
						}
						foreach($hd2 as $hd)
						{
							
							$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numac>0)
							{
								$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$action=$ac->action_plan;
							}
							else
							{
								$action="";
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$col+=2;
						}
					}
				}

		
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel Penilaian All.xls"');
		$object_writer->save('php://output');
	}

	
	function tabel_avg()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_avg';
		$data['item']=$this->Master_model->get_produk()->result();
		$data['item_selected']='';
		$data['panelis_selected']='';
		$data['form']=site_url('tabel_avg');
		if($this->input->post('item'))
		{
			$data['items']=$this->input->post('item');
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['ke']=$this->input->post('panelis');
		}
		else
		{
			$data['items']=0;
			$data['ke']=1;
		}
		
		$this->load->view('sidemenu',$data);
	}
	function tabel_dtl($id)
	{
		$this->cek_login();
		$arr=explode('_',$id);
		$item=$arr[0];
		$ke=$arr[1];
		$subvar=$arr[2];
		$ro=$this->Transaksi_model-> penilaian($item,$subvar)->row();
		$data['main_view']='v_tabel_dtl';
		$data['item']=$this->Master_model->get_produk()->result();
		$data['item_selected']=$item;
		$data['items']=$item;
		$data['panelis_selected']=$ke;
		$data['subvar']=$subvar;
		$data['varr']=$ro->varr;
		$data['ke']=$ke;
		$data['form']=site_url('tabel_avg/');
		$this->load->view('sidemenu',$data);
	}
	function tabel_dtl_kode($id)
	{
		$this->cek_login();
		$arr=explode('_',$id);
		$item=$arr[0];
		$ke=$arr[1];
		$kode=$arr[2];
		$tgl=$arr[3];
		$data['main_view']='v_tabel_dtl_kode';
		$data['items']=$item;
		$data['ke']=$ke;
		$data['kode']=$kode;
		$data['tanggal']=$tgl;
		$data['form']=site_url('tabel_avg/');
		$this->load->view('sidemenu',$data);
	}
	function tabel_dtl_produk()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_dtl_produk';
		$data['item']=$this->Master_model->get_produk()->result();
		$la=$this->Transaksi_model->list_line_akses($this->session->userdata('id_seas'))->result();//ganti
		if(count($la)>0)
		{
			$data['line']=$la;
		}
		else
		{
			$la2=$this->Transaksi_model->list_line_item_akses($this->session->userdata('id_seas'))->result();//ganti
			$data['line']=$la2;	
		}
		
		
		//$data['line']=$this->Master_model->get_lp()->result();
		$data['line_selected']='';
		$data['item_selected']='';
		$data['panelis_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel_dtl_produk');
		$data['form2']=site_url('tabel/excel_tabel_dtl_produk');
		if($this->input->post('item'))
		{
			$data['line_selected']=$this->input->post('line');
			$data['item_selected']=$this->input->post('item');
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl1']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['tgl2']=$this->input->post('tgl_akhir');
			
		}
		else
		{
			$data['items']=0;
			$data['tgl']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	
	function excel_tabel_dtl_produk()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item_selected=$this->input->post('item');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		
		if($tgl_awal!='')
		{
			$tgl1=date('Y-m-d',strtotime($tgl_awal));
			$tgl2=date('Y-m-d',strtotime($tgl_akhir));
			
			$num=$this->Transaksi_model->hdr_date($tgl1,$tgl2,$item_selected)->num_rows();
			if($num>0)
			{
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);

				$ro=$this->Transaksi_model->hdr_date($tgl1,$tgl2,$item_selected)->result();
				$rok=$this->Transaksi_model->tabel_kode_kompetitor($item_selected)->result();
				$numkom=$this->Transaksi_model->tabel_kode_kompetitor($item_selected)->num_rows();
				$jdl=$this->Transaksi_model->resume_item($item_selected)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item_selected,3)->row();
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Line Produk');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 1,$jdl->lineproduk);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 2,'Nama Produk');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 2,$jdl->nama_item);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Awal Riset');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 4,'Risetman');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 4,$jdl->risetman);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5,'Target Riset');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5,$jdl->kompetitor);
				$object->getActiveSheet()->getCellByColumnAndRow(1,5)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 6,'Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal_riset=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal_riset, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 6,$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 7,'Lama Waktu Panelis Terakhir');
				$tanggal  = strtotime($jdl2->tgl_panelis);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal_panelis=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal_panelis, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 7,$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 8,'Total Formula');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 8,$num);
				
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 10,'Var');
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, 10,'Subvar');
				$object->getActiveSheet()->getCellByColumnAndRow(0,10)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,10)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(2,10)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=2;
				foreach($rok as $dt)
				{
					$col++;
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10,$dt->kode."\n".date('d-m-Y',strtotime($dt->tanggal)));
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
				foreach($ro as $dt)
				{
					$col++;
					if($dt->ke==1)
					{
						$state="Risetman";
					}
					else if($dt->ke==2)
					{
						$state="Internal";
					}
					else if($dt->ke==3)
					{
						$state="Taste Specialist";
					}
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10,$dt->kode."\n".date('d-m-Y',strtotime($dt->tanggal)));
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,10)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
				$listed=$this->Transaksi_model->penilaian_all($item_selected)->result();
				$row=10;
				$var1='';
				$var2='';
				$mer=array();
				foreach($listed as $list)
				{
					$var2=$list->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$var1=$var2;
					$sv++;
					$mer[$list->varr] = $sv;

				}
				$var1='';
				$var2='';
				foreach($listed as $list)
				{
					$row++;
					$var2=$list->varr;
					if($var1!=$var2)
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$list->varr]-1);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$list->varr);
					}
					$var1=$var2;
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$list->subvar);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$nilai1=0;
					$nilai2=0;
					$k=0;
					$col=2;
					foreach($rok as $dt)
					{
						$col++;
						$numa=$this->Transaksi_model->tabel_avg_param_kompetitor($item_selected,$list->subvar,$dt->kode,$dt->tanggal)->num_rows();
						if($numa>0)
						{
							$a=$this->Transaksi_model->tabel_avg_param_kompetitor($item_selected,$list->subvar,$dt->kode,$dt->tanggal)->row();
							$nilai2=round($a->nilai,2);
						}
						else
						{
							$nilai2=0;
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai2);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);							
					}
					foreach($ro as $dt)
					{
						$col++;
						$k++;
						$numa=$this->Transaksi_model->tabel_avg_param($item_selected,$dt->ke,$list->subvar,$dt->kode,$dt->tanggal)->num_rows();
						if($numa>0)
						{
							$a=$this->Transaksi_model->tabel_avg_param($item_selected,$dt->ke,$list->subvar,$dt->kode,$dt->tanggal)->row();
							$nilai2=round($a->nilai,2);
						}
						else
						{
							$nilai2=0;
						}
						if($nilai2>$nilai1)
						{
							$tanda="+";
						}
						else if($nilai2<$nilai1)
						{
							$tanda="-";
						}
						else
						{
							$tanda="";
						}
						if($k==1)
						{
							$tanda="";
						}
						
						if($nilai2<=70.9)
						{
							
							$font="ff0000";
							$nilai=$nilai2;
							$bgcolor="ffc0cb";
						}
						else if(71<=$nilai2 and $nilai2<73)
						{
							$font="000000";
							$nilai=$nilai2;
							$bgcolor="ffff00";
						}
						else if($nilai2==73)
						{
							$font="364522";
							$nilai=$nilai2;
							$bgcolor="00ff00";
						}
						else if($nilai2>73)
						{
							$font="ffffff";
							$nilai=$nilai2;
							$bgcolor="0000ff";
						}
						$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					}
				}
					$ke=3;
					$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->num_rows();
					if($numk>0)
					{
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,$num+2+$numkom,$row);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow($num+2+$numkom,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
					$panelis1='';
					$panelis2='';
					$var1='';
					$var2='';
					foreach($kes_hdr as $kes_hdr)
					{
						$row++;
						$panelis2=$kes_hdr->panelis;
						if($panelis1!=$panelis2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
						}
						$panelis1=$panelis2;
						
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
						$var2=$parameter;
						if($var1!=$var2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
						}
						$var1=$var2;
						
						
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=2;
						foreach($rok as $hd)
						{
							$col++;
							$kesimpulan="";
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						}
						foreach($ro as $hd)
						{
							$col++;
							$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
							if($numk2>0)
							{
								$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
								$kesimpulan=$kes->kesimpulan;
							}
							else
							{
								$kesimpulan="";
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						}
						$row++;
						$panelis2=$kes_hdr->panelis;
						if($panelis1!=$panelis2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
						}
						$panelis1=$panelis2;
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
						$var2=$parameter;
						if($var1!=$var2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
						}
						$var1=$var2;
						
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Saran');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=2;
						foreach($rok as $hd)
						{
							$col++;
							$saran="";
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						}
						foreach($ro as $hd)
						{
							$col++;
							$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
							if($numk2>0)
							{
								$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
								$saran=$kes->saran;
							}
							else
							{
								$saran="";
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						}
					}
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$col=2;
					foreach($rok as $hd)
					{
						$col++;
						$sumber_masalah="";
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					}
					foreach($ro as $hd)
					{
						$col++;
						$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
						if($numsm>0)
						{
							$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);

					}
					
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$col=2;
					foreach($rok as $hd)
					{
						$col++;
						$desc_sm="";
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					}
					foreach($ro as $hd)
					{
						$col++;
						$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
						if($num_desc_sm>0)
						{
							$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
							$desc=nl2br(htmlspecialchars($desc_sm->deskripsi));
						}
						else
						{
							$desc="";
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);

					}
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action plan');
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$col=2;
					foreach($rok as $hd)
					{
						$col++;
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->keterangan);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					}
					foreach($ro as $hd)
					{
						$col++;
						$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,3)->num_rows();
						if($numac>0)
						{
							$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,3)->row();
							$action=$ac->action_plan;
						}
						else
						{
							$action="";
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);

					}
						
						
				}
				
				$col+=3;
				foreach($rok as $dt)
				{
					
					$listedf=$this->Transaksi_model->tabel_dtl2_kompetitor($dt->id_formula,$dt->tanggal)->result();
					$row=17;
					$var1='';
					$var2='';
					$panelis1='';
					$panelis2='';
					$jum_var=0;
					$mer=array();
					foreach($listedf as $listf)
					{
						$var2=$listf->varr;
						if($var1!=$var2)
						{
							$sv=0;
							$jum_var++;
						}
						$var1=$var2;
						$sv++;
						$mer[$listf->varr] = $sv;

						$panelis2=$listf->panelis;
						 if($panelis1!=$panelis2)
						 {
							 $pan=0;
						 }
						$panelis1=$panelis2;
						$pan++;
						$mer[$listf->panelis] = $pan;
					}
					$var1='';
					$var2='';
					$panelis1='';
					$panelis2='';
					foreach($listedf as $listf)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10, 'Nama Kompetitor');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 10, $listf->nama);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 11, 'Tujuan');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 11, $listf->tujuan);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 16, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 16, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, 16, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, 16, 'Nilai');
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, 16, 'Keterangan');
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$panelis2=$listf->panelis;
						if($panelis1!=$panelis2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+$mer[$listf->panelis]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $listf->panelis);
						}
						$panelis1=$panelis2;
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$var2=$listf->varr;
						if($var1!=$var2)
						{
							$merv=
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+$mer[$listf->varr]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $listf->varr);
						}
						$var1=$var2;
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $listf->subvar);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, round($listf->nilai,2));
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $listf->keterangan);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$row++;
					}
					
					$col+=7;
				}
				
				foreach($ro as $dt)
				{
					
					$listedf=$this->Transaksi_model->tabel_dtl2($item_selected,$dt->ke,$dt->kode,$dt->tanggal)->result();
					$row=17;
					$var1='';
					$var2='';
					$panelis1='';
					$panelis2='';
					$jum_var=0;
					$mer=array();
					foreach($listedf as $listf)
					{
						$var2=$listf->varr;
						if($var1!=$var2)
						{
							$sv=0;
							$jum_var++;
						}
						$var1=$var2;
						$sv++;
						$mer[$listf->varr] = $sv;

						$panelis2=$listf->panelis;
						 if($panelis1!=$panelis2)
						 {
							 $pan=0;
						 }
						$panelis1=$panelis2;
						$pan++;
						$mer[$listf->panelis] = $pan;
					}
					$var1='';
					$var2='';
					$panelis1='';
					$panelis2='';
					foreach($listedf as $listf)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10, 'Formula');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 10, $listf->kode);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 11, 'Tanggal  Riset');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 11, date('d-m-Y',strtotime($listf->tgl_riset)));
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 12, 'Risetman');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 12, $listf->risetman_hdr);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 13, 'Formula By');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 13, $listf->risetman);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 14, 'Tujuan');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 14, $listf->tujuan);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 16, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 16, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, 16, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, 16, 'Nilai');
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, 16, 'Keterangan');
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,16)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$panelis2=$listf->panelis;
						if($panelis1!=$panelis2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+$mer[$listf->panelis]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $listf->panelis);
						}
						$panelis1=$panelis2;
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$var2=$listf->varr;
						if($var1!=$var2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+$mer[$listf->varr]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $listf->varr);
						}
						$var1=$var2;
						
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $listf->subvar);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, round($listf->nilai,2));
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $listf->keterangan);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$row++;
					}
					
					$col+=7;
				}
				
			}
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Penilaian Date to date.xls"');
		$object_writer->save('php://output');
	}
	function print_formula($id)
	{
		$this->cek_login();
		$data['formula']=$this->Transaksi_model->formula_bahan_all($id)->result();
		$this->load->view('v_print_formula',$data);
	}
	function print_nilai($id)
	{
		$this->cek_login();
		$arr=explode('_',$id);
		$kode =  $arr[0];
		$ke =  $arr[1];
		$id_item =  $arr[2];
		$tgl =  $arr[3];
		$data['formula']=$this->Transaksi_model->tabel_dtl2($id_item,$ke,$kode,$tgl)->result();
		$this->load->view('v_print_nilai',$data);
	}
	function get_produk()
	{
		$this->cek_login();
		$id_lp=$this->input->post('id_lp');
		$data=$this->Master_model->get_produk2_akses($this->session->userdata('nama_seas'),$id_lp)->result();//ganti
		echo json_encode($data);
	}
	function get_risetman()
	{
		$this->cek_login();
		$id_item=$this->input->post('id_item');
		
		$data=$this->Transaksi_model->risetman_formula_hdr($id_item)->result();
		echo json_encode($data);
	}
	function get_penilaian_ke()
	{
		$this->cek_login();
		$kode=$this->input->post('kode');
		$ke=$this->input->post('ke');
		$id_item=$this->input->post('id_item');
		$tgl=$this->input->post('tgl');
		$data=$this->Transaksi_model->tabel_dtl2($id_item,$ke,$kode,$tgl)->result();
		echo json_encode($data);
	}
	function get_penilaian_kompetitor()
	{
		$this->cek_login();
		$id_formula=$this->input->post('id_formula');
		$tgl=$this->input->post('tgl');
		$data=$this->Transaksi_model->tabel_dtl2_kompetitor($id_formula,$tgl)->result();
		echo json_encode($data);
	}
	function get_formula()
	{
		$this->cek_login();
		$kode=$this->input->post('kode');
		$data=$this->Transaksi_model->formula_bahan_all($kode)->result();
		echo json_encode($data);
	}
	function get_tabel_sarana_formula()
	{
		$this->cek_login();
		$data=$this->Transaksi_model->formula_sarana_all($this->input->post('kode'))->result();
		echo json_encode($data);
	}
	function get_penilaian_panelis_ke()
	{
		$this->cek_login();
		$kode=$this->input->post('kode');
		$panelis=$this->input->post('panelis');
		$subvar=$this->input->post('subvar');
		$id_item=$this->input->post('id_item');
		$ke=$this->input->post('ke');
		$tgl=$this->input->post('tgl');
		$data=$this->Transaksi_model->tabel_temp3($kode,$panelis,$subvar,$id_item,$ke,$tgl)->result();
		echo json_encode($data);
	}

	function tabel_penilaian_risetman()
	{
		$this->cek_login(); 
		$data['main_view']='v_tabel_penilaian_risetman2';
		$data['form']=site_url('tabel_penilaian_risetman');	
		$data['form2']=site_url('tabel/excel_penilaian_risetman');	
			if($this->input->post('tgl_awal'))
			{
				$data['tgl1']=$this->input->post('tgl_awal');
				$data['tgl_awal']=$this->input->post('tgl_awal');
				$data['tgl2']=$this->input->post('tgl_akhir');
				$data['tgl_akhir']=$this->input->post('tgl_akhir');
				
			}
			else
			{
				$data['tgl1']='';
			}
			
			$this->load->view('sidemenu',$data);
		
		
	}
	function excel_penilaian_risetman()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		
		$tgl1=$this->input->post('tgl_awal');
		$tgl2=$this->input->post('tgl_akhir');
		$tgl1=date('Y-m-d',strtotime($tgl1));
		$tgl2=date('Y-m-d',strtotime($tgl2));
		$num=$this->Transaksi_model->hdr_penilaian_risetmans_akses($tgl1,$tgl2)->num_rows();
		if($num>0)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(65);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Nama Risetman');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'Produk Line');
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, 1,'Nama Produk');
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, 1,'Jumlah Formula');
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, 1,'Status Produk');
				$object->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A1:E1')->applyFromArray(array(
								
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => '000000')),
									)
									);
								
				$ro=$this->Transaksi_model->hdr_penilaian_risetmans_akses($tgl1,$tgl2)->result();
				$row=1;
				foreach($ro as $dt)
				{
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->lineproduk);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$dt->nama_item);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $row,$dt->jumlah);
					if($dt->status==0)
					{
						$status="Progress";
					}
					else if($dt->status==-1)
					{
						$status="Terminate";
					}
					else if($dt->status==1)
					{
						$status="Launching";
					}
					else if($dt->status==2)
					{
						$status="Bank Produk-ACC";
					}
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $row,$status);
				}
					$object->getActiveSheet()->getStyle('A1:E'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Penilaian Risetman.xls"');
		$object_writer->save('php://output');
	}
	function coba()
	{//17_63_2020-06-01_2020-07-08_Indah
		echo $this->input->post('line')."_";
		echo $this->input->post('item')."_";
		echo date("Y-m-d",strtotime($this->input->post('tgl_awal')))."_";
		echo date("Y-m-d",strtotime($this->input->post('tgl_akhir')))."_";
		echo $this->input->post('risetman');
		$id=$this->input->post('line')."_".$this->input->post('item')."_".date("Y-m-d",strtotime($this->input->post('tgl_awal')))."_".date("Y-m-d",strtotime($this->input->post('tgl_akhir')))."_".$this->input->post('risetman');
		redirect('tabel_dtl_risetman/'.$id);
		
	}
	function tabel_dtl_risetman($id)
	{
		$this->cek_login();
		$arr=explode('_',$id);
		$line=$arr[0];
		$item=$arr[1];
		$tgl1=$arr[2];
		$tgl2=$arr[3];
		$risetman=$arr[4];
		$data['main_view']='v_tabel_formula_risetman2';
		$data['item']=$this->Master_model->get_produk()->result();
		$data['line']=$this->Master_model->get_lp()->result();
		$data['form']=site_url('tabel_penilaian_risetman');
		$data['form3']=site_url('tabel_dtl_risetman/'.$id);
		$data['form2']=site_url('tabel/excel_dtl_risetman/'.$id);
		$data['form4']=site_url('tabel/coba/');
		
		$data['line_selected']=$line;
		$data['item_selected']=$item;
		$data['risetman_selected']=$risetman;
		$data['risetman']=$risetman;
		$data['ke']=0;
		$url = $_SERVER['REQUEST_URI'];
		$url_components = parse_url($url);
		if(count($url_components)>1)
		{
			parse_str($url_components['query'], $params); 
			$data['ke']=$params['panelis']; 
			
		}
		$data['tgl_awal']=date('d-m-Y',strtotime($tgl1));
		$data['tgl_akhir']=date('d-m-Y',strtotime($tgl2));
		
			
				
		$this->load->view('sidemenu',$data);
	}


	function excel_dtl_risetman($id)
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		
		$arr=explode('_',$id);
		$line_selected=$arr[0];
		$item_selected=$arr[1];
		$tgl1=$arr[2];
		$tgl2=$arr[3];
		$risetman=$arr[4];
		$ke=0;
		$data['main_view']='v_tabel_formula_risetman2';
		$url = $_SERVER['REQUEST_URI'];
		$url_components = parse_url($url);
		if(count($url_components)>1)
		{
			parse_str($url_components['query'], $params); 
			$data['ke']=$params['panelis']; 
			$ke=$params['panelis']; 
			
		}
		$object->getActiveSheet()->getColumnDimension('A')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$jdl=$this->Transaksi_model->hdr_penilaian_risetmans2($tgl1,$tgl2,$item_selected,$risetman,$ke)->row();
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Produk Line');
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 1,$jdl->lineproduk);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 2,'Nama Produk');
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 2,$jdl->nama_item);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Target Riset');
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,$jdl->kompetitor);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 4,'Risetman');
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 4,$jdl->risetman);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5,'Awal Riset by Risetman');
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5,date('d-m-Y',strtotime($jdl->tgl_awal)));
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 6,'Terakhir Panelis');
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 6,date('d-m-Y',strtotime($jdl->tgl_panelis)));
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 7,'Total Formula');
		$num0=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,0)->num_rows();
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 7,$num0);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 8,'Total Panelis Risetman');
		$num1=$this->Transaksi_model->jum_panelis_dates($tgl1,$tgl2,$item_selected,$risetman,1)->num_rows();
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 8,$num1.'( '.round(($num1/$num0*100),2).' % )');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 9,'Total Panelis Internal');
		$num2=$this->Transaksi_model->jum_panelis_dates($tgl1,$tgl2,$item_selected,$risetman,2)->num_rows();
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 9, $num2 .'( '.round(($num2/$num0*100),2).' % )');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 10,'Total Panelis Taste Specialist');
		$num3=$this->Transaksi_model->jum_panelis_dates($tgl1,$tgl2,$item_selected,$risetman,3)->num_rows();
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 10,$num3 .'( '.round(($num3/$num0*100),2).' % )');
		
		$num=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,$ke)->num_rows();
		if($num>0)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 12,'Var');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 12,'Subvar');
			$object->getActiveSheet()->getCellByColumnAndRow(0,12)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(0,12)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(0,12)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow(1,12)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(1,12)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(1,12)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$rok=$this->Transaksi_model->tabel_kode_kompetitor($item_selected)->result();
			$ro=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,$ke)->result();
			$col=1;
			/* foreach($rok as $dt)
			{
				$col++;
				$object->getActiveSheet()->setCellValueByColumnAndRow($col, 12,$dt->kode."\n \n".$dt->tanggal);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			}
			 */
			foreach($ro as $dt)
			{
				$col++;
				if($dt->ke==1)
				{
					$state="Risetman";
					$tgl=date('d-m-Y',strtotime($dt->tanggal));
				}
				else if($dt->ke==2)
				{
					$state="Internal";
					$tgl=date('d-m-Y',strtotime($dt->tanggal));
				}
				else if($dt->ke==3)
				{
					$state="Taste Specialist";
					$tgl=date('d-m-Y',strtotime($dt->tanggal));
				}
				else
				{
					$state="Belum Panelis";
					$tgl='';
				}
				$object->getActiveSheet()->setCellValueByColumnAndRow($col, 12,$dt->kode."\n".$state."\n".$tgl);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,12)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			}
			$row=12;
			$listed=$this->Transaksi_model->nilai_ldr($item_selected,$ke,date('Y-m-d',strtotime($tgl1)),date('Y-m-d',strtotime($tgl2)),$risetman)->result();
			$var1='';
			$var2='';
			foreach($listed as $list)
			{
				$var2=$list->varr;
				if($var1!=$var2)
				{
					$sv=0;
				}
				$sv++;
				$mer[$var2]=$sv;
				$var1=$var2;
			}
			$var1='';
			$var2='';
			foreach($listed as $list)
			{
				$row++;
				$var2=$list->varr;
				if($var2!=$var1)
				{
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$var2);
				}
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$var1=$var2;
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$list->subvar);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$nilai1=0;
				$nilai2=0;
				$k=0;
				$col=1;
				foreach($ro as $hd)
				{
					
					$col++;
					$k++;
					if(!empty($hd->ke))
					{
							
						$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
						$nilai2=round($list->$vnilai,2);
					}
					else
					{
						$nilai2=0;
					}
					if($nilai2>$nilai1)
					{
						$tanda="+";
					}
					else if($nilai2<$nilai1)
					{
						$tanda="-";
					}
					else
					{
						$tanda="";
					}
					if($k==1)
					{
						$tanda="";
					}
					
					if($nilai2<=70.9)
					{
						
						$font="ff0000";
						$nilai=$nilai2;
						$bgcolor="ffc0cb";
					}
					else if(71<=$nilai2 and $nilai2<73)
					{
						$font="000000";
						$nilai=$nilai2;
						$bgcolor="ffff00";
					}
					else if($nilai2==73)
					{
						$font="364522";
						$nilai=$nilai2;
						$bgcolor="00ff00";
					}
					else if($nilai2>73)
					{
						$font="ffffff";
						$nilai=$nilai2;
						$bgcolor="0000ff";
					}
					
					
					$nilai1=$nilai2;
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => $bgcolor)),
						'font'  => array(
							'color' => array('rgb' => $font)),
							)
							);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$nilai.$tanda);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
				
				
			}
								
			//detail formula
			$col+=3;
			/* 
			foreach($ro as $dt)
			{
				if (isset($dt->ke))
				{
				$listedf=$this->Transaksi_model->tabel_dtl2($item_selected,$dt->ke,$dt->kode,$dt->tanggal)->result();
				$row=19;
				$panelis1='';
				$panelis2='';
				$var1='';
				$var2='';
				$mer=array();
				foreach($listedf as $listf)
				{
					$panelis2=$listf->panelis;
					if($panelis1!=$panelis2)
					{
						$pan=0;
					}
					$pan++;
					$mer[$panelis2]=$pan;
					$panelis1=$panelis2;

					$var2=$listf->varr;
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
				foreach($listedf as $listf)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 12, 'Formula');	
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 12, $listf->kode);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 13, 'Tanggal  Riset');	
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 13, date('d-m-Y',strtotime($listf->tgl_riset)));
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 14, 'Risetman');	
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 14, $listf->risetman_hdr);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 15, 'Formula By');	
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 15, $listf->risetman);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 16, 'Tujuan');	
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 16, $listf->tujuan);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, 18, 'Panelis');
					$object->getActiveSheet()->getCellByColumnAndRow($col,18)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,18)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,18)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,18)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 18, 'Var');
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,18)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,18)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,18)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,18)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, 18, 'Subvar');
					$object->getActiveSheet()->getCellByColumnAndRow($col+2,18)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+2,18)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+2,18)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+2,18)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, 18, 'Nilai');
					$object->getActiveSheet()->getCellByColumnAndRow($col+3,18)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+3,18)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+3,18)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+3,18)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, 18, 'Keterangan');
					$object->getActiveSheet()->getCellByColumnAndRow($col+4,18)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+4,18)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+4,18)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col+4,18)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$panelis2=$listf->panelis;
					if($panelis1!=$panelis2)
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+$mer[$panelis2]-1);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $listf->panelis);
					}
					$panelis1=$panelis2;
					$var2=$listf->varr;
					if($var1!=$var2)
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+$mer[$var2]-1);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $listf->varr);
					}
					$var1=$var2;
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);					$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $listf->subvar);
					$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, round($listf->nilai,2));
					$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $listf->keterangan);
					$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$row++;
				}
				
				$col+=7;
				}
			}
		 */
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman Detail.xls"');
		$object_writer->save('php://output');
	}
	
	
	
	
	function tabel_waktu()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_waktu';
		$data['line']=$this->Master_model->get_lp()->result();
		$data['line_selected']='';
		$data['form']=site_url('tabel_waktu');
		if($this->input->post('line'))
		{
			$data['line_selected']=$this->input->post('line');
			
		}
		else
		{
			$data['line_selected']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	
	function tabel_waktu2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_waktu2';
		//$data['line']=$this->Master_model->get_lp()->result();
		$la=$this->Transaksi_model->list_line_akses($this->session->userdata('id_seas'))->result();//ganti
		if(count($la)>0)
		{
			$data['line']=$la;
		}
		else
		{
			$la2=$this->Transaksi_model->list_line_item_akses($this->session->userdata('id_seas'))->result();//ganti
			$data['line']=$la2;	
		}
		$data['line_selected']='';
		$data['lines']='';
					$linea=array();
					$data['linea']=$linea;

		$data['form']=site_url('tabel_waktu2');
		$data['form2']=site_url('tabel/excel_waktu2');
		if($this->input->post('line'))
		{
			$lines='';
			$linea=array();

			foreach($this->input->post('line') as $line)
			{
				$data['line_selected']=$line;
				$lines.=$data['line_selected'].',';
				array_push($linea,$line);
			}
					$data['lines']=rtrim($lines,',');
					$data['linea']=$linea;

		}
		else
		{
			$data['line_selected']='';
		}
		
		$this->load->view('sidemenu',$data);
		//$this->load->view('v_dd',$data);
	}
	
	function excel_waktu2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		if($this->input->post('line'))
		{
			$lines='';
			$linea=array();

			foreach($this->input->post('line') as $line)
			{
				$lines.=$line.',';
			}
				$lines=rtrim($lines,',');
	 	}
		if($lines!='')
		{
			$num=$this->Transaksi_model->lama_waktu_akses($lines)->num_rows();
			if($num>0)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'No');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'Produk Line');
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, 1,'Nama Produk');
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, 1,'Risetman');
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, 1,'Tanggal Awal Riset');
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, 1,'Lama Waktu Riset');
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, 1,'Lama Waktu Efektif Riset');
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, 1,'Tanggal Awal Konsep');
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, 1,'Lama Waktu Konsep');
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, 1,'Terakhir Panelis');
				$object->getActiveSheet()->setCellValueByColumnAndRow(10, 1,'Lama Waktu Terakhir Panelis');
				$object->getActiveSheet()->setCellValueByColumnAndRow(11, 1,'Terakhir Panelis Real');
				$object->getActiveSheet()->setCellValueByColumnAndRow(12, 1,'Lama Waktu Terakhir Panelis Real');
				$object->getActiveSheet()->setCellValueByColumnAndRow(13, 1,'Status');
				$object->getActiveSheet()->setCellValueByColumnAndRow(14, 1,'Tanggal Status');
				$object->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A1:O1')->applyFromArray(array(
					'font'  => array(
						'bold'  => true,
						'color' => array('rgb' => '000000')),
						)
						);
				$object->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setWrapText(true);
				//$object->getActiveSheet()->getColumnDimension('A')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(65);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('F')->setWidth(25);
				$object->getActiveSheet()->getColumnDimension('G')->setWidth(25);
				$object->getActiveSheet()->getColumnDimension('H')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('I')->setWidth(25);
				$object->getActiveSheet()->getColumnDimension('J')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('K')->setWidth(25);
				
				$object->getActiveSheet()->getColumnDimension('L')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('M')->setWidth(25);
				$object->getActiveSheet()->getColumnDimension('N')->setWidth(25);
				$object->getActiveSheet()->getColumnDimension('O')->setWidth(18);
				$ro=$this->Transaksi_model->lama_waktu_akses($lines)->result();
				$row=1;
				foreach($ro as $dt)
				{
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$row-1);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->lineproduk);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$dt->nama_item);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $row,$dt->risetman);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $row,date('d-m-Y',strtotime($dt->awal_riset)));
					$hari_ini = date("Y-m-d");
					if($dt->status==0)
					{
						$status="Progress";
						$akhir=date("Y-m-d");
					}
					else if($dt->status==-1)
					{
						$status="Terminate";
						$akhir=date("Y-m-d",strtotime($dt->tgl_status));
					}
					else if($dt->status==1)
					{
						$status="Launching";
						$akhir=date("Y-m-d",strtotime($dt->tgl_status));
					}
					else if($dt->status==2)
					{
						$status="Bank Produk-ACC";
						$akhir=date("Y-m-d",strtotime($dt->tgl_status));
					}
					$selisih=(strtotime($akhir)-strtotime($dt->awal_riset))/3600/24;
					
					$tahun=floor($selisih/365);
					$sisa=$selisih-($tahun*365);
					$bulan=floor($sisa/30);
					$tgl_awal=date('d',strtotime($dt->awal_riset));
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
					$tgl_terakhir = date('Y-m-d', strtotime($akhir));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$hari=$sisa-($bulan*30)-1;
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $row,$tahun.' tahun '.$bulan.' bulan '.$hari.' hari');
					if($selisih>365)
					{
						$font="red";
						$object->getActiveSheet()->getCellByColumnAndRow(5,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => 'FF0000')),
							)
							);
					}
					else
					{
						$font="";
						$object->getActiveSheet()->getCellByColumnAndRow(5,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => '000000')),
							)
							);
					}
					
					$pnd=$this->Transaksi_model->hari_pending($dt->id)->row();
					if(count($pnd)>0)
					{
						$pend=$pnd->totalp;
					}
					else
					{
						$pend=0;
					}
					$tanggal  = strtotime($dt->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$total_hari=($total/(3600*24))-$pend;
					if($total_hari>365)
					{
						$font="red";
					}
					else
					{
						$font="";
					}
					$tahun=floor($total_hari/365);
					$sisa=$total_hari-($tahun* 365);
					$bulan=floor($sisa/(30));
					$sisa=$sisa-($bulan*30);
					$hari=$sisa-1;
					$object->getActiveSheet()->setCellValueByColumnAndRow(6, $row,$tahun.' tahun '.$bulan.' bulan '.$hari.' hari');
					if($selisih>365)
					{
						$font="red";
						$object->getActiveSheet()->getCellByColumnAndRow(6,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => 'FF0000')),
							)
							);
					}
					else
					{
						$font="";
						$object->getActiveSheet()->getCellByColumnAndRow(6,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => '000000')),
							)
							);
					}
					
					
					$ak=$this->Transaksi_model->awal_konsep($dt->id)->row();
					$object->getActiveSheet()->setCellValueByColumnAndRow(7, $row,$ak->awal_riset);
				
					$konsep_awal=$ak->awal_riset;
					$selisih=(strtotime($akhir)-strtotime($konsep_awal))/3600/24;
					$tahun=floor($selisih/365);
					$sisa=$selisih-($tahun*365);
					$bulan=floor($sisa/30);
					$tgl_awal=date('d',strtotime($dt->awal_riset));
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
					$tgl_terakhir = date('Y-m-d', strtotime($akhir));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValueByColumnAndRow(8, $row,$tahun.' tahun '.$bulan.' bulan '.$hari.' hari');
					if($selisih>365)
					{
						$font="red";
						$object->getActiveSheet()->getCellByColumnAndRow(8,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => 'FF0000')),
							)
							);
					}
					else
					{
						$font="";
						$object->getActiveSheet()->getCellByColumnAndRow(8,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => '000000')),
							)
							);
					}
					if($dt->tgl_panelis!='')
					{
						$tgl_panelis=date('d-m-Y',strtotime($dt->tgl_panelis));
						$object->getActiveSheet()->setCellValueByColumnAndRow(9, $row,$tgl_panelis);
					$hari_ini = date("Y-m-d");
					$selisih=(strtotime($hari_ini)-strtotime($tgl_panelis))/3600/24;
					if($selisih>365)
					{
						$font="red";
					}
					else
					{
						$font="";
					}
					$tahun=floor($selisih/365);
					$sisa=$selisih-($tahun*365);
					$bulan=floor($sisa/30);
					$tgl_awal=date('d',strtotime($tgl_panelis));
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
					$tgl_terakhir = date('Y-m-d', strtotime($akhir));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValueByColumnAndRow(10, $row,$tahun.' tahun '.$bulan.' bulan '.$hari.' hari');
					if($selisih>365)
					{
						$font="red";
						$object->getActiveSheet()->getCellByColumnAndRow(10,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => 'FF0000')),
							)
							);
					}
					else
					{
						$font="";
						$object->getActiveSheet()->getCellByColumnAndRow(10,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => '000000')),
							)
							);
					}
					}
					else
					{
						$tgl_panelis='';
					}
					
					if($dt->tgl_real!='')
					{
						$tgl_real=date('d-m-Y',strtotime($dt->tgl_real));
						$object->getActiveSheet()->setCellValueByColumnAndRow(11, $row,$tgl_real);
					$selisih=(strtotime($akhir)-strtotime($tgl_real))/3600/24;
					if($selisih>365)
					{
						$font="red";
					}
					else
					{
						$font="";
					}
					$tahun=floor($selisih/365);
					$sisa=$selisih-($tahun*365);
					$bulan=floor($sisa/30);
					$tgl_awal=date('d',strtotime($tgl_real));
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
					$tgl_terakhir = date('Y-m-d', strtotime($akhir));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValueByColumnAndRow(12, $row,$tahun.' tahun '.$bulan.' bulan '.$hari.' hari');
					if($selisih>365)
					{
						$font="red";
						$object->getActiveSheet()->getCellByColumnAndRow(12,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => 'FF0000')),
							)
							);
					}
					else
					{
						$font="";
						$object->getActiveSheet()->getCellByColumnAndRow(12,$row)->getStyle()->applyFromArray(array(
						'font'  => array(
							'color' => array('rgb' => '000000')),
							)
							);
					}
					}
					else
					{
						$tgl_real='';
					}
					$object->getActiveSheet()->setCellValueByColumnAndRow(13, $row,$status);
					$object->getActiveSheet()->setCellValueByColumnAndRow(14, $row,$akhir);
				}
				$object->getActiveSheet()->getStyle('A1:O'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			}
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Lama Waktu.xls"');
		$object_writer->save('php://output');
	}
	
	
	function tabel_waktu_dtl2($id)
	{
		$this->cek_login();
		$id_item=$id;
		//$arr=explode('-',$id);
		//$id_item=$arr[0];
		//$linea=array();
		//$line=$arr[1];
		//$lines=explode('_',$line);
	//	array_push($linea,$lines);
		$data['main_view']='v_tabel_waktu_dtl2';
		$data['item']=$this->Master_model->get_produk()->result();
		$data['item_selected']=$id_item;
		$data['items']=$id_item;
		$data['panelis_selected']=3;
		$data['form']=site_url('tabel/excel_waktu_dtl2');
		$data['ke']=3;
		//$data['line']=$linea;
		
		$this->load->view('sidemenu',$data);
	}
	
	function excel_waktu_dtl2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$items=$this->input->post('item');
		$ke=$this->input->post('panelis');

		$num=$this->Transaksi_model->penilaian_all($items)->num_rows();
		if($num>0)
		{
			$jdl=$this->Transaksi_model->resume_item($items)->row();
			$jdl2=$this->Transaksi_model->lama_waktu2($items,3)->row();
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Produk Line');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 1,$jdl->lineproduk);
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 2,'Nama Produk');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 2,$jdl->nama_item);
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Awal Riset');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,date('d-m-Y',strtotime($jdl->awal_riset)));
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 4,'Risetman');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 4,$jdl->risetman);
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5,'Target Riset');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5,$jdl->kompetitor);
			$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 6,'Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 6,$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 7,'Lama Waktu Panelis Terakhir');
				$tanggal  = strtotime($jdl2->tgl_panelis);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 7,$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 9,'Var');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, 9,'Subvar');
			$object->getActiveSheet()->getCellByColumnAndRow(0,9)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(0,9)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(0,9)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow(1,9)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(1,9)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(1,9)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$hdk=$this->Transaksi_model->tabel_kode_kompetitor($items)->result();
			$hd=$this->Transaksi_model->tabel_kode($items,$ke)->result();
			$col=1;
			foreach($hdk as $hd2)
			{
				$col++;
				$object->getActiveSheet()->setCellValueByColumnAndRow($col, 9,$hd2->kode."\n".date('d-m-Y',strtotime($hd2->tanggal)));
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			}
			foreach($hd as $hd)
			{
				$col++;
				$object->getActiveSheet()->setCellValueByColumnAndRow($col, 9,$hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow($col,9)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				
			}
			$listed=$this->Transaksi_model->penilaian_all($items)->result();
			$row=9;
			$var1='';
			$var2='';
			$mer=array();
			foreach($listed as $list)
			{
				$var2=$list->varr;
				if($var1!=$var2)
				{
					$sv=0;
				}
				$sv++;
				$mer[$var2]=$sv;
				$var1=$var2;
			}
			$var1='';
			$var2='';
			foreach($listed as $list)
			{
				$row++;
				$var2=$list->varr;
				if($var1!=$var2)
				{
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$list->varr);$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$list->varr);
				}
				$var1=$var2;
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$list->subvar);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				$nilai1=0;
				$nilai2=0;
				$hdr=$this->Transaksi_model->tabel_kode($items,$ke)->result();
				$k=0;
				$col=1;
				foreach($hdk as $hd)
				{
					$col++;
					$k++;
					$num=$this->Transaksi_model->tabel_avg_param_kompetitor($items,$list->subvar,$hd->kode,$hd->tanggal)->num_rows();
						if($num>0)
						{
							$a=$this->Transaksi_model->tabel_avg_param_kompetitor($items,$list->subvar,$hd->kode,$hd->tanggal)->row();
							$nilai2=round($a->nilai,2);
						}
						else
						{
							$nilai2=0;
						}
						
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai2);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				}
				foreach($hdr as $hd)
				{
					$col++;
					$k++;
					$num=$this->Transaksi_model->tabel_avg_param($items,$ke,$list->subvar,$hd->kode,$hd->tanggal)->num_rows();
						if($num>0)
						{
							$a=$this->Transaksi_model->tabel_avg_param($items,$ke,$list->subvar,$hd->kode,$hd->tanggal)->row();
							$nilai2=round($a->nilai,2);
						}
						else
						{
							$nilai2=0;
						}
						if($nilai2>$nilai1)
						{
							$font="036635";
							$nilai=$nilai2;
							$bgcolor="00ff00";
						}
						else if($nilai2<$nilai1)
						{
							$font="ff0000";
							$nilai=$nilai2;
							$bgcolor="ffc0cb";
						}
						else if($nilai2==0)
						{
							$font="ff0000";
							$nilai=$nilai2;
							$bgcolor="ffc0cb";
						}
						else
						{
							$font="000000";
							$nilai=$nilai2;
							$bgcolor="ffff00";
						}
						
						if($k==1)
						{
							$font="000000";
							$nilai=$nilai2;
							$bgcolor="ffffff";
						}
						$nilai1=$nilai2;
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				}
			}
		
			$col+=3;
			foreach($hdk as $dt)
			{
					
					$listf=$this->Transaksi_model->tabel_dtl2_kompetitor($dt->id_formula,$dt->tanggal)->result();
					$row=16;
					foreach($listf as $listf)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 9, 'Nama Kompetitor');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 9, $listf->nama);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10, 'Tujuan');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 10, $listf->tujuan);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 15, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 15, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, 15, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, 15, 'Nilai');
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, 15, 'Keterangan');
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $listf->panelis);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $listf->varr);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $listf->subvar);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, round($listf->nilai,2));
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $listf->keterangan);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$row++;
					}
					
					$col+=7;
				}
			foreach($hdr as $dt)
			{
					
					$listedf=$this->Transaksi_model->tabel_dtl2($items,$ke,$dt->kode,$dt->tanggal)->result();
					$row=16;
					$panelis1='';
					$panelis2='';
					$var1='';
					$var2='';
					$mer=array();
					foreach($listedf as $listf)
					{
						$panelis2=$listf->panelis;
						if($panelis1!=$panelis2)
						{
							$pan=0;
						}
						$pan++;
						$mer[$panelis2]=$pan;
						$panelis1=$panelis2;

						$var2=$listf->varr;
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
					foreach($listedf as $listf)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 9, 'Formula');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 9, $listf->kode);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10, 'Tanggal  Riset');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 10, date('d-m-Y',strtotime($listf->tgl_riset)));
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 11, 'Risetman');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 11, $listf->risetman_hdr);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 12, 'Formula By');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 12, $listf->risetman);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 13, 'Tujuan');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 13, $listf->tujuan);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 15, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 15, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, 15, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, 15, 'Nilai');
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, 15, 'Keterangan');
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$panelis2=$listf->panelis;
						if($panelis1!=$panelis2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+$mer[$panelis2]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $listf->panelis);
						}
						$panelis1=$panelis2;
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$var2=$listf->varr;
						if($var1!=$var2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+$mer[$var2]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $listf->varr);
						}
						$var1=$var2;
						

						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $listf->subvar);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, round($listf->nilai,2));
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $listf->keterangan);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$row++;
					}
					
					$col+=7;
				}
				
		
		}
		
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Lama Waktu Detail.xls"');
		$object_writer->save('php://output');
	}
	
	
	
	function tabel_waktu_dtl($id)
	{
		$this->cek_login();
		$arr=explode('-',$id);
		$id_item=$arr[0];
		$line=$arr[1];
		$data['main_view']='v_tabel_waktu_dtl';
		$data['item']=$this->Master_model->get_produk()->result();
		$data['item_selected']=$id_item;
		$data['items']=$id_item;
		$data['panelis_selected']=3;
		$data['form']=site_url('tabel_waktu');
		$data['ke']=3;
		$data['line']=$line;
		$this->load->view('sidemenu',$data);
	}
	
	
	function tabel_progress()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_progress';
		$data['line']=$this->Master_model->get_lp()->result();
		$data['line_selected']='';
		$data['form']=site_url('tabel_progress');
		if($this->input->post('line'))
		{
			$data['line_selected']=$this->input->post('line');
			
		}
		else
		{
			$data['line_selected']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function tabel_kategori()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_kategori';
		$data['kategori']=$this->Master_model->get_kategori()->result();
		$data['kategori_selected']='';
		$data['form']=site_url('tabel_kategori');
		$data['form2']=site_url('tabel/excel_kategori');
		if($this->input->post('kategori'))
		{
			$data['kategori_selected']=$this->input->post('kategori');
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			
		}
		else
		{
			$data['kategori_selected']='';
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_kategori()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$kategori_selected=$this->input->post('kategori');
		
		if($tgl_awal!='')
		{
			
			$tgl1=date('Y-m-d',strtotime($tgl_awal));
			$tgl2=date('Y-m-d',strtotime($tgl_akhir));
			
			$num=$this->Transaksi_model->rekap_bahan_produk($kategori_selected,$tgl1,$tgl2)->num_rows();
			if($num>0)
			{	
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Kode Bahan');
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'Nama Produk');
				$object->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A1:B1')->applyFromArray(array(
					'font'  => array(
						'bold'  => true,
						'color' => array('rgb' => '000000')),
						)
						);
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(15);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(150);
				$dt=$this->Transaksi_model->rekap_bahan_produk($kategori_selected,$tgl1,$tgl2)->result();
				$row=1;
				foreach($dt as $ro)
				{
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$ro->kode_bahan);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$ro->produk);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);

				}
				$object->getActiveSheet()->getStyle('A1:B'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			}
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Kategori Bahan.xls"');
		$object_writer->save('php://output');
	}
	function tabel_masalah()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_masalah';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']='';
		$data['panelis_selected']='';
		$data['form']=site_url('tabel/tabel_masalah');
		$data['form2']=site_url('tabel/excel_tabel_masalah');
		if($this->input->post('item'))
		{
			$data['items']=$this->input->post('item');
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['ke']=$this->input->post('panelis');
		}
		else
		{
			$data['items']=0;
			$data['ke']=1;
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_masalah()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$ke=$this->input->post('panelis');
		$tgl_awal=date("Y-m-d",strtotime($this->input->post('tgl_awal')));
		$tgl_akhir=date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$num=$this->Transaksi_model->sumber_masalah($item,$ke,$tgl_awal,$tgl_akhir)->num_rows();
		$dt=$this->Transaksi_model->sumber_masalah($item,$ke,$tgl_awal,$tgl_akhir)->result();

		if($num>0)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);

			$jdl=$this->Transaksi_model->resume_item($item)->row();
			$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
			$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
			$object->getActiveSheet()->setCellValue('A1','Line Produk');
			$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
			$object->getActiveSheet()->setCellValue('A2','Nama Produk');
			$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
			$object->getActiveSheet()->setCellValue('A3','Awal Riset');
			$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
			$object->getActiveSheet()->setCellValue('A4','Risetman');
			$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
			$object->getActiveSheet()->setCellValue('A5','Target Riset');
			$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
			$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->setCellValue('A6','Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B6',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
				$object->getActiveSheet()->setCellValue('A7','Lama Waktu Panelis Terakhir');
				$tanggal  = strtotime($jdl2->tgl_panelis);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B7',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
				$object->getActiveSheet()->setCellValue('A8','Panelis');
				if($ke==1)
				{
					$status="Risetman";
				}
				else if($ke==2)
				{
					$status="Internal";
				}
				else if($ke==3)
				{
					$status="Taste Specialist";
				}
				$object->getActiveSheet()->setCellValue('B8',$status);
				$object->getActiveSheet()->setCellValue('A9','Total Formula dipanelis');
				$tot=$this->Transaksi_model->formula_panelis($item,$ke)->num_rows();
				$object->getActiveSheet()->setCellValue('B9',$tot);	
								
				$row=11;
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Masalah');
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Jumlah');
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				foreach($dt as $dt)
				{
					$row++;
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->masalah);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->jum.' ('.round($dt->jum/$tot*100).' %)');
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Masalah.xls"');
		$object_writer->save('php://output');
	}
	
	function get_dtl_masalah()
	{
		$this->cek_login();
		$item=$this->input->post('id_item');
		$ke=$this->input->post('ke');
		$id_masalah=$this->input->post('id_masalah');
		$tgl_awal=date('Y-m-d',strtotime($this->input->post('tgl_awal')));
		$tgl_akhir=date('Y-m-d',strtotime($this->input->post('tgl_akhir')));
		$data=$this->Transaksi_model->dtl_masalah($item,$ke,$id_masalah,$tgl_awal,$tgl_akhir)->result();
		echo json_encode($data);
	}
	function tabel_risetman_harian()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_risetman_harian';
		$data['risetman']=$this->Master_model->get_risetman()->result();
		$data['risetman_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_risetman_harian');
		$data['form2']=site_url('tabel/excel_tabel_risetman_harian');
		if($this->input->post('risetman'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['risetman_selected']=$this->input->post('risetman');
		}
		else
		{
			$data['risetman_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function tabel_risetman_harian2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_risetman_harian2';
		$data['risetman']=$this->Master_model->get_risetman()->result();
		$data['risetman_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_risetman_harian2');
		$data['form2']=site_url('tabel/excel_tabel_risetman_harian2');
		if($this->input->post('risetman'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$risetmana=array();
			$risetmans='';
			foreach($this->input->post('risetman') as $risetman)
			{
				$risetmans.=$risetman.',';
				array_push($risetmana,$risetman);
			}
					
					$data['risetman_selected']=rtrim($risetmans,',');
					$data['risetmana']=$risetmana;
			
		}
		else
		{
			$data['risetman_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_risetman_harian()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$risetman_selected=$this->input->post('risetman');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat oleh '.$risetman_selected.' Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_harian_risetman($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_harian_risetman($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman harian.xls"');
		$object_writer->save('php://output');
	}
	function excel_tabel_risetman_harian2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		//$risetman_selected=$this->input->post('risetman');
		$risetmans='';
			foreach($this->input->post('risetman') as $risetman)
			{
				$risetmans.=$risetman.',';
			}
					
		$risetman_selected=rtrim($risetmans,',');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat oleh '.$risetman_selected.' Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_harian_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_harian_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman harian.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_produk_harian()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_produk_harian';
		$data['produk']=$this->Master_model->get_produk()->result();
		$data['produk_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_produk_harian');
		$data['form2']=site_url('tabel/excel_tabel_produk_harian');
		if($this->input->post('produk'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['produk_selected']=$this->input->post('produk');
		}
		else
		{
			$data['produk_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function tabel_produk_harian2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_produk_harian2';
		$data['produk']=$this->Master_model->get_produk()->result();
		$data['produk_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_produk_harian2');
		$data['form2']=site_url('tabel/excel_tabel_produk_harian2');
		if($this->input->post('produk'))
		{
			$produka=array();
			$produks='';
			foreach($this->input->post('produk') as $produk)
			{
				$produks.=$produk.',';
				array_push($produka,$produk);
			}
					
					$data['produks']=rtrim($produks,',');
					$data['produka']=$produka;
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
		}
		else
		{
			$data['produka']="";
			$data['produks']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_produk_harian()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$produk_selected=$this->input->post('produk');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_harian_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_harian_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Produk harian.xls"');
		$object_writer->save('php://output');
	}
	
		function excel_tabel_produk_harian2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		//$produk_selected=$this->input->post('produk');
		$produks='';
			foreach($this->input->post('produk') as $produk)
			{
				$produks.=$produk.',';
			}
					
			$produk_selected=rtrim($produks,',');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_harian_produk2($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$tgl1=$tgl_awal;
		$tgl2=$tgl_akhir;
		$col=2;
		while (strtotime($tgl1) <= strtotime($tgl2)) {		
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$tgl1);
			$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_harian_produk2($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$tgl1=$tgl_awal;
				$tgl2=$tgl_akhir;
				$col=1;
				while (strtotime($tgl1) <= strtotime($tgl2)) 
				{
					$col++;
					$tgl=date("Y-m-d", strtotime($tgl1));
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Produk harian.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_risetman_mingguan()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_risetman_mingguan';
		$data['risetman']=$this->Master_model->get_risetman()->result();
		$data['risetman_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_risetman_mingguan');
		$data['form2']=site_url('tabel/excel_tabel_risetman_mingguan');
		if($this->input->post('risetman'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['risetman_selected']=$this->input->post('risetman');
		}
		else
		{
			$data['risetman_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_risetman_mingguan()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$risetman_selected=$this->input->post('risetman');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat oleh '.$risetman_selected.' Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$week1=strftime("%U",strtotime($tgl_awal));
		$week2=strftime("%U",strtotime($tgl_akhir));
		$h=0;
		$col=2;
		for($i=$week1+1;$i<=$week2+1;$i++)
		{
			$h++;
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,'W. '.$h);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_mingguan_risetman($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$week1=strftime("%U",strtotime($tgl_awal));
				$week2=strftime("%U",strtotime($tgl_akhir));
				$col=1;
				for($i=$week1+1;$i<=$week2+1;$i++)
				{
					$col++;
					$tgl=$i;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$week1=strftime("%U",strtotime($tgl_awal));
		$week2=strftime("%U",strtotime($tgl_akhir));
		$h=0;
		$col=2;
		for($i=$week1+1;$i<=$week2+1;$i++)
		{
			$h++;	
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,'W. '.$h);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_mingguan_risetman($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$week1=strftime("%U",strtotime($tgl_awal));
				$week2=strftime("%U",strtotime($tgl_akhir));
				$col=1;
				for($i=$week1+1;$i<=$week2+1;$i++)
				{
					$tgl=$i;
					$col++;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman Mingguan.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_risetman_mingguan2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_risetman_mingguan2';
		$data['risetman']=$this->Master_model->get_risetman()->result();
		$data['risetman_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_risetman_mingguan2');
		$data['form2']=site_url('tabel/excel_tabel_risetman_mingguan2');
		if($this->input->post('risetman'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$risetmana=array();
			$risetmans='';
			foreach($this->input->post('risetman') as $risetman)
			{
				$risetmans.=$risetman.',';
				array_push($risetmana,$risetman);
			}
					
					$data['risetman_selected']=rtrim($risetmans,',');
					$data['risetmana']=$risetmana;
		}
		else
		{
			$data['risetman_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_risetman_mingguan2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$risetmans='';
			foreach($this->input->post('risetman') as $risetman)
			{
				$risetmans.=$risetman.',';
			}
					
		$risetman_selected=rtrim($risetmans,',');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat oleh '.$risetman_selected.' Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$col=2;
		$hdr=$this->Transaksi_model->hdr_mingguan_risetman2($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$k=0;
		$num=count($hdr);
		foreach($hdr as $hd)
		{
			$k++;
			$tgl=date("d-m-Y",strtotime($hd->tgl));
			if($k=1)
			{
				if(strtotime($tgl)<strtotime($tgl_awal))
				{
					$tgl=date("d-m-Y",strtotime($tgl_awal));
					
				}
			}
			
			$tgl2=date("d-m-Y",strtotime("+6 day",strtotime($hd->tgl)));
			if($k=$num)
			{
				if(strtotime($tgl2)>strtotime($tgl_akhir))
				{
					$tgl2=date("d-m-Y",strtotime($tgl_akhir));
					
				}
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$tgl);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_mingguan_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=3;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=1;
				foreach($hdr as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$jum=$dt->$tgl;
					
					if(empty($jum))
					{
						$jum=0;
					}
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}	
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$hdr2=$this->Transaksi_model->hdr_mingguan_kontribusi_risetman2($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$k=0;
		$col=2;
		foreach($hdr2 as $hd)
		{
			$k++;
			$tgl=date("d-m-Y",strtotime($hd->tgl));
			if($k=1)
			{
				if(strtotime($tgl)<strtotime($tgl_awal))
				{
					$tgl=date("d-m-Y",strtotime($tgl_awal));
					
				}
			}
			
			$tgl2=date("d-m-Y",strtotime("+6 day",strtotime($hd->tgl)));
			if($k=$num)
			{
				if(strtotime($tgl2)>strtotime($tgl_akhir))
				{
					$tgl2=date("d-m-Y",strtotime($tgl_akhir));
					
				}
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$tgl.'<br>'.$tgl2);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;			
		}
		/* $week1=strftime("%U",strtotime($tgl_awal));
		$week2=strftime("%U",strtotime($tgl_akhir));
		$h=0;
		$col=2;
		for($i=$week1+1;$i<=$week2+1;$i++)
		{
			$h++;	
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,'W. '.$h);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		} */
		$row++;
		$dt=$this->Transaksi_model->rekap_kontribusi_mingguan_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=1;
				foreach($hdr2 as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$jum=$dt->$tgl;
					
					if(empty($jum))
					{
						$jum=0;
					}
					
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
				/* $week1=strftime("%U",strtotime($tgl_awal));
				$week2=strftime("%U",strtotime($tgl_akhir));
				$col=1;
				for($i=$week1+1;$i<=$week2+1;$i++)
				{
					$tgl=$i;
					$col++;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				} */
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman Mingguan.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_produk_mingguan()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_produk_mingguan';
		$data['produk']=$this->Master_model->get_produk()->result();
		$data['produk_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_produk_mingguan');
		$data['form2']=site_url('tabel/excel_tabel_produk_mingguan');
		if($this->input->post('produk'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['produk_selected']=$this->input->post('produk');
		}
		else
		{
			$data['produk_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_produk_mingguan()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$produk_selected=$this->input->post('produk');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$week1=strftime("%U",strtotime($tgl_awal));
		$week2=strftime("%U",strtotime($tgl_akhir));
		$h=0;
		$col=2;
		for($i=$week1+1;$i<=$week2+1;$i++)
		{
			$h++;
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,'W. '.$h);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_mingguan_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$week1=strftime("%U",strtotime($tgl_awal));
				$week2=strftime("%U",strtotime($tgl_akhir));
				$col=1;
				for($i=$week1+1;$i<=$week2+1;$i++)
				{
					$col++;
					$tgl=$i;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$week1=strftime("%U",strtotime($tgl_awal));
		$week2=strftime("%U",strtotime($tgl_akhir));
		$h=0;
		$col=2;
		for($i=$week1+1;$i<=$week2+1;$i++)
		{
			$h++;	
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,'W. '.$h);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_mingguan_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$week1=strftime("%U",strtotime($tgl_awal));
				$week2=strftime("%U",strtotime($tgl_akhir));
				$col=1;
				for($i=$week1+1;$i<=$week2+1;$i++)
				{
					$tgl=$i;
					$col++;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Produk Mingguan.xls"');
		$object_writer->save('php://output');
	}
	function wardah()
	{
		echo $this->input->post('tgl_awal');
	}
	function tabel_produk_mingguan2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_produk_mingguan2';
		$data['produk']=$this->Master_model->get_produk()->result();
		$data['produk_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_produk_mingguan2');
		$data['form2']=site_url('tabel/excel_tabel_produk_mingguan2');		
		if($this->input->post('produk'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$produka=array();
			$produks='';
			foreach($this->input->post('produk') as $produk)
			{
				$produks.=$produk.',';
				array_push($produka,$produk);
			}
			$data['produk_selected']=rtrim($produks,',');
			$data['produka']=$produka;
		}
		else
		{
			$data['produk_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_produk_mingguan2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$produk_selected=$this->input->post('produk');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$produks='';
		foreach($this->input->post('produk') as $produk)
			{
				$produks.=$produk.',';
			}
					
			$produk_selected=rtrim($produks,',');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$hdr=$this->Transaksi_model->hdr_mingguan_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$col=2;
		$k=0;
		$num=count($hdr);
		foreach($hdr as $hd)
		{
			$k++;
			$tgl=date("d-m-Y",strtotime($hd->tgl));
			if($k=1)
			{
				if(strtotime($tgl)<strtotime($tgl_awal))
				{
					$tgl=date("d-m-Y",strtotime($tgl_awal));
					
				}
			}
			
			$tgl2=date("d-m-Y",strtotime("+6 day",strtotime($hd->tgl)));
			if($k=$num)
			{
				if(strtotime($tgl2)>strtotime($tgl_akhir))
				{
					$tgl2=date("d-m-Y",strtotime($tgl_akhir));
					
				}
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$tgl."\n".$tgl2);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
			
		}
		
		$dt=$this->Transaksi_model->rekap_mingguan_produk2($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=3;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=1;
				foreach($hdr as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$jum=$dt->$tgl;
					
					if(empty($jum))
					{
						$jum=0;
					}
					
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					
				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$k=0;
		$num=count($hdr);
		$col=2;
		foreach($hdr as $hd)
		{
			$k++;
			$tgl=date("d-m-Y",strtotime($hd->tgl));
			if($k=1)
			{
				if(strtotime($tgl)<strtotime($tgl_awal))
				{
					$tgl=date("d-m-Y",strtotime($tgl_awal));
					
				}
			}
			
			$tgl2=date("d-m-Y",strtotime("+6 day",strtotime($hd->tgl)));
			if($k=$num)
			{
				if(strtotime($tgl2)>strtotime($tgl_akhir))
				{
					$tgl2=date("d-m-Y",strtotime($tgl_akhir));
					
				}
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$tgl."\n".$tgl2);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
			
		}
		
		$dt=$this->Transaksi_model->rekap_kontribusi_mingguan_produk2($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=1;
				foreach($hdr as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$jum=$dt->$tgl;
					
					if(empty($jum))
					{
						$jum=0;
					}
					
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					
				}
			
			}
			
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Produk Mingguan.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_risetman_bulanan()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_risetman_bulanan';
		$data['risetman']=$this->Master_model->get_risetman()->result();
		$data['risetman_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_risetman_bulanan');
		$data['form2']=site_url('tabel/excel_tabel_risetman_bulanan');
		if($this->input->post('risetman'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['risetman_selected']=$this->input->post('risetman');
		}
		else
		{
			$data['risetman_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_risetman_bulanan()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$risetman_selected=$this->input->post('risetman');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat oleh '.$risetman_selected.' Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$month1=date("n",strtotime($tgl_awal));
		$month2=date("n",strtotime($tgl_akhir));
		$col=2;
		for($i=$month1;$i<=$month2;$i++)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,'M. '.$i);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_bulanan_risetman($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$month1=date("n",strtotime($tgl_awal));
				$month2=date("n",strtotime($tgl_akhir));
				$col=1;
				for($i=$month1;$i<=$month2;$i++)
				{
					$col++;
					$tgl=$i;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$month1=date("n",strtotime($tgl_awal));
		$month2=date("n",strtotime($tgl_akhir));
		$h=0;
		$col=2;
		for($i=$month1;$i<=$month2;$i++)
		{
			$h++;	
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,'M. '.$i);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_bulanan_risetman($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$month1=date("n",strtotime($tgl_awal));
				$month2=date("n",strtotime($tgl_akhir));
				$col=1;
				for($i=$month1;$i<=$month2;$i++)
				{
					$tgl=$i;
					$col++;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman Mingguan.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_risetman_bulanan2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_risetman_bulanan2';
		$data['risetman']=$this->Master_model->get_risetman()->result();
		$data['risetman_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_risetman_bulanan2');
		$data['form2']=site_url('tabel/excel_tabel_risetman_bulanan2');
		if($this->input->post('risetman'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$risetmana=array();
			$risetmans='';
			foreach($this->input->post('risetman') as $risetman)
			{
				$risetmans.=$risetman.',';
				array_push($risetmana,$risetman);
			}
					
			$data['risetman_selected']=rtrim($risetmans,',');
			$data['risetmana']=$risetmana;
		}
		else
		{
			$data['risetman_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_risetman_bulanan2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		//$risetman_selected=$this->input->post('risetman');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$risetmans='';
			foreach($this->input->post('risetman') as $risetman)
			{
				$risetmans.=$risetman.',';
			}
					
		$risetman_selected=rtrim($risetmans,',');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat oleh '.$risetman_selected.' Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$col=2;
		$hdr=$this->Transaksi_model->hdr_bulanan_risetman2($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($hdr as $hd)
		{
			$mnt=date("n",strtotime($hd->tgl));
			$yr=date("Y",strtotime($hd->tgl));
			$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
			
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$bulan[$mnt].' '.$yr);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_bulanan_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=3;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=1;
				foreach($hdr as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$dt->$tgl);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$col=2;
		$hdr2=$this->Transaksi_model->hdr_bulanan_kontribusi_risetman2($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($hdr2 as $hd)
		{
			$mnt=date("n",strtotime($hd->tgl));
			$yr=date("Y",strtotime($hd->tgl));
			$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$bulan[$mnt].' '.$yr);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
			
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_bulanan_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=1;
				foreach($hdr2 as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$dt->$tgl);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Risetman Bulanan.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_produk_bulanan()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_produk_bulanan';
		$data['produk']=$this->Master_model->get_produk()->result();
		$data['produk_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_produk_bulanan');
		$data['form2']=site_url('tabel/excel_tabel_produk_bulanan');
		if($this->input->post('produk'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['produk_selected']=$this->input->post('produk');
		}
		else
		{
			$data['produk_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		//$this->load->view('sidemenu',$data);
	}
	function excel_tabel_produk_bulanan()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$produk_selected=$this->input->post('produk');
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$month1=date("n",strtotime($tgl_awal));
		$month2=date("n",strtotime($tgl_akhir));
		$col=2;
		for($i=$month1;$i<=$month2;$i++)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,'M. '.$i);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		
		$dt=$this->Transaksi_model->rekap_bulanan_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=2;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$month1=date("n",strtotime($tgl_awal));
				$month2=date("n",strtotime($tgl_akhir));
				$col=1;
				for($i=$month1;$i<=$month2;$i++)
				{
					$col++;
					$tgl=$i;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$month1=date("n",strtotime($tgl_awal));
		$month2=date("n",strtotime($tgl_akhir));
		$col=2;
		for($i=$month1;$i<=$month2;$i++)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,'M. '.$i);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_bulanan_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$month1=date("n",strtotime($tgl_awal));
				$month2=date("n",strtotime($tgl_akhir));
				$col=1;
				for($i=$month1;$i<=$month2;$i++)
				{
					$tgl=$i;
					$col++;
					if(empty($dt->$tgl))
					{
						$jum=0;
					}
					else
					{
						$jum=$dt->$tgl;
					}				
						
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$jum);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			$row++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Produk Bulanan.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_produk_bulanan2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_produk_bulanan2';
		$data['produk']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['produk_selected']='';
		$data['tgl_awal']='';
		$data['form']=site_url('tabel/tabel_produk_bulanan2');
		$data['form2']=site_url('tabel/excel_tabel_produk_bulanan2');
		if($this->input->post('produk'))
		{
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$produka=array();
			$produks='';
			
			foreach($this->input->post('produk') as $produk)
			{
				$produks.=$produk.',';
				array_push($produka,$produk);
			}
					
			$data['produk_selected']=rtrim($produks,',');
			$data['produka']=$produka;
		}
		else
		{
			$data['produk_selected']="";
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_produk_bulanan2()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$tgl_awal=$this->input->post('tgl_awal');
		$tgl_akhir=$this->input->post('tgl_akhir');
		$produks='';
		foreach($this->input->post('produk') as $produk)
		{
			$produks.=$produk.',';
		}
				
		$produk_selected=rtrim($produks,',');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Formula dibuat Tanggal '.$tgl_awal.' s/d '.$tgl_akhir);
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, 3,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, 3,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$hdr=$this->Transaksi_model->hdr_bulanan_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$col=2;
		foreach($hdr as $hd)
		{
			$mnt=date("n",strtotime($hd->tgl));
			$yr=date("Y",strtotime($hd->tgl));
			$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, 3,$bulan[$mnt].' '.$yr);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,3)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_bulanan_produk2($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		$row=3;
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman_hdr))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman_hdr);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				
				$col=1;
				foreach($hdr as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$id="'".$tgl."'";
					
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$dt->$tgl);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
		}
		
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Kontribusi');
		$row+=2;
		$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Risetman');
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Item');
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$col=2;
		foreach($hdr as $hd)
		{
			$mnt=date("n",strtotime($hd->tgl));
			$yr=date("Y",strtotime($hd->tgl));
			$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
			$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$bulan[$mnt].' '.$yr);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$col++;
		}
		$dt=$this->Transaksi_model->rekap_kontribusi_bulanan_produk2($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
		foreach($dt as $dt)
		{
			$row++;
			if(!empty($dt->risetman))
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->risetman);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->nama_item);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$col=1;
				foreach($hdr as $hd)
				{
					$col++;
					$tgl=$hd->tgl;
					$id="'".$tgl."'";
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$dt->$tgl);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Produk Bulanan.xls"');
		$object_writer->save('php://output');
	}
	
	
	function get_dtl_konsep()
	{
		$this->cek_login();
		$id=$this->input->post('id');
		$data=$this->Transaksi_model->konsep_sebelumnya($id)->result();
		echo json_encode($data);
	}
	function tabel_vs()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_vs';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']=0;
		$data['panelis_selected']=1;
		$data['type']=1;
		$data['form']=site_url('tabel/tabel_vs');
		$data['form2']=site_url('tabel/excel_tabel_vs');
		if($this->input->post('item'))
		{
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['type']=$this->input->post('type');
			$data['kode1']=$this->input->post('kode1');
			$data['kode2']=$this->input->post('kode2');
			$data['kode3']=$this->input->post('kode3');
			$data['kode4']=$this->input->post('kode4');
			$data['kode5']=$this->input->post('kode5');
			
		}
		else
		{
			$data['kode1']=0;
			$data['kode2']=0;
			$data['kode3']=0;
			$data['kode4']=0;
			$data['kode5']=0;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_vs()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$ke=$this->input->post('panelis');
		$type=$this->input->post('type');
		$kode1=$this->input->post('kode1');
		$kode2=$this->input->post('kode2');
		$kode3=$this->input->post('kode3');
		$kode4=$this->input->post('kode4');
		$kode5=$this->input->post('kode5');
		if($ke==0)
		{
			$ke=1;
			$panelis_selected=0;
		}
		else
		{
			$panelis_selected=$ke;
		}
							
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		if($type==1)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$num=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->num_rows();
			if($num>0)
			{
					$jdl=$this->Transaksi_model->resume_item($item)->row();
					$jdl2=$this->Transaksi_model->lama_waktu2($item,$panelis_selected)->row();
					$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
					$object->getActiveSheet()->setCellValue('A1','Line Produk');
					$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
					$object->getActiveSheet()->setCellValue('A2','Nama Produk');
					$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
					$object->getActiveSheet()->setCellValue('A3','Awal Riset');
					$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
					$object->getActiveSheet()->setCellValue('A4','Risetman');
					$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
					$object->getActiveSheet()->setCellValue('A5','Target Riset');
					$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
					$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

					$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
					$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
					$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
					$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
					if($jdl->nama_konsep_sebelumnya!='')
					{
						$object->getActiveSheet()->setCellValue('A8','Referensi');
						$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
					}
					$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
						$tanggal  = strtotime($jdl->awal_riset);
						$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
						$total   = $sekarang - $tanggal;
						$tahun=floor($total/(60 * 60 * 24 * 365));
						$sisa=$total-($tahun*(60 * 60 * 24 * 365));
						$bulan=floor($sisa/(60 * 60 * 24 * 30));
						$hari_ini = date("Y-m-d");
						$tgl_awal=date('d',$tanggal);
						$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
						$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
						$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
						$hari=$hari-1;
						$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
						$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
						$tanggal  = strtotime($jdl2->tgl_panelis);
						$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
						$total   = $sekarang - $tanggal;
						$tahun=floor($total/(60 * 60 * 24 * 365));
						$sisa=$total-($tahun*(60 * 60 * 24 * 365));
						$bulan=floor($sisa/(60 * 60 * 24 * 30));
						$hari_ini = date("Y-m-d");
						$tgl_awal=date('d',$tanggal);
						$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
						$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
						$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
						$hari=$hari-1;
						$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
						$object->getActiveSheet()->setCellValue('A11','Total Formula');
						$object->getActiveSheet()->setCellValue('B11',$total_formula);
						$object->getActiveSheet()->setCellValue('A12','Sember Formula');
						$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
						$row=13;
						foreach($formulaby as $fb)
						{
							$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
							$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
							$row++;
						}
						if($panelis_selected==0)
						{
							for($ke=1;$ke<=3;$ke++)
							{
								$hdr=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							if(count($hdr)>0)
							{
								$row+=2;
								if($ke==1)
								{
									$pan='Risetman';
								}
								else if($ke==2)
								{
									$pan='Internal';
								}
								else if($ke==3)
								{
									$pan='Taste Spesialist';
								}
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => '#000000'),
									'size' => 16
									),
									
									)
									);
								
								$row++;
								
								
								$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
								
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								
								foreach($hdr as $hd)
								{
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								
								$data=$this->Transaksi_model->nilai_kode_vs($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$sv=0;
									}
									$sv++;
									$var[$dt->varr]=$sv;
									$var1=$var2;
									
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$pan=0;
									}
									$pan++;
									$var[$dt->panelis]=$pan;
									$panelis1=$panelis2;
								}
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
										$mer=$row+$var[$dt->panelis]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
										$mer=$row+$var[$dt->varr]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
									}
									$var1=$var2;
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$nilai1=0;
									$nilai2=0;
									$k=0;
									$col=3;
									foreach($hdr as $hd)
									{
										
										$k++;
										$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
										$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
										$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
										$nilai2=round((float)$dt->$vnilai,2);
										$skala2=round((float)$dt->$skala,2);
										if($nilai2>$nilai1)
										{
											$tanda="+";
										}
										else if($nilai2<$nilai1)
										{
											$tanda="-";
										}
										else
										{
											$tanda="";
										}
										if($k==1)
										{
											$tanda="";
										}
										
										if($nilai2<=70.9)
										{
											
											$font="ff0000";
											$nilai=$nilai2;
											$bgcolor="ffc0cb";
										}
										else if(71<=$nilai2 and $nilai2<73)
										{
											$font="000000";
											$nilai=$nilai2;
											$bgcolor="ffff00";
										}
										else if($nilai2==73)
										{
											$font="364522";
											$nilai=$nilai2;
											$bgcolor="00ff00";
										}
										else if($nilai2>73)
										{
											$font="ffffff";
											$nilai=$nilai2;
											$bgcolor="0000ff";
										}
										
										
										$nilai1=$nilai2;
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
										'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => $bgcolor)),
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => $font)),
											)
											);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col+=3;
									}					
									$row++;
								}
								if($ke==3)
								{						 
									$col=0;
									$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
									if($numk>0)
									{
										$total=($num*3)+3;
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
									$row++;
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									foreach($kes_hdr as $kes_hdr)
									{
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdr as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$kesimpulan=$kes->kesimpulan;
											}
											else
											{
												$kesimpulan="";
											}
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
											
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
										}
										
										$row++;
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdr as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$saran=$kes->saran;
											}
											else
											{
												$saran="";
											}
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
											
										}
										$row++;
									}
									}
								}
								else
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
									$col=3;
									foreach($hdr as $hd)
									{
										$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
										if($numkes>0)
										{
											$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									$row++;
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
									if($numsm>0)
									{
										$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									
									$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
									if($num_desc_sm>0)
									{
									$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
									$desc_sm=$desc_sm->deskripsi;
									}
									else
									{
										$desc_sm="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numac>0)
									{
										$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$action=$ac->action_plan;
									}
									else
									{
										$action="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
							}
							
							}
						}
						else
						{
								$hdr=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
								$row++;
								
								
								$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
								
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								
								foreach($hdr as $hd)
								{
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								
								$data=$this->Transaksi_model->nilai_kode_vs($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$sv=0;
									}
									$sv++;
									$var[$dt->varr]=$sv;
									$var1=$var2;
									
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$pan=0;
									}
									$pan++;
									$var[$dt->panelis]=$pan;
									$panelis1=$panelis2;
								}
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
										$mer=$row+$var[$dt->panelis]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
										$mer=$row+$var[$dt->varr]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
									}
									$var1=$var2;
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$nilai1=0;
									$nilai2=0;
									$k=0;
									$col=3;
									foreach($hdr as $hd)
									{
										
										$k++;
										$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
										$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
										$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
										$nilai2=round((float)$dt->$vnilai,2);
										$skala2=round((float)$dt->$skala,2);
										if($nilai2>$nilai1)
										{
											$tanda="+";
										}
										else if($nilai2<$nilai1)
										{
											$tanda="-";
										}
										else
										{
											$tanda="";
										}
										if($k==1)
										{
											$tanda="";
										}
										
										if($nilai2<=70.9)
										{
											
											$font="ff0000";
											$nilai=$nilai2;
											$bgcolor="ffc0cb";
										}
										else if(71<=$nilai2 and $nilai2<73)
										{
											$font="000000";
											$nilai=$nilai2;
											$bgcolor="ffff00";
										}
										else if($nilai2==73)
										{
											$font="364522";
											$nilai=$nilai2;
											$bgcolor="00ff00";
										}
										else if($nilai2>73)
										{
											$font="ffffff";
											$nilai=$nilai2;
											$bgcolor="0000ff";
										}
										
										
										$nilai1=$nilai2;
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
										'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => $bgcolor)),
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => $font)),
											)
											);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col+=3;
									}					
									$row++;
								}
								if($ke==3)
								{						 
									$col=0;
									$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
									if($numk>0)
									{
										$total=($num*3)+3;
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
									$row++;
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									foreach($kes_hdr as $kes_hdr)
									{
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdr as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$kesimpulan=$kes->kesimpulan;
											}
											else
											{
												$kesimpulan="";
											}
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
											
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
										}
										
										$row++;
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdr as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$saran=$kes->saran;
											}
											else
											{
												$saran="";
											}
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
											
										}
										$row++;
									}
									}
								}
								else
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
									$col=3;
									foreach($hdr as $hd)
									{
										$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
										if($numkes>0)
										{
											$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									$row++;
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
									if($numsm>0)
									{
										$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									
									$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
									if($num_desc_sm>0)
									{
									$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
									$desc_sm=$desc_sm->deskripsi;
									}
									else
									{
										$desc_sm="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numac>0)
									{
										$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$action=$ac->action_plan;
									}
									else
									{
										$action="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}

						}
		
			}
			
		
		}
		else
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(36);
			$num=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->num_rows();
			if($num>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$panelis_selected)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					if($panelis_selected==0)
					{
						
						for($ke=1;$ke<=3;$ke++)
						{
							
						$hdr=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						if(count($hdr)>0)
						{
							$row+=2;
							if($ke==1)
							{
								$pan='Risetman';
							}
							else if($ke==2)
							{
								$pan='Internal';
							}
							else if($ke==3)
							{
								$pan='Taste Spesialist';
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => '#000000'),
								'size' => 16
								),
								
								)
								);
							
							
							$row++;
							$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Var');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Subvar');
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Panelis');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							
							foreach($hdr as $hd)
							{
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							$row++;
							
							$data=$this->Transaksi_model->nilai_kode_vs2($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							$var1='';
							$var2='';
							$subvar1='';
							$subvar2='';
							foreach($data as $dt)
							{
								$var2=$dt->varr;
								if($var2!=$var1)
								{
									$sv=0;
								}
								$sv++;
								$var[$dt->varr]=$sv;
								$var1=$var2;
								
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									$pan=0;
								}
								$pan++;
								$var[$dt->subvar]=$pan;
								$subvar1=$subvar2;
							}
							$var1='';
							$var2='';
							$subvar1='';
							$subvar2='';
							foreach($data as $dt)
							{
							
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$dt->panelis);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$var2=$dt->varr;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
									$mer=$row+$var[$dt->varr]-1;
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
								}
								$var1=$var2;
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
									$mer=$row+$var[$dt->subvar]-1;
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
								}
								$subvar1=$subvar2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$nilai1=0;
								$nilai2=0;
								$k=0;
								$col=3;
								foreach($hdr as $hd)
								{
									
									$k++;
									$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
									$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
									$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
									$nilai2=round((float)$dt->$vnilai,2);
									$skala2=round((float)$dt->$skala,2);
									if($nilai2>$nilai1)
									{
										$tanda="+";
									}
									else if($nilai2<$nilai1)
									{
										$tanda="-";
									}
									else
									{
										$tanda="";
									}
									if($k==1)
									{
										$tanda="";
									}
									
									if($nilai2<=70.9)
									{
										
										$font="ff0000";
										$nilai=$nilai2;
										$bgcolor="ffc0cb";
									}
									else if(71<=$nilai2 and $nilai2<73)
									{
										$font="000000";
										$nilai=$nilai2;
										$bgcolor="ffff00";
									}
									else if($nilai2==73)
									{
										$font="364522";
										$nilai=$nilai2;
										$bgcolor="00ff00";
									}
									else if($nilai2>73)
									{
										$font="ffffff";
										$nilai=$nilai2;
										$bgcolor="0000ff";
									}
									
									
									$nilai1=$nilai2;
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
									'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $bgcolor)),
									'font'  => array(
										'bold'  => true,
										'color' => array('rgb' => $font)),
										)
										);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}					
								$row++;
							}
							if($ke==3)
							{						 
								$col=0;
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
								if($numk>0)
								{
									$total=($num*3)+3;
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
								$row++;
								$panelis1='';
								$panelis2='';
								$var1='';
								$var2='';
								foreach($kes_hdr as $kes_hdr)
								{
									$panelis2=$kes_hdr->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
									$var2=$parameter;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
									}
									$var1=$var2;
									
									
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col=3;
									foreach($hdr as $hd)
									{
										$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
										if($numk2>0)
										{
											$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									$row++;
									$panelis2=$kes_hdr->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
									}
									$panelis1=$panelis2;
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$parameter;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
									}
									$var1=$var2;
									
									
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col=3;
									foreach($hdr as $hd)
									{
										$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
										if($numk2>0)
										{
											$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
											$saran=$kes->saran;
										}
										else
										{
											$saran="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
										
									}
									$row++;
								}
								}
							}
							else
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
								$col=3;
								foreach($hdr as $hd)
								{
									$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numkes>0)
									{
										$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
								if($numsm>0)
								{
									$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								
								$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
								if($num_desc_sm>0)
								{
								$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
								$desc_sm=$desc_sm->deskripsi;
								}
								else
								{
									$desc_sm="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							$row++;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdr as $hd)
							{
								$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
								if($numac>0)
								{
									$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
									$action=$ac->action_plan;
								}
								else
								{
									$action="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}

						}
						}
					}
					else
					{
						$hdr=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						$row++;
						$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						
						foreach($hdr as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						
						$data=$this->Transaksi_model->nilai_kode_vs2($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						$var1='';
						$var2='';
						$subvar1='';
						$subvar2='';
						foreach($data as $dt)
						{
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$sv=0;
							}
							$sv++;
							$var[$dt->varr]=$sv;
							$var1=$var2;
							
							$subvar2=$dt->subvar;
							if($subvar2!=$subvar1)
							{
								$pan=0;
							}
							$pan++;
							$var[$dt->subvar]=$pan;
							$subvar1=$subvar2;
						}
						$var1='';
						$var2='';
						$subvar1='';
						$subvar2='';
						foreach($data as $dt)
						{
							
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$dt->panelis);
							
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
								$mer=$row+$var[$dt->varr]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
							}
							$var1=$var2;
							$subvar2=$dt->subvar;
							if($subvar2!=$subvar1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
								$mer=$row+$var[$dt->subvar]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
							}
							$subvar1=$subvar2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$nilai1=0;
							$nilai2=0;
							$k=0;
							$col=3;
							foreach($hdr as $hd)
							{
								
								$k++;
								$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
								$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
								$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								if($nilai2>$nilai1)
								{
									$tanda="+";
								}
								else if($nilai2<$nilai1)
								{
									$tanda="-";
								}
								else
								{
									$tanda="";
								}
								if($k==1)
								{
									$tanda="";
								}
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								
								
								$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							$row++;
						}
						if($ke==3)
						{						 
							$col=0;
							$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
							if($numk>0)
							{
								$total=($num*3)+3;
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
							$row++;
							$panelis1='';
							$panelis2='';
							$var1='';
							$var2='';
							foreach($kes_hdr as $kes_hdr)
							{
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								$row++;
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$saran=$kes->saran;
									}
									else
									{
										$saran="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								$row++;
							}
							}
						}
						else
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
							$col=3;
							foreach($hdr as $hd)
							{
								$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
								if($numkes>0)
								{
									$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
						}
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
							if($numsm>0)
							{
								$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							
							$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
							if($num_desc_sm>0)
							{
							$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
							$desc_sm=$desc_sm->deskripsi;
							}
							else
							{
								$desc_sm="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numac>0)
							{
								$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$action=$ac->action_plan;
							}
							else
							{
								$action="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
					}
					

					
						
					
			}
			

		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel VS.xls"');
		$object_writer->save('php://output');
	}
	
	
	function tabel_vs_rekap()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_vs_rekap';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']=0;
		$data['panelis_selected']=0;
		$data['form']=site_url('tabel/tabel_vs_rekap');
		$data['form2']=site_url('tabel/excel_tabel_vs_rekap');
		if($this->input->post('item'))
		{
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['kode1']=$this->input->post('kode1');
			$data['kode2']=$this->input->post('kode2');
			$data['kode3']=$this->input->post('kode3');
			$data['kode4']=$this->input->post('kode4');
			$data['kode5']=$this->input->post('kode5');
			
		}
		else
		{
			$data['kode1']=0;
			$data['kode2']=0;
			$data['kode3']=0;
			$data['kode4']=0;
			$data['kode5']=0;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_vs_rekap()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$ke=$this->input->post('panelis');
		$kode1=$this->input->post('kode1');
		$kode2=$this->input->post('kode2');
		$kode3=$this->input->post('kode3');
		$kode4=$this->input->post('kode4');
		$kode5=$this->input->post('kode5');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
		$num=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->num_rows();
		if($num>0)
		{
			$jdl=$this->Transaksi_model->resume_item($item)->row();
			$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
			$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
			$object->getActiveSheet()->setCellValue('A1','Line Produk');
			$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
			$object->getActiveSheet()->setCellValue('A2','Nama Produk');
			$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
			$object->getActiveSheet()->setCellValue('A3','Awal Riset');
			$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
			$object->getActiveSheet()->setCellValue('A4','Risetman');
			$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
			$object->getActiveSheet()->setCellValue('A5','Target Riset');
			$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
			$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

			$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
			$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
			$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
			$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
			if($jdl->nama_konsep_sebelumnya!='')
			{
				$object->getActiveSheet()->setCellValue('A8','Referensi');
				$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
			}
			$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
				$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
				$tanggal  = strtotime($jdl2->tgl_panelis);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
				$object->getActiveSheet()->setCellValue('A11','Total Formula');
				$object->getActiveSheet()->setCellValue('B11',$total_formula);
				$object->getActiveSheet()->setCellValue('A12','Sember Formula');
				$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
				$row=13;
				foreach($formulaby as $fb)
				{
					$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
					$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
					$row++;
				}
				$hdr=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
				$row++;
				$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
				
				
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=3;
				
				foreach($hdr as $hd)
				{
					
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col++;
				}
				$row++;
				
				$data=$this->Transaksi_model->rekap_kode_vs($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
				$var1='';$var2='';$mer=array();$p=0;
				foreach($data as $dt)
				{
					$p++;
					$var2=$dt->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
				}
				$var1='';$var2='';
				$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+$p-1);
				foreach($data as $dt)
				{
					$var2=$dt->varr;
					if($var1!=$var2)
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$var2]-1);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $dt->varr);	
					}
					$var1=$var2;
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$nilai1=0;
					$nilai2=0;
					$k=0;
					$col=3;
					foreach($hdr as $hd)
					{
						
						$k++;
						$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
						$nilai2=round((float)$dt->$vnilai,2);
						if($nilai2>$nilai1)
						{
							$tanda="+";
						}
						else if($nilai2<$nilai1)
						{
							$tanda="-";
						}
						else
						{
							$tanda="";
						}
						if($k==1)
						{
							$tanda="";
						}
						
						if($nilai2<=70.9)
						{
							
							$font="ff0000";
							$nilai=$nilai2.$tanda;
							$bgcolor="ffc0cb";
						}
						else if(71<=$nilai2 and $nilai2<73)
						{
							$font="000000";
							$nilai=$nilai2.$tanda;
							$bgcolor="ffff00";
						}
						else if($nilai2==73)
						{
							$font="364522";
							$nilai=$nilai2.$tanda;
							$bgcolor="00ff00";
						}
						else if($nilai2>73)
						{
							$font="ffffff";
							$nilai=$nilai2.$tanda;
							$bgcolor="0000ff";
						}
						
						
						$nilai1=$nilai2;
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => $bgcolor)),
						'font'  => array(
							'bold'  => true,
							'color' => array('rgb' => $font)),
							)
							);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col++;
					}					
					$row++;
				}
				if($ke==3)
				{						 
					$col=0;
					$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
					if($numk>0)
					{
						$total=$num+3;
					}
					$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
					$row++;
					foreach($kes_hdr as $kes_hdr)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
							if($numk2>0)
							{
								$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
								$kesimpulan=$kes->kesimpulan;
							}
							else
							{
								$kesimpulan="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col++;
						}
						
						$row++;
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
							if($numk2>0)
							{
								$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
								$saran=$kes->saran;
							}
							else
							{
								$saran="";
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col++;
						}
						$row++;
					}
					
				}
				else
				{
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
					$col=3;
					foreach($hdr as $hd)
					{
						$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
						if($numkes>0)
						{
							$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
							$kesimpulan=$kes->kesimpulan;
						}
						else
						{
							$kesimpulan="";
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);					
						$col++;
					}
					$row++;
				}
				$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=3;
				foreach($hdr as $hd)
				{
					$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
					if($numsm>0)
					{
						$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
					$col++;
				}
				$row++;
				$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=3;
				foreach($hdr as $hd)
				{
					
					$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
					if($num_desc_sm>0)
					{
						$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
						$desc_sm=$desc_sm->deskripsi;
					}
					else
					{
						$desc_sm="";
					}
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col++;
				}
				$row++;
				$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$col=3;
				foreach($hdr as $hd)
				{
					$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
					if($numac>0)
					{
						$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
						$action=$ac->action_plan;
					}
					else
					{
						$action="";
					}
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col++;
				}
				$col+=3;
				foreach($hdr as $dt)
				{
					
					$listedf=$this->Transaksi_model->tabel_dtl2($item,$ke,$dt->kode,$dt->tanggal)->result();
					$row=16;
					$panelis1='';
					$panelis2='';
					$var1='';
					$var2='';
					$mer=array();
					foreach($listedf as $listf)
					{
						$panelis2=$listf->panelis;
						if($panelis1!=$panelis2)
						{
							$pan=0;
						}
						$pan++;
						$mer[$panelis2]=$pan;
						$panelis1=$panelis2;

						$var2=$listf->varr;
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
					foreach($listedf as $listf)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 9, 'Formula');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 9, $listf->kode);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 10, 'Tanggal  Riset');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 10, date('d-m-Y',strtotime($listf->tgl_riset)));
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 11, 'Risetman');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 11, $listf->risetman_hdr);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 12, 'Formula By');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 12, $listf->risetman);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 13, 'Tujuan');	
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 13, $listf->tujuan);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, 15, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, 15, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, 15, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, 15, 'Nilai');
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, 15, 'Keterangan');
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,15)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$panelis2=$listf->panelis;
						if($panelis1!=$panelis2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+$mer[$panelis2]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $listf->panelis);
						}
						$panelis1=$panelis2;
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$var2=$listf->varr;
						if($var1!=$var2)
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+$mer[$var2]-1);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $listf->varr);
						}
						$var1=$var2;
						

						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $listf->subvar);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, round($listf->nilai,2));
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $listf->keterangan);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+4,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$row++;
					}
					
					$col+=7;
				}
				
					
				
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel VS Rekap.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_vs_kompetitor()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_vs_kompetitor';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']=0;
		$data['panelis_selected']=1;
		$data['type']=1;
		$data['form']=site_url('tabel/tabel_vs_kompetitor');
		$data['form2']=site_url('tabel/excel_tabel_vs_kompetitor');
		if($this->input->post('item'))
		{
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['type']=$this->input->post('type');
			$data['komp1']=$this->input->post('komp1');
			$data['komp2']=$this->input->post('komp2');
			$data['komp3']=$this->input->post('komp3');
			$data['komp4']=$this->input->post('komp4');
			$data['komp5']=$this->input->post('komp5');
			$data['kode1']=$this->input->post('kode1');
			$data['kode2']=$this->input->post('kode2');
			$data['kode3']=$this->input->post('kode3');
			$data['kode4']=$this->input->post('kode4');
			$data['kode5']=$this->input->post('kode5');
			
		}
		else
		{
			$data['komp1']=0;
			$data['komp2']=0;
			$data['komp3']=0;
			$data['komp4']=0;
			$data['komp5']=0;
			$data['kode1']=0;
			$data['kode2']=0;
			$data['kode3']=0;
			$data['kode4']=0;
			$data['kode5']=0;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_vs_kompetitor()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$ke=$this->input->post('panelis');
		$type=$this->input->post('type');
		$komp1=$this->input->post('komp1');
		$komp2=$this->input->post('komp2');
		$kode1=$this->input->post('kode1');
		$kode2=$this->input->post('kode2');
		$kode3=$this->input->post('kode3');
		$kode4=$this->input->post('kode4');
		$kode5=$this->input->post('kode5');
		
		if($ke==0)
		{
			$ke=1;
			$panelis_selected=0;
		}
		else
		{
			$panelis_selected=$ke;
		}
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		if($type==1)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$hdk=$this->Transaksi_model->hdr_kode_vs_komp($komp1,$komp2)->result();
			$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
			if(count($hdk)>0 and count($hdf)>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$panelis_selected)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					
					if($panelis_selected==0)
					{
						for($ke=1;$ke<=3;$ke++)
						{
							
							$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							if(count($hdk)>0 and count($hdf)>0)
							{
								$row+=2;
								if($ke==1)
								{
									$pan='Risetman';
								}
								else if($ke==2)
								{
									$pan='Internal';
								}
								else if($ke==3)
								{
									$pan='Taste Specialist';
								}
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => '#000000'),
											'size' => 16
											),
											
											)
											);
								$row++;
							
							
								$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
									
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								
								$data=$this->Transaksi_model->nilai_kode_vs_kompetitor($item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$sv=0;
									}
									$sv++;
									$var[$dt->varr]=$sv;
									$var1=$var2;
									
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$pan=0;
									}
									$pan++;
									$var[$dt->panelis]=$pan;
									$panelis1=$panelis2;
								}
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
										$mer=$row+$var[$dt->panelis]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
										$mer=$row+$var[$dt->varr]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
									}
									$var1=$var2;
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$nilai1=0;
									$nilai2=0;
									$k=0;
									$col=3;
									foreach($hdk as $hd)
									{
										
										$k++;
										$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
										$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
										$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
										$nilai2=round((float)$dt->$vnilai,2);
										$skala2=round((float)$dt->$skala,2);
										
										if($nilai2<=70.9)
										{
											
											$font="ff0000";
											$nilai=$nilai2;
											$bgcolor="ffc0cb";
										}
										else if(71<=$nilai2 and $nilai2<73)
										{
											$font="000000";
											$nilai=$nilai2;
											$bgcolor="ffff00";
										}
										else if($nilai2==73)
										{
											$font="364522";
											$nilai=$nilai2;
											$bgcolor="00ff00";
										}
										else if($nilai2>73)
										{
											$font="ffffff";
											$nilai=$nilai2;
											$bgcolor="0000ff";
										}
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
										'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => $bgcolor)),
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => $font)),
											)
											);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col+=3;
									}					
									
									foreach($hdf as $hd)
									{
										
										$k++;
										$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
										$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
										$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
										$nilai2=round((float)$dt->$vnilai,2);
										$skala2=round((float)$dt->$skala,2);
										if($nilai2>$nilai1)
										{
											$tanda="+";
										}
										else if($nilai2<$nilai1)
										{
											$tanda="-";
										}
										else
										{
											$tanda="";
										}
										if($k==1)
										{
											$tanda="";
										}
										
										if($nilai2<=70.9)
										{
											
											$font="ff0000";
											$nilai=$nilai2;
											$bgcolor="ffc0cb";
										}
										else if(71<=$nilai2 and $nilai2<73)
										{
											$font="000000";
											$nilai=$nilai2;
											$bgcolor="ffff00";
										}
										else if($nilai2==73)
										{
											$font="364522";
											$nilai=$nilai2;
											$bgcolor="00ff00";
										}
										else if($nilai2>73)
										{
											$font="ffffff";
											$nilai=$nilai2;
											$bgcolor="0000ff";
										}
										
										
										$nilai1=$nilai2;
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
										'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => $bgcolor)),
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => $font)),
											)
											);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col+=3;
									}					
									$row++;
								}
								if($ke==3)
								{						 
									$col=0;
									$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
									if($numk>0)
									{
										$total=((count($hdk)+count($hdf))*3)+3;
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
									$row++;
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									foreach($kes_hdr as $kes_hdr)
									{
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdk as $hd)
										{
											
											$kesimpulan="";
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
										}
										
										foreach($hdf as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$kesimpulan=$kes->kesimpulan;
											}
											else
											{
												$kesimpulan="";
											}
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
											
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
										}
										
										$row++;
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdk as $hd)
										{
												$saran="";
											
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
											
										}
										foreach($hdf as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$saran=$kes->saran;
											}
											else
											{
												$saran="";
											}
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
											
										}
										$row++;
									}
									}
								}
								else
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
									$col=3;
									foreach($hdk as $hd)
									{
										
											$kesimpulan="";
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									foreach($hdf as $hd)
									{
										$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
										if($numkes>0)
										{
											$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									$row++;
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									$sumber_masalah="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
									if($numsm>0)
									{
										$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									$desc_sm="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									
									$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
									if($num_desc_sm>0)
									{
									$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
									$desc_sm=$desc_sm->deskripsi;
									}
									else
									{
										$desc_sm="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									
									$action="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}

								
								foreach($hdf as $hd)
								{
									$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numac>0)
									{
										$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$action=$ac->action_plan;
									}
									else
									{
										$action="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}

							}
						}
					}
					else
					{
						
						$row++;
						
						
						$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
							
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						
						$data=$this->Transaksi_model->nilai_kode_vs_kompetitor($item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						$var1='';
						$var2='';
						$panelis1='';
						$panelis2='';
						foreach($data as $dt)
						{
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$sv=0;
							}
							$sv++;
							$var[$dt->varr]=$sv;
							$var1=$var2;
							
							$panelis2=$dt->panelis;
							if($panelis2!=$panelis1)
							{
								$pan=0;
							}
							$pan++;
							$var[$dt->panelis]=$pan;
							$panelis1=$panelis2;
						}
						$var1='';
						$var2='';
						$panelis1='';
						$panelis2='';
						foreach($data as $dt)
						{
							$panelis2=$dt->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
								$mer=$row+$var[$dt->panelis]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
								$mer=$row+$var[$dt->varr]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
							}
							$var1=$var2;
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$nilai1=0;
							$nilai2=0;
							$k=0;
							$col=3;
							foreach($hdk as $hd)
							{
								
								$k++;
								$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
								$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
								$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							
							foreach($hdf as $hd)
							{
								
								$k++;
								$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
								$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
								$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								if($nilai2>$nilai1)
								{
									$tanda="+";
								}
								else if($nilai2<$nilai1)
								{
									$tanda="-";
								}
								else
								{
									$tanda="";
								}
								if($k==1)
								{
									$tanda="";
								}
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								
								
								$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							$row++;
						}
						if($ke==3)
						{						 
							$col=0;
							$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
							if($numk>0)
							{
								$total=((count($hdk)+count($hdf))*3)+3;
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
							$row++;
							$panelis1='';
							$panelis2='';
							$var1='';
							$var2='';
							foreach($kes_hdr as $kes_hdr)
							{
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									
									$kesimpulan="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								$row++;
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
										$saran="";
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								foreach($hdf as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$saran=$kes->saran;
									}
									else
									{
										$saran="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								$row++;
							}
							}
						}
						else
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
							$col=3;
							foreach($hdk as $hd)
							{
								
									$kesimpulan="";
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
								if($numkes>0)
								{
									$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
						}
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							$sumber_masalah="";
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
							if($numsm>0)
							{
								$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							$desc_sm="";
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							
							$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
							if($num_desc_sm>0)
							{
							$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
							$desc_sm=$desc_sm->deskripsi;
							}
							else
							{
								$desc_sm="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							
							$action="";
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}

						
						foreach($hdf as $hd)
						{
							$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numac>0)
							{
								$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$action=$ac->action_plan;
							}
							else
							{
								$action="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
							
					}
					
			}
		}
		else
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$hdk=$this->Transaksi_model->hdr_kode_vs_komp($komp1,$komp2)->result();
			$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
			if(count($hdk)>0 and count($hdf)>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					if($panelis_selected==0)
					{
						for($ke=1;$ke<=3;$ke++)
						{
						$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						if(count($hdk)>0 and count($hdf)>0)
						{
								$row+=2;
								if($ke==1)
								{
									$pan='Risetman';
								}
								else if($ke==2)
								{
									$pan='Internal';
								}
								else if($ke==3)
								{
									$pan='Taste Specialist';
								}
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => '#000000'),
											'size' => 16
											),
											
											)
								);
							$row++;
							
							
							$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
							
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							$row++;
							
							$data=$this->Transaksi_model->nilai_kode_vs_kompetitor2($item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							$var1='';
							$var2='';
							$subvar1='';
							$subvar2='';
							foreach($data as $dt)
							{
								$var2=$dt->varr;
								if($var2!=$var1)
								{
									$sv=0;
								}
								$sv++;
								$var[$dt->varr]=$sv;
								$var1=$var2;
								
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									$pan=0;
								}
								$pan++;
								$var[$dt->subvar]=$pan;
								$subvar1=$subvar2;
							}
							$var1='';
							$var2='';
							$subvar1='';
							$subvar2='';
							foreach($data as $dt)
							{
								$var2=$dt->varr;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
									$mer=$row+$var[$dt->varr]-1;
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
								}
								$var1=$var2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
									$mer=$row+$var[$dt->subvar]-1;
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
								}
								$subvar1=$subvar2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->panelis);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$nilai1=0;
								$nilai2=0;
								$k=0;
								$col=3;
								foreach($hdk as $hd)
								{
									
									$k++;
									$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
									$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
									$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
									$nilai2=round((float)$dt->$vnilai,2);
									$skala2=round((float)$dt->$skala,2);
									
									if($nilai2<=70.9)
									{
										
										$font="ff0000";
										$nilai=$nilai2;
										$bgcolor="ffc0cb";
									}
									else if(71<=$nilai2 and $nilai2<73)
									{
										$font="000000";
										$nilai=$nilai2;
										$bgcolor="ffff00";
									}
									else if($nilai2==73)
									{
										$font="364522";
										$nilai=$nilai2;
										$bgcolor="00ff00";
									}
									else if($nilai2>73)
									{
										$font="ffffff";
										$nilai=$nilai2;
										$bgcolor="0000ff";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
									'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $bgcolor)),
									'font'  => array(
										'bold'  => true,
										'color' => array('rgb' => $font)),
										)
										);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}					
								
								foreach($hdf as $hd)
								{
									
									$k++;
									$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
									$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
									$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
									$nilai2=round((float)$dt->$vnilai,2);
									$skala2=round((float)$dt->$skala,2);
									if($nilai2>$nilai1)
									{
										$tanda="+";
									}
									else if($nilai2<$nilai1)
									{
										$tanda="-";
									}
									else
									{
										$tanda="";
									}
									if($k==1)
									{
										$tanda="";
									}
									
									if($nilai2<=70.9)
									{
										
										$font="ff0000";
										$nilai=$nilai2;
										$bgcolor="ffc0cb";
									}
									else if(71<=$nilai2 and $nilai2<73)
									{
										$font="000000";
										$nilai=$nilai2;
										$bgcolor="ffff00";
									}
									else if($nilai2==73)
									{
										$font="364522";
										$nilai=$nilai2;
										$bgcolor="00ff00";
									}
									else if($nilai2>73)
									{
										$font="ffffff";
										$nilai=$nilai2;
										$bgcolor="0000ff";
									}
									
									
									$nilai1=$nilai2;
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
									'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $bgcolor)),
									'font'  => array(
										'bold'  => true,
										'color' => array('rgb' => $font)),
										)
										);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}					
								$row++;
							}
							if($ke==3)
							{						 
								$col=0;
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
								if($numk>0)
								{
									$total=((count($hdk)+count($hdf))*3)+3;
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
								$row++;
								$panelis1='';
								$panelis2='';
								$var1='';
								$var2='';
								foreach($kes_hdr as $kes_hdr)
								{
									$panelis2=$kes_hdr->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
									$var2=$parameter;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
									}
									$var1=$var2;
									
									
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col=3;
									foreach($hdk as $hd)
									{
										
										$kesimpulan="";
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									foreach($hdf as $hd)
									{
										$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
										if($numk2>0)
										{
											$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									$row++;
									$panelis2=$kes_hdr->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
									}
									$panelis1=$panelis2;
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$parameter;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
									}
									$var1=$var2;
									
									
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col=3;
									foreach($hdk as $hd)
									{
											$saran="";
										
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
										
									}
									foreach($hdf as $hd)
									{
										$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
										if($numk2>0)
										{
											$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
											$saran=$kes->saran;
										}
										else
										{
											$saran="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
										
									}
									$row++;
								}
								}
							}
							else
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
								$col=3;
								foreach($hdk as $hd)
								{
									
										$kesimpulan="";
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numkes>0)
									{
										$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								$sumber_masalah="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
								if($numsm>0)
								{
									$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								$desc_sm="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								
								$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
								if($num_desc_sm>0)
								{
								$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
								$desc_sm=$desc_sm->deskripsi;
								}
								else
								{
									$desc_sm="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							$row++;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								
								$action="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}

							
							foreach($hdf as $hd)
							{
								$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
								if($numac>0)
								{
									$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
									$action=$ac->action_plan;
								}
								else
								{
									$action="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
						}
						}
					}
					else
					{
							$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
					$row++;
					
					
					$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					
					foreach($hdf as $hd)
					{
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					
					$data=$this->Transaksi_model->nilai_kode_vs_kompetitor2($item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
					$var1='';
					$var2='';
					$subvar1='';
					$subvar2='';
					foreach($data as $dt)
					{
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$sv=0;
						}
						$sv++;
						$var[$dt->varr]=$sv;
						$var1=$var2;
						
						$subvar2=$dt->subvar;
						if($subvar2!=$subvar1)
						{
							$pan=0;
						}
						$pan++;
						$var[$dt->subvar]=$pan;
						$subvar1=$subvar2;
					}
					$var1='';
					$var2='';
					$subvar1='';
					$subvar2='';
					foreach($data as $dt)
					{
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
							$mer=$row+$var[$dt->varr]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
						}
						$var1=$var2;
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$subvar2=$dt->subvar;
						if($subvar2!=$subvar1)
						{
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
							$mer=$row+$var[$dt->subvar]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
						}
						$subvar1=$subvar2;
						
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->panelis);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$nilai1=0;
						$nilai2=0;
						$k=0;
						$col=3;
						foreach($hdk as $hd)
						{
							
							$k++;
							$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
							$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
							$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
							$nilai2=round((float)$dt->$vnilai,2);
							$skala2=round((float)$dt->$skala,2);
							
							if($nilai2<=70.9)
							{
								
								$font="ff0000";
								$nilai=$nilai2;
								$bgcolor="ffc0cb";
							}
							else if(71<=$nilai2 and $nilai2<73)
							{
								$font="000000";
								$nilai=$nilai2;
								$bgcolor="ffff00";
							}
							else if($nilai2==73)
							{
								$font="364522";
								$nilai=$nilai2;
								$bgcolor="00ff00";
							}
							else if($nilai2>73)
							{
								$font="ffffff";
								$nilai=$nilai2;
								$bgcolor="0000ff";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => $bgcolor)),
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => $font)),
								)
								);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}					
						
						foreach($hdf as $hd)
						{
							
							$k++;
							$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
							$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
							$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
							$nilai2=round((float)$dt->$vnilai,2);
							$skala2=round((float)$dt->$skala,2);
							if($nilai2>$nilai1)
							{
								$tanda="+";
							}
							else if($nilai2<$nilai1)
							{
								$tanda="-";
							}
							else
							{
								$tanda="";
							}
							if($k==1)
							{
								$tanda="";
							}
							
							if($nilai2<=70.9)
							{
								
								$font="ff0000";
								$nilai=$nilai2;
								$bgcolor="ffc0cb";
							}
							else if(71<=$nilai2 and $nilai2<73)
							{
								$font="000000";
								$nilai=$nilai2;
								$bgcolor="ffff00";
							}
							else if($nilai2==73)
							{
								$font="364522";
								$nilai=$nilai2;
								$bgcolor="00ff00";
							}
							else if($nilai2>73)
							{
								$font="ffffff";
								$nilai=$nilai2;
								$bgcolor="0000ff";
							}
							
							
							$nilai1=$nilai2;
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => $bgcolor)),
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => $font)),
								)
								);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}					
						$row++;
					}
					if($ke==3)
					{						 
						$col=0;
						$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
						if($numk>0)
						{
							$total=((count($hdk)+count($hdf))*3)+3;
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
						$row++;
						$panelis1='';
						$panelis2='';
						$var1='';
						$var2='';
						foreach($kes_hdr as $kes_hdr)
						{
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								
								$kesimpulan="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							$row++;
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
									$saran="";
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
								
							}
							foreach($hdf as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$saran=$kes->saran;
								}
								else
								{
									$saran="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
								
							}
							$row++;
						}
						}
					}
					else
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
						$col=3;
						foreach($hdk as $hd)
						{
							
								$kesimpulan="";
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numkes>0)
							{
								$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$kesimpulan=$kes->kesimpulan;
							}
							else
							{
								$kesimpulan="";
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
					}
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						$sumber_masalah="";
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$col+=3;
					}
					
					foreach($hdf as $hd)
					{
						$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
						if($numsm>0)
						{
							$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						$desc_sm="";
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					
					foreach($hdf as $hd)
					{
						
						$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
						if($num_desc_sm>0)
						{
						$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
						$desc_sm=$desc_sm->deskripsi;
						}
						else
						{
							$desc_sm="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						
						$action="";
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}

					
					foreach($hdf as $hd)
					{
						$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
						if($numac>0)
						{
							$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
							$action=$ac->action_plan;
						}
						else
						{
							$action="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					}
					

					
						
					
			}
			
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel VS Kompetitor.xls"');
		$object_writer->save('php://output');
	}
	function tabel_vs_kompetitor2()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_vs_kompetitor2';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']=0;
		$data['panelis_selected']=1;
		$data['type']=1;
		$data['form']=site_url('tabel/tabel_vs_kompetitor2');
		$data['form2']=site_url('tabel/excel_tabel_vs_kompetitor2');
		if($this->input->post('item'))
		{
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['type']=$this->input->post('type');
			$data['komp1']=$this->input->post('komp1');
			$data['komp2']=$this->input->post('komp2');
			$data['komp3']=$this->input->post('komp3');
			$data['komp4']=$this->input->post('komp4');
			$data['komp5']=$this->input->post('komp5');
			$data['kode1']=$this->input->post('kode1');
			$data['kode2']=$this->input->post('kode2');
			$data['kode3']=$this->input->post('kode3');
			$data['kode4']=$this->input->post('kode4');
			$data['kode5']=$this->input->post('kode5');
			
		}
		else
		{
			$data['komp1']=0;
			$data['komp2']=0;
			$data['komp3']=0;
			$data['komp4']=0;
			$data['komp5']=0;
			$data['kode1']=0;
			$data['kode2']=0;
			$data['kode3']=0;
			$data['kode4']=0;
			$data['kode5']=0;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_vs_kompetitor2()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$ke=$this->input->post('panelis');
		$type=$this->input->post('type');
		$komp1=$this->input->post('komp1');
		$komp2=$this->input->post('komp2');
		$komp3=$this->input->post('komp3');
		$komp4=$this->input->post('komp4');
		$komp5=$this->input->post('komp5');
		$kode1=$this->input->post('kode1');
		$kode2=$this->input->post('kode2');
		$kode3=$this->input->post('kode3');
		$kode4=$this->input->post('kode4');
		$kode5=$this->input->post('kode5');
		
		if($ke==0)
		{
			$ke=1;
			$panelis_selected=0;
		}
		else
		{
			$panelis_selected=$ke;
		}
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		if($type==1)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$hdk=$this->Transaksi_model->hdr_kode_vs_komp2($ke,$komp1,$komp2,$komp3,$komp4,$komp5)->result();
			$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
			if(count($hdk)>0 or count($hdf)>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$panelis_selected)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					
					if($panelis_selected==0)
					{
						for($ke=1;$ke<=3;$ke++)
						{
							$hdk=$this->Transaksi_model->hdr_kode_vs_komp2($ke,$komp1,$komp2,$komp3,$komp4,$komp5)->result();
							$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							if(count($hdk)>0 and count($hdf)>0)
							{
								$row+=2;
								if($ke==1)
								{
									$pan='Risetman';
								}
								else if($ke==2)
								{
									$pan='Internal';
								}
								else if($ke==3)
								{
									$pan='Taste Specialist';
								}
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => '#000000'),
											'size' => 16
											),
											
											)
											);
								$row++;
							
							
								$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
									
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								
								$data=$this->Transaksi_model->nilai_kode_vs_kompetitor3($item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$sv=0;
									}
									$sv++;
									$var[$dt->varr]=$sv;
									$var1=$var2;
									
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$pan=0;
									}
									$pan++;
									$var[$dt->panelis]=$pan;
									$panelis1=$panelis2;
								}
								$var1='';
								$var2='';
								$panelis1='';
								$panelis2='';
								foreach($data as $dt)
								{
									$panelis2=$dt->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
										$mer=$row+$var[$dt->panelis]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$dt->varr;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
										$mer=$row+$var[$dt->varr]-1;
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
									}
									$var1=$var2;
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$nilai1=0;
									$nilai2=0;
									$k=0;
									$col=3;
									foreach($hdk as $hd)
									{
										
										$k++;
										$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
										$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
										$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
										$nilai2=round((float)$dt->$vnilai,2);
										$skala2=round((float)$dt->$skala,2);
										
										if($nilai2<=70.9)
										{
											
											$font="ff0000";
											$nilai=$nilai2;
											$bgcolor="ffc0cb";
										}
										else if(71<=$nilai2 and $nilai2<73)
										{
											$font="000000";
											$nilai=$nilai2;
											$bgcolor="ffff00";
										}
										else if($nilai2==73)
										{
											$font="364522";
											$nilai=$nilai2;
											$bgcolor="00ff00";
										}
										else if($nilai2>73)
										{
											$font="ffffff";
											$nilai=$nilai2;
											$bgcolor="0000ff";
										}
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
										'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => $bgcolor)),
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => $font)),
											)
											);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col+=3;
									}					
									
									foreach($hdf as $hd)
									{
										
										$k++;
										$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
										$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
										$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
										$nilai2=round((float)$dt->$vnilai,2);
										$skala2=round((float)$dt->$skala,2);
										if($nilai2>$nilai1)
										{
											$tanda="+";
										}
										else if($nilai2<$nilai1)
										{
											$tanda="-";
										}
										else
										{
											$tanda="";
										}
										if($k==1)
										{
											$tanda="";
										}
										
										if($nilai2<=70.9)
										{
											
											$font="ff0000";
											$nilai=$nilai2;
											$bgcolor="ffc0cb";
										}
										else if(71<=$nilai2 and $nilai2<73)
										{
											$font="000000";
											$nilai=$nilai2;
											$bgcolor="ffff00";
										}
										else if($nilai2==73)
										{
											$font="364522";
											$nilai=$nilai2;
											$bgcolor="00ff00";
										}
										else if($nilai2>73)
										{
											$font="ffffff";
											$nilai=$nilai2;
											$bgcolor="0000ff";
										}
										
										
										$nilai1=$nilai2;
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
										'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => $bgcolor)),
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => $font)),
											)
											);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col+=3;
									}					
									$row++;
								}
								if($ke==3)
								{						 
									$col=0;
									$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
									if($numk>0)
									{
										$total=((count($hdk)+count($hdf))*3)+3;
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
									$row++;
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									foreach($kes_hdr as $kes_hdr)
									{
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdk as $hd)
										{
											
											$kesimpulan="";
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
										}
										
										foreach($hdf as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$kesimpulan=$kes->kesimpulan;
											}
											else
											{
												$kesimpulan="";
											}
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
											
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
										}
										
										$row++;
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
											$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
										}
										$panelis1=$panelis2;
										$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$var2=$parameter;
										if($var2!=$var1)
										{
											$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
										}
										$var1=$var2;
										
										
										$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
										$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$col=3;
										foreach($hdk as $hd)
										{
												$saran="";
											
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
											
										}
										foreach($hdf as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$saran=$kes->saran;
											}
											else
											{
												$saran="";
											}
											$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
											$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
											
											$col+=3;
											
										}
										$row++;
									}
									}
								}
								else
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
									$col=3;
									foreach($hdk as $hd)
									{
										
											$kesimpulan="";
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									foreach($hdf as $hd)
									{
										$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
										if($numkes>0)
										{
											$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									$row++;
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									$sumber_masalah="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
									if($numsm>0)
									{
										$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									$desc_sm="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									
									$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
									if($num_desc_sm>0)
									{
									$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
									$desc_sm=$desc_sm->deskripsi;
									}
									else
									{
										$desc_sm="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}
								$row++;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									
									$action="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}

								
								foreach($hdf as $hd)
								{
									$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numac>0)
									{
										$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$action=$ac->action_plan;
									}
									else
									{
										$action="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}

							}
						}
					}
					else
					{
						$hdk=$this->Transaksi_model->hdr_kode_vs_komp2($ke,$komp1,$komp2,$komp3,$komp4,$komp5)->result();
						$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						if(count($hdk)>0 and count($hdf)>0)
						{
						
						$row++;
						
						
						$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
							
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						
						$data=$this->Transaksi_model->nilai_kode_vs_kompetitor3($item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						$var1='';
						$var2='';
						$panelis1='';
						$panelis2='';
						foreach($data as $dt)
						{
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$sv=0;
							}
							$sv++;
							$var[$dt->varr]=$sv;
							$var1=$var2;
							
							$panelis2=$dt->panelis;
							if($panelis2!=$panelis1)
							{
								$pan=0;
							}
							$pan++;
							$var[$dt->panelis]=$pan;
							$panelis1=$panelis2;
						}
						$var1='';
						$var2='';
						$panelis1='';
						$panelis2='';
						foreach($data as $dt)
						{
							$panelis2=$dt->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
								$mer=$row+$var[$dt->panelis]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
								$mer=$row+$var[$dt->varr]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
							}
							$var1=$var2;
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$nilai1=0;
							$nilai2=0;
							$k=0;
							$col=3;
							foreach($hdk as $hd)
							{
								
								$k++;
								$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
								$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
								$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							
							foreach($hdf as $hd)
							{
								
								$k++;
								$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
								$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
								$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								if($nilai2>$nilai1)
								{
									$tanda="+";
								}
								else if($nilai2<$nilai1)
								{
									$tanda="-";
								}
								else
								{
									$tanda="";
								}
								if($k==1)
								{
									$tanda="";
								}
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								
								
								$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							$row++;
						}
						if($ke==3)
						{						 
							$col=0;
							$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
							if($numk>0)
							{
								$total=((count($hdk)+count($hdf))*3)+3;
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
							$row++;
							$panelis1='';
							$panelis2='';
							$var1='';
							$var2='';
							foreach($kes_hdr as $kes_hdr)
							{
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
									
									$kesimpulan="";
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								$row++;
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdk as $hd)
								{
										$saran="";
									
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								foreach($hdf as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$saran=$kes->saran;
									}
									else
									{
										$saran="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								$row++;
							}
							}
						}
						else
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
							$col=3;
							foreach($hdk as $hd)
							{
								
									$kesimpulan="";
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
								if($numkes>0)
								{
									$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
						}
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							$sumber_masalah="";
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
							if($numsm>0)
							{
								$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							$desc_sm="";
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							
							$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
							if($num_desc_sm>0)
							{
							$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
							$desc_sm=$desc_sm->deskripsi;
							}
							else
							{
								$desc_sm="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdk as $hd)
						{
							
							$action="";
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}

						
						foreach($hdf as $hd)
						{
							$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numac>0)
							{
								$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$action=$ac->action_plan;
							}
							else
							{
								$action="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						}
					}
					
			}
		}
		else
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$hdk=$this->Transaksi_model->hdr_kode_vs_komp($komp1,$komp2)->result();
			$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
			if(count($hdk)>0 and count($hdf)>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,$ke)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					if($panelis_selected==0)
					{
						for($ke=1;$ke<=3;$ke++)
						{
						$hdk=$this->Transaksi_model->hdr_kode_vs_komp2($ke,$komp1,$komp2,$komp3,$komp4,$komp5)->result();
						$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
						if(count($hdk)>0 and count($hdf)>0)
						{
								$row+=2;
								if($ke==1)
								{
									$pan='Risetman';
								}
								else if($ke==2)
								{
									$pan='Internal';
								}
								else if($ke==3)
								{
									$pan='Taste Specialist';
								}
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
										'font'  => array(
											'bold'  => true,
											'color' => array('rgb' => '#000000'),
											'size' => 16
											),
											
											)
								);
							$row++;
							
							
							$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
							
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							$row++;
							
							$data=$this->Transaksi_model->nilai_kode_vs_kompetitor4($item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							$var1='';
							$var2='';
							$subvar1='';
							$subvar2='';
							foreach($data as $dt)
							{
								$var2=$dt->varr;
								if($var2!=$var1)
								{
									$sv=0;
								}
								$sv++;
								$var[$dt->varr]=$sv;
								$var1=$var2;
								
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									$pan=0;
								}
								$pan++;
								$var[$dt->subvar]=$pan;
								$subvar1=$subvar2;
							}
							$var1='';
							$var2='';
							$subvar1='';
							$subvar2='';
							foreach($data as $dt)
							{
								$var2=$dt->varr;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
									$mer=$row+$var[$dt->varr]-1;
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
								}
								$var1=$var2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
									$mer=$row+$var[$dt->subvar]-1;
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
								}
								$subvar1=$subvar2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->panelis);
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$nilai1=0;
								$nilai2=0;
								$k=0;
								$col=3;
								foreach($hdk as $hd)
								{
									
									$k++;
									$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
									$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
									$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
									$nilai2=round((float)$dt->$vnilai,2);
									$skala2=round((float)$dt->$skala,2);
									
									if($nilai2<=70.9)
									{
										
										$font="ff0000";
										$nilai=$nilai2;
										$bgcolor="ffc0cb";
									}
									else if(71<=$nilai2 and $nilai2<73)
									{
										$font="000000";
										$nilai=$nilai2;
										$bgcolor="ffff00";
									}
									else if($nilai2==73)
									{
										$font="364522";
										$nilai=$nilai2;
										$bgcolor="00ff00";
									}
									else if($nilai2>73)
									{
										$font="ffffff";
										$nilai=$nilai2;
										$bgcolor="0000ff";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
									'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $bgcolor)),
									'font'  => array(
										'bold'  => true,
										'color' => array('rgb' => $font)),
										)
										);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}					
								
								foreach($hdf as $hd)
								{
									
									$k++;
									$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
									$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
									$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
									$nilai2=round((float)$dt->$vnilai,2);
									$skala2=round((float)$dt->$skala,2);
									if($nilai2>$nilai1)
									{
										$tanda="+";
									}
									else if($nilai2<$nilai1)
									{
										$tanda="-";
									}
									else
									{
										$tanda="";
									}
									if($k==1)
									{
										$tanda="";
									}
									
									if($nilai2<=70.9)
									{
										
										$font="ff0000";
										$nilai=$nilai2;
										$bgcolor="ffc0cb";
									}
									else if(71<=$nilai2 and $nilai2<73)
									{
										$font="000000";
										$nilai=$nilai2;
										$bgcolor="ffff00";
									}
									else if($nilai2==73)
									{
										$font="364522";
										$nilai=$nilai2;
										$bgcolor="00ff00";
									}
									else if($nilai2>73)
									{
										$font="ffffff";
										$nilai=$nilai2;
										$bgcolor="0000ff";
									}
									
									
									$nilai1=$nilai2;
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
									'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $bgcolor)),
									'font'  => array(
										'bold'  => true,
										'color' => array('rgb' => $font)),
										)
										);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col+=3;
								}					
								$row++;
							}
							if($ke==3)
							{						 
								$col=0;
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
								if($numk>0)
								{
									$total=((count($hdk)+count($hdf))*3)+3;
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
								$row++;
								$panelis1='';
								$panelis2='';
								$var1='';
								$var2='';
								foreach($kes_hdr as $kes_hdr)
								{
									$panelis2=$kes_hdr->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
									}
									$panelis1=$panelis2;
									
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
									$var2=$parameter;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
									}
									$var1=$var2;
									
									
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col=3;
									foreach($hdk as $hd)
									{
										
										$kesimpulan="";
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									foreach($hdf as $hd)
									{
										$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
										if($numk2>0)
										{
											$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
											$kesimpulan=$kes->kesimpulan;
										}
										else
										{
											$kesimpulan="";
										}
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
										
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
									}
									
									$row++;
									$panelis2=$kes_hdr->panelis;
									if($panelis2!=$panelis1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
										$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
									}
									$panelis1=$panelis2;
									$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$var2=$parameter;
									if($var2!=$var1)
									{
										$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
										$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
									}
									$var1=$var2;
									
									
									$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
									$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$col=3;
									foreach($hdk as $hd)
									{
											$saran="";
										
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
										
									}
									foreach($hdf as $hd)
									{
										$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
										if($numk2>0)
										{
											$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
											$saran=$kes->saran;
										}
										else
										{
											$saran="";
										}
										$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
										$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
										
										$col+=3;
										
									}
									$row++;
								}
								}
							}
							else
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
								$col=3;
								foreach($hdk as $hd)
								{
									
										$kesimpulan="";
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								foreach($hdf as $hd)
								{
									$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
									if($numkes>0)
									{
										$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								$row++;
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								$sumber_masalah="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
								if($numsm>0)
								{
									$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								$desc_sm="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								
								$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
								if($num_desc_sm>0)
								{
								$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
								$desc_sm=$desc_sm->deskripsi;
								}
								else
								{
									$desc_sm="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
							$row++;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								
								$action="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}

							
							foreach($hdf as $hd)
							{
								$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
								if($numac>0)
								{
									$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
									$action=$ac->action_plan;
								}
								else
								{
									$action="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}
						}
						}
					}
					else
					{
					$hdk=$this->Transaksi_model->hdr_kode_vs_komp2($ke,$komp1,$komp2,$komp3,$komp4,$komp5)->result();
					$hdf=$this->Transaksi_model->hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
					if(count($hdk)>0 and count($hdf)>0)
					{
					$row++;
					
					
					$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->nama."\n".date('d-m-Y',strtotime($hd->tanggal)));
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					
					foreach($hdf as $hd)
					{
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					
					$data=$this->Transaksi_model->nilai_kode_vs_kompetitor4($item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
					$var1='';
					$var2='';
					$subvar1='';
					$subvar2='';
					foreach($data as $dt)
					{
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$sv=0;
						}
						$sv++;
						$var[$dt->varr]=$sv;
						$var1=$var2;
						
						$subvar2=$dt->subvar;
						if($subvar2!=$subvar1)
						{
							$pan=0;
						}
						$pan++;
						$var[$dt->subvar]=$pan;
						$subvar1=$subvar2;
					}
					$var1='';
					$var2='';
					$subvar1='';
					$subvar2='';
					foreach($data as $dt)
					{
						$var2=$dt->varr;
						if($var2!=$var1)
						{
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
							$mer=$row+$var[$dt->varr]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
						}
						$var1=$var2;
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$subvar2=$dt->subvar;
						if($subvar2!=$subvar1)
						{
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
							$mer=$row+$var[$dt->subvar]-1;
							$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
						}
						$subvar1=$subvar2;
						
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->panelis);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$nilai1=0;
						$nilai2=0;
						$k=0;
						$col=3;
						foreach($hdk as $hd)
						{
							
							$k++;
							$vnilai='nilaik '.$hd->id_kompetitor.' '.$hd->tanggal;
							$keterangan='keterangank '.$hd->id_kompetitor.' '.$hd->tanggal;
							$skala='skalak '.$hd->id_kompetitor.' '.$hd->tanggal;
							$nilai2=round((float)$dt->$vnilai,2);
							$skala2=round((float)$dt->$skala,2);
							
							if($nilai2<=70.9)
							{
								
								$font="ff0000";
								$nilai=$nilai2;
								$bgcolor="ffc0cb";
							}
							else if(71<=$nilai2 and $nilai2<73)
							{
								$font="000000";
								$nilai=$nilai2;
								$bgcolor="ffff00";
							}
							else if($nilai2==73)
							{
								$font="364522";
								$nilai=$nilai2;
								$bgcolor="00ff00";
							}
							else if($nilai2>73)
							{
								$font="ffffff";
								$nilai=$nilai2;
								$bgcolor="0000ff";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => $bgcolor)),
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => $font)),
								)
								);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}					
						
						foreach($hdf as $hd)
						{
							
							$k++;
							$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
							$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
							$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
							$nilai2=round((float)$dt->$vnilai,2);
							$skala2=round((float)$dt->$skala,2);
							if($nilai2>$nilai1)
							{
								$tanda="+";
							}
							else if($nilai2<$nilai1)
							{
								$tanda="-";
							}
							else
							{
								$tanda="";
							}
							if($k==1)
							{
								$tanda="";
							}
							
							if($nilai2<=70.9)
							{
								
								$font="ff0000";
								$nilai=$nilai2;
								$bgcolor="ffc0cb";
							}
							else if(71<=$nilai2 and $nilai2<73)
							{
								$font="000000";
								$nilai=$nilai2;
								$bgcolor="ffff00";
							}
							else if($nilai2==73)
							{
								$font="364522";
								$nilai=$nilai2;
								$bgcolor="00ff00";
							}
							else if($nilai2>73)
							{
								$font="ffffff";
								$nilai=$nilai2;
								$bgcolor="0000ff";
							}
							
							
							$nilai1=$nilai2;
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => $bgcolor)),
							'font'  => array(
								'bold'  => true,
								'color' => array('rgb' => $font)),
								)
								);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}					
						$row++;
					}
					if($ke==3)
					{						 
						$col=0;
						$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
						if($numk>0)
						{
							$total=((count($hdk)+count($hdf))*3)+3;
						
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
						$row++;
						$panelis1='';
						$panelis2='';
						$var1='';
						$var2='';
						foreach($kes_hdr as $kes_hdr)
						{
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
								
								$kesimpulan="";
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							foreach($hdf as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							
							$row++;
							$panelis2=$kes_hdr->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
							}
							$panelis1=$panelis2;
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$parameter;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
							}
							$var1=$var2;
							
							
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col=3;
							foreach($hdk as $hd)
							{
									$saran="";
								
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
								
							}
							foreach($hdf as $hd)
							{
								$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
								if($numk2>0)
								{
									$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
									$saran=$kes->saran;
								}
								else
								{
									$saran="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
								
							}
							$row++;
						}
						}
					}
					else
					{
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
						$col=3;
						foreach($hdk as $hd)
						{
							
								$kesimpulan="";
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						
						foreach($hdf as $hd)
						{
							$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
							if($numkes>0)
							{
								$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
								$kesimpulan=$kes->kesimpulan;
							}
							else
							{
								$kesimpulan="";
							}
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
					}
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						$sumber_masalah="";
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$col+=3;
					}
					
					foreach($hdf as $hd)
					{
						$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
						if($numsm>0)
						{
							$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
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
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						$desc_sm="";
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					
					foreach($hdf as $hd)
					{
						
						$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
						if($num_desc_sm>0)
						{
						$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
						$desc_sm=$desc_sm->deskripsi;
						}
						else
						{
							$desc_sm="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					$row++;
					$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
					$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=3;
					foreach($hdk as $hd)
					{
						
						$action="";
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}

					
					foreach($hdf as $hd)
					{
						$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
						if($numac>0)
						{
							$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
							$action=$ac->action_plan;
						}
						else
						{
							$action="";
						}
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col+=3;
					}
					}
					

					
						
					
			}
			}
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel VS Kompetitor.xls"');
		$object_writer->save('php://output');
	}
	
	function tabel_resume_formula()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_resume_formula';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']=0;
		$data['type']=1;
		$data['form']=site_url('tabel/tabel_resume_formula');
		$data['form2']=site_url('tabel/excel_resume_formula');
		if($this->input->post('item'))
		{
			$data['item_selected']=$this->input->post('item');
			$data['id_formula']=$this->input->post('id_formula');
			$data['type']=$this->input->post('type');
			
		}
		else
		{
			$data['id_formula']=0;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_resume_formula()
	{
		$id_formula=$this->input->post('id_formula');
		$type=$this->input->post('type');
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
		if($type==1)
		{
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
		}
		else
		{
			$r1s=$this->Transaksi_model->penilaian_formula_list3($id_formula,1)->result();
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
				$subvar1='';
				$subvar2='';
				$var1='';
				$var2='';
				$mtgl1='';
				$mtgl2='';
				$mtglr1='';
				$mtglr2='';
				$mer=array();
				foreach($r1s as $r1)	
				{
					$subvar2=$r1->subvar;
					if($subvar1!=$subvar2)
					{
						$subvar=0;
					}
					$subvar++;
					$mer[$subvar2]=$subvar;
					$subvar1=$subvar2;
					
					$var2=$r1->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
					$mtgl2=$r1->tanggal;
					if($mtgl1!=$mtgl2)
					{
						$st=0;
					}
					$st++;
					$mer[$mtgl2]=$st;
					$mtgl1=$mtgl2;
					
					$mtglr2=$r1->tgl_real;
					if($mtglr1!=$mtglr2)
					{
						$str=0;
					}
					$str++;
					$mer[$mtglr2.'r']=$str;
					$mtglr1=$mtglr2;
					
				}
				$subvar1='';
				$subvar2='';
				$var1='';
				$var2='';
				$mtgl1='';
				$mtgl2='';
				$mtglr1='';
				$mtglr2='';
				foreach($r1s as $r1)
				{
					$var2=$r1->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r1->varr);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					
					$subvar2=$r1->subvar;
					if($subvar1!=$subvar2)
					{
						$sheet->setCellValueByColumnAndRow(1, $row, $r1->subvar);
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$subvar2]-1);
					}
					$subvar1=$subvar2;
					
					$mtgl2=$r1->tanggal;
					if($mtgl1!=$mtgl2)
					{
						$sheet->setCellValueByColumnAndRow(2, $row, $r1->tanggal);
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$mtgl2]-1);
					}
					$mtgl1=$mtgl2;
					
					$mtglr2=$r1->tgl_real;
					if($mtglr1!=$mtglr2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r1->tgl_real);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$mtglr2.'r']-1);
					}
					$mtglr1=$mtglr2;
					$sheet->setCellValueByColumnAndRow(4, $row, $r1->panelis);
					
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
			
			$r2s=$this->Transaksi_model->penilaian_formula_list3($id_formula,2)->result();
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
				$subvar1='';
				$subvar2='';
				$var1='';
				$var2='';
				$mtgl1='';
				$mtgl2='';
				$mtglr1='';
				$mtglr2='';
				$mer=array();
				foreach($r2s as $r2)	
				{
					$subvar2=$r2->subvar;
					if($subvar1!=$subvar2)
					{
						$subvar=0;
					}
					$subvar++;
					$mer[$subvar2]=$subvar;
					$subvar1=$subvar2;
					
					$var2=$r2->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
					
					$mtgl2=$r2->tanggal;
					if($mtgl1!=$mtgl2)
					{
						$st=0;
					}
					$st++;
					$mer[$mtgl2]=$st;
					$mtgl1=$mtgl2;
					
					$mtglr2=$r2->tgl_real;
					if($mtglr1!=$mtglr2)
					{
						$str=0;
					}
					$str++;
					$mer[$mtglr2.'r']=$str;
					$mtglr1=$mtglr2;
				}
				$subvar1='';
				$subvar2='';
				$var1='';
				$var2='';
				$mtgl1='';
				$mtgl2='';
				$mtglr1='';
				$mtglr2='';
				foreach($r2s as $r2)
				{
					$var2=$r2->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r2->varr);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					
					$subvar2=$r2->subvar;
					if($subvar1!=$subvar2)
					{
						$sheet->setCellValueByColumnAndRow(1, $row, $r2->subvar);
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$subvar2]-1);
					}
					$subvar1=$subvar2;
					
					$mtgl2=$r2->tanggal;
					if($mtgl1!=$mtgl2)
					{
						$sheet->setCellValueByColumnAndRow(2, $row, $r2->tanggal);
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$mtgl2]-1);
					}
					$mtgl1=$mtgl2;
					
					$mtglr2=$r2->tgl_real;
					if($mtglr1!=$mtglr2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r2->tgl_real);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$mtglr2.'r']-1);
					}
					$mtglr1=$mtglr2;
					$sheet->setCellValueByColumnAndRow(4, $row, $r2->panelis);
					
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
			
			$r3s=$this->Transaksi_model->penilaian_formula_list3($id_formula,3)->result();
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
				$subvar1='';
				$subvar2='';
				$var1='';
				$var2='';
				$mtgl1='';
				$mtgl2='';
				$mtglr1='';
				$mtglr2='';
				$mer=array();
				foreach($r3s as $r3)	
				{
					$subvar2=$r3->subvar;
					if($subvar1!=$subvar2)
					{
						$subvar=0;
					}
					$subvar++;
					$mer[$subvar2]=$subvar;
					$subvar1=$subvar2;
					
					$var2=$r3->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$var2]=$sv;
					$var1=$var2;
					
					
					$mtgl2=$r3->tanggal;
					if($mtgl1!=$mtgl2)
					{
						$st=0;
					}
					$st++;
					$mer[$mtgl2]=$st;
					$mtgl1=$mtgl2;
					
					$mtglr2=$r3->tgl_real;
					if($mtglr1!=$mtglr2)
					{
						$str=0;
					}
					$str++;
					$mer[$mtglr2.'r']=$str;
					$mtglr1=$mtglr2;
				}
				$subvar1='';
				$subvar2='';
				$var1='';
				$var2='';
				$mtgl1='';
				$mtgl2='';
				$mtglr1='';
				$mtglr2='';
				foreach($r3s as $r3)
				{
					$var2=$r3->varr;
					if($var1!=$var2)
					{
						$sheet->setCellValueByColumnAndRow(0, $row, $r3->varr);
						$sheet->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
					}
					$var1=$var2;
					
					$subvar2=$r3->subvar;
					if($subvar1!=$subvar2)
					{
						$sheet->setCellValueByColumnAndRow(1, $row, $r3->subvar);
						$sheet->mergeCellsByColumnAndRow(1,$row,1,$row+$mer[$subvar2]-1);
					}
					$subvar1=$subvar2;
					
					$mtgl2=$r3->tanggal;
					if($mtgl1!=$mtgl2)
					{
						$sheet->setCellValueByColumnAndRow(2, $row, $r3->tanggal);
						$sheet->mergeCellsByColumnAndRow(2,$row,2,$row+$mer[$mtgl2]-1);
					}
					$mtgl1=$mtgl2;
					
					$mtglr2=$r3->tgl_real;
					if($mtglr1!=$mtglr2)
					{
						$sheet->setCellValueByColumnAndRow(3, $row, $r3->tgl_real);
						$sheet->mergeCellsByColumnAndRow(3,$row,3,$row+$mer[$mtglr2.'r']-1);
					}
					$mtglr1=$mtglr2;
					$sheet->setCellValueByColumnAndRow(4, $row, $r3->panelis);
					
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
		}
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
	
	function tabel_terbaik_nilai()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_terbaik_nilai';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['form']=site_url('tabel/tabel_terbaik_nilai');
		$data['form2']=site_url('tabel/excel_tabel_terbaik_nilai');
		if($this->input->post('item'))
		{
			$data['item_selected']=$this->input->post('item');
			$data['type']=$this->input->post('type');		
		}
		else
		{
			$data['item_selected']=0;
			$data['type']=1;
		}
		$this->load->view('sidemenu',$data);
	}
	
	function excel_tabel_terbaik_nilai()
	{
		
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$type=$this->input->post('type');
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		if($type==1)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$num=$this->Transaksi_model->hdr_kode_terbaik(3,$item)->num_rows();
			if($num>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,3)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					for($panelis_selected=1;$panelis_selected<=3;$panelis_selected++)
					{
						$row+=2;
						if($panelis_selected==1)
						{
							$pan='Risetman';
						}
						else if($panelis_selected==2)
						{
							$pan='Internal';
						}
						else if($panelis_selected==3)
						{
							$pan='Taste Specialist';
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => '#000000'),
									'size' => 16
									),
									
									)
									);
						$hdr=$this->Transaksi_model->hdr_kode_terbaik($panelis_selected,$item)->result();
						$row++;
					
					
						$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
						
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
					
						foreach($hdr as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						
						$data=$this->Transaksi_model->nilai_kode_terbaik($item,$panelis_selected)->result();
						$var1='';
						$var2='';
						$panelis1='';
						$panelis2='';
						foreach($data as $dt)
						{
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$sv=0;
							}
							$sv++;
							$var[$dt->varr]=$sv;
							$var1=$var2;
							
							$panelis2=$dt->panelis;
							if($panelis2!=$panelis1)
							{
								$pan=0;
							}
							$pan++;
							$var[$dt->panelis]=$pan;
							$panelis1=$panelis2;
						}
						$var1='';
						$var2='';
						$panelis1='';
						$panelis2='';
						foreach($data as $dt)
						{
							$panelis2=$dt->panelis;
							if($panelis2!=$panelis1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->panelis);
								$mer=$row+$var[$dt->panelis]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
							}
							$panelis1=$panelis2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->varr);
								$mer=$row+$var[$dt->varr]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
							}
							$var1=$var2;
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $dt->subvar);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$nilai1=0;
							$nilai2=0;
							$k=0;
							$col=3;
							foreach($hdr as $hd)
							{
								
								$k++;
								$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
								$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
								$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								if($nilai2>$nilai1)
								{
									$tanda="+";
								}
								else if($nilai2<$nilai1)
								{
									$tanda="-";
								}
								else
								{
									$tanda="";
								}
								if($k==1)
								{
									$tanda="";
								}
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								
								
								$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							$row++;
						}
						if($panelis_selected==3)
						{						 
							$col=0;
							$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
							if($numk>0)
							{
								$total=($num*3)+3;
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
							$row++;
							$panelis1='';
							$panelis2='';
							$var1='';
							$var2='';
							foreach($kes_hdr as $kes_hdr)
							{
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								$row++;
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$saran=$kes->saran;
									}
									else
									{
										$saran="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								$row++;
							}
							}
						}
						else
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
							$col=3;
							foreach($hdr as $hd)
							{
								$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
								if($numkes>0)
								{
									$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
						}
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$panelis_selected)->num_rows();
							if($numsm>0)
							{
								$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$panelis_selected)->result();
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
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							
							$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
							if($num_desc_sm>0)
							{
							$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->row();
							$desc_sm=$desc_sm->deskripsi;
							}
							else
							{
								$desc_sm="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
							if($numac>0)
							{
								$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->row();
								$action=$ac->action_plan;
							}
							else
							{
								$action="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}

						
							
					
					}
				}
			
		}
		else
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(36);
			$num=$this->Transaksi_model->hdr_kode_terbaik(3,$item)->num_rows();
			if($num>0)
			{
				$jdl=$this->Transaksi_model->resume_item($item)->row();
				$jdl2=$this->Transaksi_model->lama_waktu2($item,3)->row();
				$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
				$object->getActiveSheet()->setCellValue('A1','Line Produk');
				$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
				$object->getActiveSheet()->setCellValue('A2','Nama Produk');
				$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
				$object->getActiveSheet()->setCellValue('A3','Awal Riset');
				$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
				$object->getActiveSheet()->setCellValue('A4','Risetman');
				$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
				$object->getActiveSheet()->setCellValue('A5','Target Riset');
				$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
				$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->setCellValue('A6','Referensi Kompetitor');
				$object->getActiveSheet()->setCellValue('B6',$jdl->kompetitor_list);
				$object->getActiveSheet()->setCellValue('A7','Konsep Sebelumnya');
				$object->getActiveSheet()->setCellValue('B7',$jdl->kompetitor_list);
				if($jdl->nama_konsep_sebelumnya!='')
				{
					$object->getActiveSheet()->setCellValue('A8','Referensi');
					$object->getActiveSheet()->setCellValue('B8',$jdl->nama_konsep_sebelumnya);
				}
				$object->getActiveSheet()->setCellValue('A9','Lama Waktu Riset');
					$tanggal  = strtotime($jdl->awal_riset);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B9',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
					$object->getActiveSheet()->setCellValue('A10','Lama Waktu Panelis Terakhir');
					$tanggal  = strtotime($jdl2->tgl_panelis);
					$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
					$total   = $sekarang - $tanggal;
					$tahun=floor($total/(60 * 60 * 24 * 365));
					$sisa=$total-($tahun*(60 * 60 * 24 * 365));
					$bulan=floor($sisa/(60 * 60 * 24 * 30));
					$hari_ini = date("Y-m-d");
					$tgl_awal=date('d',$tanggal);
					$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
					$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
					$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
					$hari=$hari-1;
					$object->getActiveSheet()->setCellValue('B10',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )');
					$object->getActiveSheet()->setCellValue('A11','Total Formula');
					$object->getActiveSheet()->setCellValue('B11',$total_formula);
					$object->getActiveSheet()->setCellValue('A12','Sember Formula');
					$formulaby=$this->Transaksi_model->list_formula_by($item)->result();
					$row=13;
					foreach($formulaby as $fb)
					{
						$object->getActiveSheet()->setCellValue('A'.$row, $fb->risetman);
						$object->getActiveSheet()->setCellValue('B'.$row, $fb->jumlah);
						$row++;
					}
					for($panelis_selected=1;$panelis_selected<=3;$panelis_selected++)
					{
						$row+=2;
						if($panelis_selected==1)
						{
							$pan='Risetman';
						}
						else if($panelis_selected==2)
						{
							$pan='Internal';
						}
						else if($panelis_selected==3)
						{
							$pan='Taste Specialist';
						}
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $pan);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array(
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => '#000000'),
									'size' => 16
									),
									
									)
									);
						$hdr=$this->Transaksi_model->hdr_kode_terbaik($panelis_selected,$item)->result();
						$row++;
						$object->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Var');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Subvar');
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Panelis');
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						
						foreach($hdr as $hd)
						{
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hd->kode."\n".date('d-m-Y',strtotime($hd->tanggal)));
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						
						$data=$this->Transaksi_model->nilai_kode_terbaik2($item,$panelis_selected)->result();
						$var1='';
						$var2='';
						$subvar1='';
						$subvar2='';
						foreach($data as $dt)
						{
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$sv=0;
							}
							$sv++;
							$var[$dt->varr]=$sv;
							$var1=$var2;
							
							$subvar2=$dt->subvar;
							if($subvar2!=$subvar1)
							{
								$pan=0;
							}
							$pan++;
							$var[$dt->subvar]=$pan;
							$subvar1=$subvar2;
						}
						$var1='';
						$var2='';
						$subvar1='';
						$subvar2='';
						foreach($data as $dt)
						{
						
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$dt->panelis);
							$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$var2=$dt->varr;
							if($var2!=$var1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
								$mer=$row+$var[$dt->varr]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$mer);
							}
							$var1=$var2;
							$subvar2=$dt->subvar;
							if($subvar2!=$subvar1)
							{
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
								$mer=$row+$var[$dt->subvar]-1;
								$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$mer);
							}
							$subvar1=$subvar2;
							
							$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$nilai1=0;
							$nilai2=0;
							$k=0;
							$col=3;
							foreach($hdr as $hd)
							{
								
								$k++;
								$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
								$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
								$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
								$nilai2=round((float)$dt->$vnilai,2);
								$skala2=round((float)$dt->$skala,2);
								if($nilai2>$nilai1)
								{
									$tanda="+";
								}
								else if($nilai2<$nilai1)
								{
									$tanda="-";
								}
								else
								{
									$tanda="";
								}
								if($k==1)
								{
									$tanda="";
								}
								
								if($nilai2<=70.9)
								{
									
									$font="ff0000";
									$nilai=$nilai2;
									$bgcolor="ffc0cb";
								}
								else if(71<=$nilai2 and $nilai2<73)
								{
									$font="000000";
									$nilai=$nilai2;
									$bgcolor="ffff00";
								}
								else if($nilai2==73)
								{
									$font="364522";
									$nilai=$nilai2;
									$bgcolor="00ff00";
								}
								else if($nilai2>73)
								{
									$font="ffffff";
									$nilai=$nilai2;
									$bgcolor="0000ff";
								}
								
								
								$nilai1=$nilai2;
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->applyFromArray(array(
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $bgcolor)),
								'font'  => array(
									'bold'  => true,
									'color' => array('rgb' => $font)),
									)
									);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nilai.$tanda);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $skala2);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $dt->$keterangan);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col+=3;
							}					
							$row++;
						}
						if($panelis_selected==3)
						{						 
							$col=0;
							$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->num_rows();
							if($numk>0)
							{
								$total=($num*3)+3;
							
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$total-1,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Kesimpulan');
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($total-1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item)->result();
							$row++;
							$panelis1='';
							$panelis2='';
							$var1='';
							$var2='';
							foreach($kes_hdr as $kes_hdr)
							{
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Kesimpulan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$kesimpulan=$kes->kesimpulan;
									}
									else
									{
										$kesimpulan="";
									}
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
									
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
								}
								
								$row++;
								$panelis2=$kes_hdr->panelis;
								if($panelis2!=$panelis1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+5);
									$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $kes_hdr->panelis);
								}
								$panelis1=$panelis2;
								$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$var2=$parameter;
								if($var2!=$var1)
								{
									$object->getActiveSheet()->mergeCellsByColumnAndRow(1,$row,1,$row+1);
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $parameter);
								}
								$var1=$var2;
								
								
								$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Action Plan');
								$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$col=3;
								foreach($hdr as $hd)
								{
									$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
									if($numk2>0)
									{
										$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
										$saran=$kes->saran;
									}
									else
									{
										$saran="";
									}
									$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
									$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $saran);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
									$col+=3;
									
								}
								$row++;
							}
							}
						}
						else
						{
							$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Kesimpulan');
							$col=3;
							foreach($hdr as $hd)
							{
								$numkes=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
								if($numkes>0)
								{
									$kes=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->row();
									$kesimpulan=$kes->kesimpulan;
								}
								else
								{
									$kesimpulan="";
								}
								$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
								$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $kesimpulan);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
								$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								
								$col+=3;
							}
							$row++;
						}
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Sumber Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$panelis_selected)->num_rows();
							if($numsm>0)
							{
								$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$panelis_selected)->result();
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
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $sumber_masalah);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Deskripsi Masalah');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							
							$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
							if($num_desc_sm>0)
							{
							$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->row();
							$desc_sm=$desc_sm->deskripsi;
							}
							else
							{
								$desc_sm="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $desc_sm);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}
						$row++;
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,2,$row);
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Action Plan');
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col=3;
						foreach($hdr as $hd)
						{
							$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
							if($numac>0)
							{
								$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$panelis_selected)->row();
								$action=$ac->action_plan;
							}
							else
							{
								$action="";
							}
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row);
							$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $action);
							$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$object->getActiveSheet()->getCellByColumnAndRow($col+2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							$col+=3;
						}

					}
							
					
			}
			

		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Tabel Nilai Terbaik.xls"');
		$object_writer->save('php://output');
	}
	
	function get_kode()
	{
		$this->cek_login();
		$id_item=$this->input->post('id_item');
		$data=$this->Transaksi_model->list_formula($id_item)->result();
		echo json_encode($data);
	}
	function get_kompetitor()
	{
		$this->cek_login();
		$id_item=$this->input->post('id_item');
		$data=$this->Transaksi_model->list_kompetitor($id_item)->result();
		echo json_encode($data);
	}
	
	function range_panelis()
	{
		$this->cek_login();
		$data['main_view']='v_range_panelis';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']='';
		$data['panelis_selected']='';
		$data['form']=site_url('tabel/range_panelis');
		//$data['form2']=site_url('tabel/excel_tabel_masalah');
		if($this->input->post('item'))
		{
			$data['items']=$this->input->post('item');
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['ke']=$this->input->post('panelis');
		}
		else
		{
			$data['items']=0;
			$data['ke']=1;
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
		}
		$this->load->view('sidemenu',$data);
	}
	function tabel_formula_terbaik()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_formula_terbaik';
		$data['item']=$this->Master_model->get_produk_akses($this->session->userdata('nama_seas'))->result();//ganti
		$data['item_selected']='';
		$data['panelis_selected']='';
		$data['form']=site_url('tabel/tabel_formula_terbaik');
		$data['form2']=site_url('tabel/excel_tabel_formula_terbaik');
		if($this->input->post('item'))
		{
			$data['items']=$this->input->post('item');
			$data['item_selected']=$this->input->post('item');
			$data['panelis_selected']=$this->input->post('panelis');
			$data['tgl_awal']=$this->input->post('tgl_awal');
			$data['tgl_akhir']=$this->input->post('tgl_akhir');
			$data['type']=$this->input->post('type');
			$data['ke']=$this->input->post('panelis');
		}
		else
		{
			$data['items']=0;
			$data['ke']=1;
			$data['tgl_awal']='';
			$data['tgl_akhir']='';
			$data['type']=1;
		}
		$this->load->view('sidemenu',$data);
	}
	function excel_tabel_formula_terbaik()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$item=$this->input->post('item');
		$type=$this->input->post('type');
		$tgl_awal=date("Y-m-d",strtotime($this->input->post('tgl_awal')));
		$tgl_akhir=date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		if($type==1)
		{
		$hd=$this->Transaksi_model->rekap_formula_5terbaik($item)->result();
		$dtl=$this->Transaksi_model->rekap_formula_5terbaik2($item)->result();
		if(count($hd)>0)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$jdl=$this->Transaksi_model->resume_item($item)->row();
			$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
			$object->getActiveSheet()->setCellValue('A1','Line Produk');
			$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
			$object->getActiveSheet()->setCellValue('A2','Nama Produk');
			$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
			$object->getActiveSheet()->setCellValue('A3','Awal Riset');
			$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
			$object->getActiveSheet()->setCellValue('A4','Risetman');
			$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
			$object->getActiveSheet()->setCellValue('A5','Target Riset');
			$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
			$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->setCellValue('A6','Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B6',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
												
				$row=9;
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Var');
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Subvar');
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$col=2;
				foreach($hd as $hdr)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$hdr->tanggal);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$col++;
				}
				$var1='';
				$var2='';
				$mer=array();
				foreach($dtl as $dt)
				{
					$var2=$dt->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$dt->varr]=$sv;
					$var1=$var2;
				}
				$var1='';
				$var2='';
				
				foreach($dtl as $dt)
				{
					$row++;
					$var2=$dt->varr;
					if($var1!=$var2)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
					
					}
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$var1=$var2;
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=2;
					foreach($hd as $hdr)
					{
						$vkode=$hdr->tanggal.' kode';
						$vid=$hdr->tanggal.' id';
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$dt->$vkode);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col++;
					}
				}
			
		}
			
		}
		else
		{
		$hd=$this->Transaksi_model->rekap_formula_terbaik_date($item,$tgl_awal,$tgl_akhir)->result();
		$dtl=$this->Transaksi_model->rekap_formula_terbaik_date2($item,$tgl_awal,$tgl_akhir)->result();
		if(count($hd)>0)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(36);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$jdl=$this->Transaksi_model->resume_item($item)->row();
			$total_formula=$this->Transaksi_model->list_formula($item)->num_rows();
			$object->getActiveSheet()->setCellValue('A1','Line Produk');
			$object->getActiveSheet()->setCellValue('B1',$jdl->lineproduk);
			$object->getActiveSheet()->setCellValue('A2','Nama Produk');
			$object->getActiveSheet()->setCellValue('B2',$jdl->nama_item);
			$object->getActiveSheet()->setCellValue('A3','Awal Riset');
			$object->getActiveSheet()->setCellValue('B3',date('d-m-Y',strtotime($jdl->awal_riset)));
			$object->getActiveSheet()->setCellValue('A4','Risetman');
			$object->getActiveSheet()->setCellValue('B4',$jdl->risetman);
			$object->getActiveSheet()->setCellValue('A5','Target Riset');
			$object->getActiveSheet()->setCellValue('B5',$jdl->kompetitor);
			$object->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->setCellValue('A6','Lama Waktu Riset');
				$tanggal  = strtotime($jdl->awal_riset);
				$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
				$total   = $sekarang - $tanggal;
				$tahun=floor($total/(60 * 60 * 24 * 365));
				$sisa=$total-($tahun*(60 * 60 * 24 * 365));
				$bulan=floor($sisa/(60 * 60 * 24 * 30));
				$hari_ini = date("Y-m-d");
				$tgl_awal=date('d',$tanggal);
				$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
				$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
				$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
				$hari=$hari-1;
				$object->getActiveSheet()->setCellValue('B6',$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari');
												
				$row=9;
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Var');
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Subvar');
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$col=2;
				foreach($hd as $hdr)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$hdr->tanggal);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$col++;
				}
				$var1='';
				$var2='';
				$mer=array();
				foreach($dtl as $dt)
				{
					$var2=$dt->varr;
					if($var1!=$var2)
					{
						$sv=0;
					}
					$sv++;
					$mer[$dt->varr]=$sv;
					$var1=$var2;
				}
				$var1='';
				$var2='';
				
				foreach($dtl as $dt)
				{
					$row++;
					$var2=$dt->varr;
					if($var1!=$var2)
					{
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$dt->varr);
						$object->getActiveSheet()->mergeCellsByColumnAndRow(0,$row,0,$row+$mer[$var2]-1);
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);
					
					}
						$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$var1=$var2;
					
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$dt->subvar);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$col=2;
					foreach($hd as $hdr)
					{
						$vkode=$hdr->tanggal.' kode';
						$vid=$hdr->tanggal.' id';
						$object->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$dt->$vkode);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getCellByColumnAndRow($col,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$col++;
					}
				}
			
		}
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Formula Terbaik.xls"');
		$object_writer->save('php://output');
	}
	function tabel_resume_terbaik()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_resume_terbaik';
		$data['produk']=$this->Master_model->get_produk_run_akses($this->session->userdata('nama_seas'))->result();//ganti
	
		$this->load->view('sidemenu',$data);
	}
	function get_dtl_penilaian_terbaik()
	{
		$this->cek_login();
		
		$id_formula=$this->input->post('id_formula');
		$id_penilaian=$this->input->post('id_penilaian');
		$data=$this->Transaksi_model->dtl_subvar_formula_terbaik($id_formula,$id_penilaian)->result();
		echo json_encode($data);
	}
	function tabel_list_kompetitor()
	{
		$this->cek_login();
		$data['main_view']='v_tabel_kompetitor';
		//$data['line']=$this->Master_model->get_lp()->result();
		$la=$this->Transaksi_model->list_line_akses($this->session->userdata('id_seas'))->result();//ganti
		if(count($la)>0)
		{
			$data['line']=$la;
		}
		else
		{
			$la2=$this->Transaksi_model->list_line_item_akses($this->session->userdata('id_seas'))->result();//ganti
			$data['line']=$la2;	
		}
		$data['line_selected']='';
		$data['lines']='';
					$linea=array();
					$data['linea']=$linea;

		$data['form']=site_url('tabel/tabel_list_kompetitor');
		//$data['form2']=site_url('tabel/excel_waktu2');
		$data['form2']='';
		if($this->input->post('line'))
		{
			$lines='';
			$linea=array();

			foreach($this->input->post('line') as $line)
			{
				$data['line_selected']=$line;
				$lines.=$data['line_selected'].',';
				array_push($linea,$line);
			}
					$data['lines']=rtrim($lines,',');
					$data['linea']=$linea;

		}
		else
		{
			$data['line_selected']='';
		}
		
		$this->load->view('sidemenu',$data);
		//$this->load->view('v_dd',$data);
	}
	function tabel_aktivitas()
	{
		$this->cek_login(); 
		$data['main_view']='v_tabel_aktivitas';
		$data['form']=site_url('tabel/tabel_aktivitas');	
		$data['form2']=site_url('tabel/excel_tabel_aktivitas');	
		
		
		$data['line']=$this->Login_model->list_all_user()->result();
		$data['line_selected']='';
		$data['lines']='';
		$linea=array();
		$data['linea']=$linea;
		if($this->input->post('line'))
		{
			$lines='';
			$linea=array();

			foreach($this->input->post('line') as $line)
			{
				$data['line_selected']=$line;
				$lines.=$data['line_selected'].',';
				array_push($linea,$line);
			}
					$data['lines']=rtrim($lines,',');
					$data['linea']=$linea;

		}
		else
		{
			$data['line_selected']='';
		}
		
			if($this->input->post('tgl_awal'))
			{
				$data['tgl1']=$this->input->post('tgl_awal');
				$data['tgl_awal']=$this->input->post('tgl_awal');
				$data['tgl2']=$this->input->post('tgl_akhir');
				$data['tgl_akhir']=$this->input->post('tgl_akhir');
				
			}
			else
			{
				$data['tgl1']='';
				$data['tgl2']='';
			}
			
			$this->load->view('sidemenu',$data);
		
		
	}
	function excel_tabel_aktivitas()
	{
		$this->load->library("excel");
		$object = new PHPExcel();
		$tgl1=date("Y-m-d",strtotime($this->input->post('tgl_awal')));
		$tgl2=date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		if($this->input->post('line'))
		{
			$lines='';
			$linea=array();

			foreach($this->input->post('line') as $line)
			{
				$data['line_selected']=$line;
				$lines.=$data['line_selected'].',';
				array_push($linea,$line);
			}
					$lines=rtrim($lines,',');
					$data['linea']=$linea;

		}
		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		$listed=$this->Transaksi_model->act_panelis($tgl1,$tgl2,$lines)->result();
		if(count($listed)>0)
		{
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(70);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(36);
			$row=1;
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'Aktivitas');
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,'Waktu');
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,'PIC');
			$object->getActiveSheet()->setCellValueByColumnAndRow(3, $row,'Real Name');
			$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow(3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->applyFromArray(array('font'  => array('bold'  => true,)));
			$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->applyFromArray(array('font'  => array('bold'  => true,)));
			$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->applyFromArray(array('font'  => array('bold'  => true,)));
			$object->getActiveSheet()->getCellByColumnAndRow(3,$row)->getStyle()->applyFromArray(array('font'  => array('bold'  => true,)));
			$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(3,$row)->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getCellByColumnAndRow(3,$row)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			foreach($listed as $list)
			{
				$row++;
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$list->kegiatan);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $row,date('d-m-Y H:i',strtotime($list->tgl)));
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$list->pic);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $row,$list->realname);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(1,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(2,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(3,$row)->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$object->getActiveSheet()->getCellByColumnAndRow(0,$row)->getStyle()->getAlignment()->setWrapText(true);

			}
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Log Aktivitas.xls"');
		$object_writer->save('php://output');
	}
	
	
}