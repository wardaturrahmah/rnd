<html lang="en">
	<head>
		<title>Laporan VS Rekap</title>
		<script>
			function get_kode(){
			var id_item=$( "#item option:selected" ).val();
			var kode1=<?php echo $kode1;?>;
			var kode2=<?php echo $kode2;?>;
			var kode3=<?php echo $kode3;?>;
			var kode4=<?php echo $kode4;?>;
			var kode5=<?php echo $kode5;?>;
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_kode',
					method:'post',
					data:{
							id_item:id_item,
						},
					success:function(data)
					{
							
							var html = '<option value="0">-</option>';
							var i=0;
							var obj = jQuery.parseJSON(data);
							//loop opsi
							for(i=0; i<obj.length; i++){
								html += '<option value="'+obj[i].id+'">'+obj[i].kode+'</option>';
							}
							$('#kode1').html(html);//mengembalikan nilai option pada id kode			
							$('#kode2').html(html);//mengembalikan nilai option pada id kode			
							$('#kode3').html(html);//mengembalikan nilai option pada id kode			
							$('#kode4').html(html);//mengembalikan nilai option pada id kode			
							$('#kode5').html(html);//mengembalikan nilai option pada id kode			
							//if(a!='')
							//{
							$('#kode1').val(kode1);
							$('#kode2').val(kode2);
							$('#kode3').val(kode3);
							$('#kode4').val(kode4);
							$('#kode5').val(kode5);
							//}

					}
				});	
			
		
		}  
			
			function get_dtl(id)
			{
			var arr = id.split("_");
			var kode =  arr[0];
			var ke =  arr[1];
			var id_item =  arr[2];
			var tgl =  arr[3];
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_penilaian_ke',
					method:'post',
					data:{
							kode:kode,
							ke:ke,
							id_item:id_item,
							tgl:tgl,
						},
					success:function(data)
					{
							
							 var html = '';
							 var judul = '';
							 var i=0;
							 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
							//membuat header kolom
							 html='<thead ><th class="text-center">Panelis</th><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Skala</th><th class="text-center">Keterangan</th></thead>';
							var var1='';
							var var2='';
							var panelis1='';
							var panelis2='';
							var jum_var=0;
							var mer=[];
							for(i=0; i<obj.length; i++){
							 var2=obj[i].varr;
							 if(var1!=var2)
							 {
								 var sv=0;
								 jum_var++;
							 }
							var1=var2;
							sv++;
							mer[obj[i].varr] = sv;

							panelis2=obj[i].panelis;
							 if(panelis1!=panelis2)
							 {
								 var pan=0;
							 }
							panelis1=panelis2;
							pan++;
							mer[obj[i].panelis] = pan;
							}
							
							var var1='';
							var var2='';
							var panelis1='';
							var panelis2='';
							var jum_var=0;
							for(i=0; i<obj.length; i++){
								html +='<tr align="left">';
								panelis2=obj[i].panelis;
								if(panelis1!=panelis2)
								{
									html += '<td rowspan='+mer[obj[i].panelis]+'>'+obj[i].panelis+'</td>';
								}
								else
								{
									html += '';
								}
								panelis1=panelis2;
								
								var2=obj[i].varr;
								if(var1!=var2)
								{
									html += '<td rowspan='+mer[obj[i].varr]+'>'+obj[i].varr+'</td>';
								}
								else
								{
									html += '';
								}
								var1=var2;
								
								html += '<td>'+obj[i].subvar+'</td><td>'+Math.round(obj[i].nilai * 100) / 100+'</td><td>'+Math.round(obj[i].skala * 100) / 100+'</td><td>'+obj[i].keterangan+'</td>';
								html +='</tr>';
							}
							
							if(ke==1)
							{
								var panelis_='Panelis Risetman';
							}
							else if(ke==2)
							{
								var panelis_='Panelis Internal';
							}
							else if(ke==3)
							{
								var panelis_='Panelis Taste Specialist';
							}
							else
							{
								var panelis_='';
							}
							judul='Penilaian '+panelis_+' Formula dengan seri '+kode+' Tanggal '+tgl;
							//menaruh variabel html pada tabel
							 $('#tabel').html(html);
							 $('#myModalLabel').html(judul);
						 

					}
				});	
			
			$('#myModal').modal('show');
		}
		
		</script>
		<style type="text/css">
		table {
		  text-align: left;
		  position: relative;
		}

		th {
		  background: white;
		  position: sticky;
		  top: 0;
		}
	</style>
	</head>
	<body class="nav-md" onload="get_kode();">

<?php
$this->load->model('Transaksi_model', '', TRUE);
$this->load->model('Master_model', '', TRUE);
?>	
		<div class="right_col" role="main">
			<div class="page-title">
				
				<div class="title_right">
				</div>
			</div>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Laporan VS Rekap : Laporan yang digunakan untuk memversuskan 5 formula pada produk dan direkap tiap subvar</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="panelis" id="panelis">
										
												<option <?php echo $panelis_selected == 1 ? 'selected="selected"' : '' ?> value="1">Risetman</option>
												<option <?php echo $panelis_selected == 2 ? 'selected="selected"' : '' ?> value="2">Internal</option>
												<option <?php echo $panelis_selected == 3 ? 'selected="selected"' : '' ?> value="3">Taste Specialist</option>
													
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Item</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="item" id="item" onchange="get_kode();">
										<?php
											foreach ($item as $item) {
												?>
												<option <?php echo $item_selected == $item->id ? 'selected="selected"' : '' ?> value="<?php echo $item->id ?>">
													<?php echo $item->nama_item ?>
												</option>
												<?php
											}	
										?>					
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode1" id="kode1" class="kode form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
										</select>			
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode2" id="kode2" class="kode form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
										</select>			
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode3" id="kode3" class="kode form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
										</select>			
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode4" id="kode4" class="kode form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
										</select>			
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode5" id="kode5" class="kode form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
										</select>			
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<input type="submit" class="btn btn-success" value="Submit" onclick="javascript: form.action='<?php echo $form?>';"/>
										<input type="submit" class="btn btn-info" value="Excel" onclick="javascript: form.action='<?php echo $form2?>';"/>
								
									</div>
									
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>List</h2>
								
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
							<?php
							$num=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->num_rows();
							if($num>0)
							{
							$jdl=$this->Transaksi_model->resume_item($item_selected)->row();
								$jdl2=$this->Transaksi_model->lama_waktu2($item_selected,$panelis_selected)->row();
								$total_formula=$this->Transaksi_model->list_formula($item_selected)->num_rows();
							?>
							<table width="100%">
							<tr>
									<td width="25%">Line Produk</td>
									<td width="75%"><?php echo $jdl->lineproduk; ?></td>
									
							</tr>
							<tr>
									<td width="25%">Nama Produk</td>
									<td width="75%"><?php echo $jdl->nama_item; ?></td>
							</tr>
							<tr>
									<td width="25%">Awal Riset</td>
									<td width="75%"><?php echo date('d-m-Y',strtotime($jdl->awal_riset)); ?></td>
							</tr>
							<tr>
									<td width="25%">Risetman</td>
									<td width="75%"><?php echo $jdl->risetman?></td>
							</tr>
							<tr>
									<td width="25%">Target Riset</td>
									<td width="75%"><?php echo $jdl->kompetitor?></td>
							</tr>
							<tr>
									<td width="25%">Referensi Kompetitor</td>
									<td width="75%">
									<?php
									$link=$this->Transaksi_model->list_kompetitor($item_selected)->result();
										foreach($link as $link)
										{
											if($link->status_kompetitor==1)
											{
									?>
										<u><a href="<?php echo base_url().'/panelis_kompetitor/'.$link->id_kompetitor?>"  target="_blank"><?php echo $link->nama.',';?></a></u>
									<?php
											}
										}
									?>
									</td>
							</tr>
							<tr>
								<td width="25%">Konsep Sebelumnya :</td>
										<?php
										if($jdl->nama_konsep_sebelumnya!='')
										{?>
										<td width="75%"><?php echo $jdl->nama_konsep_sebelumnya;?></td>
										
										<?php
										}
										?>
							</tr>
							<tr>
									<td width="25%">Referensi :</td>
									<td>
									<?php
									$num_link=$this->Master_model->get_ref_link($jdl->id)->num_rows();
									if($num_link>0)
									{
										$link=$this->Master_model->get_ref_link($jdl->id)->result();
										foreach($link as $link)
										{
											echo $link->nama_item.',';
										}
									}
									?>	
							</tr>
							<tr>
									<td width="25%">Lama Waktu Riset</td>
									<td width="75%"><?php 
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
										
								echo $tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari';?></td>
							</tr>
							<tr>
									<td width="25%">Lama Waktu Panelis Terakhir</td>
									<td width="75%"><?php 
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
										
								echo $tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )';?></td>
							</tr>
							<tr>
									<td width="25%">Total Formula</td>
									<td width="75%"><?php echo $total_formula;?></td>
							</tr>
							
							<tr>
									<td width="25%">Sumber Formula</td>
									<td width="75%"></td>
									
							</tr>
							<?php
							$formulaby=$this->Transaksi_model->list_formula_by($item_selected)->result();
							foreach($formulaby as $fb)
							{
							?>
							<tr>
									<td width="25%"><?php echo $fb->risetman;?></td>
									<td width="75%"><?php echo $fb->jumlah;?></td>
							</tr>
							<?php
							}
							?>
							</table>
							<div style="overflow-x:auto;overflow-y:auto;height:350px;">
							<table class="table table-bordered">
							<thead>
							<th></th>
							<th><center>Var</center></th>
							<th><center>Subvar</center></th>
							<?php
							$hdr=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							foreach($hdr as $hd)
							{
							?>
								<th><center><?php echo $hd->kode.'<br>'.date('d-m-Y',strtotime($hd->tanggal));?></center></th>
							<?php
								
							}
							?>
							</thead>
							<tr>
							<?php
							$data=$this->Transaksi_model->rekap_kode_vs($item_selected,$panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							$var1='';
							$var2='';
							$mer=array();
							foreach($data as $dt)
							{
								$var2=$dt->varr;
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
							foreach($data as $dt)
							{
								
							?>
							<td></td>
							<?php
								$var2=$dt->varr;
								if($var1!=$var2)
								{?>
									<td rowspan="<?php echo $mer[$var2]?>"><?php echo $dt->varr; ?></td>
							<?php
								}
								$var1=$var2;
							?>
							
							<td><?php echo $dt->subvar; ?></td>
							<?php
								$nilai1=0;
								$nilai2=0;
								$k=0;
								foreach($hdr as $hd)
								{
									$k++;
									$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
									$nilai2=round($dt->$vnilai,2);
																	if($nilai2>$nilai1)
								{
									$tanda='+';
								}
								else if($nilai2<$nilai1)
								{
									$tanda='-';
								}
								else if($nilai2==0)
								{
									$tanda='';
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
									$nilai="<font color='red'>".$nilai2.$tanda."</font>";
									$bgcolor="#ffc0cb ";
								}
								else if(71<=$nilai2 and $nilai2<=72.9)
								{
									$nilai="<font color='black'>".$nilai2.$tanda."</font>";
									$bgcolor="yellow ";
								}
								else if($nilai2==73)
								{
									$nilai="<font color='green'>".$nilai2.$tanda."</font>";
									$bgcolor="#00ff00";
								}
								else if($nilai2>73)
								{
									$nilai="<font color='white'>".$nilai2.$tanda."</font>";
									$bgcolor="blue";
								}
								$nilai1=$nilai2;

									?>
									<td  bgcolor="<?php echo $bgcolor;?>"><u><a onclick="get_dtl('<?php echo $hd->kode.'_'.$panelis_selected.'_'.$item_selected.'_'.$hd->tanggal;?>');"><?php echo $nilai; ?></a></u></td>
									
									<?php
								}
							?>
							</tr>
							<?php
							}
							?>
							
								
							<?php
							if($panelis_selected==3)
							{
								 
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->num_rows();
								if($numk>0)
								{
									$total=$num+3;
							?>
								<tr>
								
								<td colspan="<?php echo $total?>">Kesimpulan</td>
								</tr>								
							<?php	
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
									foreach($kes_hdr as $kes_hdr)
									{
							?>		
									<tr>
									
									<td><?php echo $kes_hdr->panelis;?></td>
									<td><?php 
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
									echo $parameter;?></td>
									<td>Kesimpulan</td>
							<?php
											
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
							?>
							
									<td><?php echo $kesimpulan?></td>
							<?php
										}
									?>
									</tr>
									<tr>
									<td><?php echo $kes_hdr->panelis;?></td>
									<td><?php
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

									echo $parameter;?></td>
									<td>Action Plan</td>
							<?php
										
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
							?>
											<td><?php echo $saran?></td>
							<?php
										}
							?>
									</tr>
										
							<?php
											
											
									}
							?>
										<tr>
										<td colspan="3">Sumber Masalah</td>
							<?php 
										
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
											$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->row();
											?>
											<td><?php echo $sumber_masalah;?></td>
																						
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Deskripsi Masalah</td>
							<?php 
										
										foreach($hdr as $hd)
										{
											$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
											if($num_desc_sm>0)
											{
												$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->row();
												$desc=nl2br(htmlspecialchars($desc_sm->deskripsi));
											}
											else
											{
												$desc="";
											}
											
											
											?>
											<td><?php echo $desc ?></td>
											
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Action Plan</td>
							<?php 
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
											}?>
											<td><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
							<?php
									
									
								}
							}
							else
							{
								?>
									<tr>
										<td colspan="3">Kesimpulan</td>
							<?php 
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
											}?>
											<td><?php echo nl2br(htmlspecialchars($kesimpulan));?></td>
										<?php
										}
							?>
										</tr>
								<tr>
										<td colspan="3">Sumber Masalah</td>
							<?php 
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
											$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->row();
											?>
											<td colspan-"2"><?php echo nl2br(htmlspecialchars($sumber_masalah));?></td>
																						
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Deskripsi Masalah</td>
							<?php 
										foreach($hdr as $hd)
										{
											
											$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->num_rows();
											if($num_desc_sm>0)
											{
											$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$panelis_selected)->row();
											?>
											<td><?php echo nl2br(htmlspecialchars($desc_sm->deskripsi));?></td>
											
										<?php
											}
											else
											{
										?>
											<td></td>
										<?php
											}
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Action Plan</td>
							<?php 
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
											}?>
											<td><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
								<?php
							}
							?>
							</table>
							</div>
							<?php
							}
							?>
							</div>
					</div>
				</div>			
		
			</div>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel"></h4>
						</div>
						<div class="modal-body">	
							<table  id="tabel" class=" table table-striped table-bordered"></table>
							<p id="saran"></p>
						</div>
					</div>
				</div>
				<br>
			</div>
			
			<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel2"></h4>
						</div>
						<div class="modal-body">	
							<h5 id="deskripsi_kode"></h5>
							<p id="deskripsi_tgl"></p>
							<p id="deskripsi_risetman"></p>
							<p id="deskripsi_formulaby"></p>
							<p id="deskripsi_tujuan"></p>
							<p id="deskripsi_status"></p>
							<button type="button"><a href="" id="prn" target="_blank">Print</a></button>

							<table  id="tabel2" class=" table table-striped table-bordered"></table>
						</div>
					</div>
				</div>
				<br>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function() {
			$('#item').select2();
			$('#kode1').select2();
			$('#kode2').select2();
			$('#kode3').select2();
			$('#kode4').select2();
			$('#kode5').select2();
			});
//$('table').excelTableFilter();
		</script>
	
		</body>
</html>