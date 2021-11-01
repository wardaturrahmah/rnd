<html lang="en">
	<head>
		<title>Laporan VS</title>
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
			
			function get_formula(id)
			{
				var kode =  id;
					$.ajax({
						url:'<?php echo base_url();?>tabel/get_formula',
						method:'post',
						data:{
								kode:kode,
							},
						success:function(data)
						{
								
								 var html = '';
								 var judul = '';
								 var i=0;
								 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
								//membuat header kolom
								 html='<thead ><th class="text-center">Kode Bahan</th><th class="text-center">Kategori</th><th class="text-center">Kadar</th></thead>';							//mengisi kolom anggota keluarga
								 for(i=0; i<obj.length; i++){
									 var score=Math.round(obj[i].kadar*10)/10;
									html += '<tr align=left><td>'+obj[i].kode_bahan+'</td><td>'+obj[i].kategori+'</td><td>'+score+'</td></tr>';
								}
								
								
								var tgl2 =new Date(obj[0].tgl_riset)
								var tanggal = tgl2.getDate()+ '-' + (tgl2.getMonth() + 1) + '-' + tgl2.getFullYear();

								judul='Formula '+obj[0].kode+' Tanggal '+tanggal;
								//menaruh variabel html pada tabel
								 $('#tabel2').html(html);
								 
								 $('#myModalLabel2').html(judul);
								 $('#deskripsi_kode').html('Seri Formula :'+obj[0].kode);
								 $('#deskripsi_tgl').html('Tanggal Riset :'+tanggal);
								 $('#deskripsi_risetman').html('Risetman :'+obj[0].risetman_hdr);
								 $('#deskripsi_formulaby').html('Formula By :'+obj[0].risetman);
								 $('#deskripsi_tujuan').html('Tujuan :'+obj[0].tujuan);
								 var status="";
								 if(obj[0].approve1==1)
								 {
									 status="approve by risetman";
								 }
								 else if(obj[0].approve1==-1)
								 {
									 status="Drop by risetman";
								 }
								 if(obj[0].approve2==1)
								 {
									 status="approve by internal";
								 }
								 else if(obj[0].approve1==-1)
								 {
									 status="Drop by internal";
								 }
								 if(obj[0].approve3==1)
								 {
									 status="approve by Taste Specialist";
								 }
								 else if(obj[0].approve3==-1)
								 {
									 status="Drop by Taste Specialist";
								 }
								 $('#deskripsi_status').html('Status :'+status);
							 

						}
						
					});	
					$.ajax({
								url:'<?php echo base_url();?>mkt/get_tabel_sarana_formula',
								method:'post',
								data:{
										id_formula:kode,
									},
								success:function(data)
								{
									 var html2 = '';
									 var judul = '';
									 var i=0;
									 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
									 html2='<thead ><th class="text-center">Sarana</th></thead>';
									 for(i=0; i<obj.length; i++){
										 
										html2 += '<tr align=left><td>'+obj[i].sarana+'</td></tr>';
									}
									$('#tabel3').html(html2);
								}
							});		
				document.getElementById("prn").href="print_formula/"+kode; 

				$('#myModal2').modal('show');
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
							<h2>Laporan VS : Laporan yang digunakan untuk memversuskan 5 formula pada produk</h2>
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
												<option <?php echo $panelis_selected == 0 ? 'selected="selected"' : '' ?> value="0">All</option>
													
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Type</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="type" id="type" >
											<option value=1 <?php echo $type == 1 ? 'selected="selected"' : '' ?>>Group By Panelis</option>
											<option value=2 <?php echo $type == 2 ? 'selected="selected"' : '' ?>>Group By Subvar</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="kode1" id="kode1" class="kode form-control" required>
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
										<select name="kode2" id="kode2" class="kode form-control" required>
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
										<select name="kode3" id="kode3" class="kode form-control" required>
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
										<select name="kode4" id="kode4" class="kode form-control" required>
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
										<select name="kode5" id="kode5" class="kode form-control" required>
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
							<?php
							
							if($panelis_selected==0)
							{
								$panelis_selected=1;
								$ke=0;
							}
							else
							{
								$ke=$panelis_selected;
							}
							
							if($type==1)
							{
							?>
							<div class="x_content">
							<?php
							
							$num=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->num_rows();
							if($num>0)
							{
							
							
								$jdl=$this->Transaksi_model->resume_item($item_selected)->row();
								$jdl2=$this->Transaksi_model->lama_waktu2($item_selected,$ke)->row();
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
									<td valign="top" width="25%">Target Riset</td>
									<td width="75%"><?php echo nl2br(htmlspecialchars($jdl->kompetitor))?></td>
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
										<u><a href="<?php echo base_url().'mkt/kompetitor_dtl/'.$link->id_kompetitor.'-'.$item_selected?>"  target="_blank"><?php echo $link->nama.',';?></a></u>
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
							<?php
							if($ke==0)
							{
								for($panelis_selected=1;$panelis_selected<=3;$panelis_selected++)
								{
									$hdr=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
									if(count($hdr)>0)
									{
									if($panelis_selected==1)
									{
										echo '</br><h3>Risetman</h3></br>';
									}
									else if($panelis_selected==2)
									{
										echo '</br><h3>Internal</h3></br>';
									}
									else if($panelis_selected==3)
									{
										echo '</br><h3>Taste Specialist</h3></br>';
									}
							?>
							<div style="overflow-x:auto;overflow-y:auto;height:350px;">
							
							<table class="table table-bordered">
							<thead>
							<th><center>Panelis</center></th>
							<th><center>Var</center></th>
							<th><center>Subvar</center></th>
							<?php
							
							foreach($hdr as $hd)
							{
							?>
								<th colspan="3"><center><u><a onclick="get_formula('<?php echo $hd->id_formula?>');"><?php echo $hd->kode.'<br>'.date('d-m-Y',strtotime($hd->tanggal));?></a></u></center></th>
							<?php
								
							}
							?>
							</thead>
							<tr>
							<?php
							$data=$this->Transaksi_model->nilai_kode_vs($item_selected,$panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
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
							?>
							<?php
								$panelis2=$dt->panelis;
								if($panelis2!=$panelis1)
								{
							?>
									<td rowspan="<?php echo $var[$dt->panelis]?>"><?php echo $dt->panelis; ?></td>
							<?php
								}
								$panelis1=$panelis2;
							?>
							<?php
								$var2=$dt->varr;
								if($var2!=$var1)
								{
							?>
									<td rowspan="<?php echo $var[$dt->varr]?>"><?php echo $dt->varr; ?></td>
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
									$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
									$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
									$nilai2=round((float)$dt->$vnilai,2);
									$skala2=round((float)$dt->$skala,2);
									
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
									else if(71<=$nilai2 and $nilai2<73)
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
						
									<td bgcolor="<?php echo $bgcolor;?>"><?php echo $nilai; ?></td>
									<td><?php echo $skala2; ?></td>
									<td><?php echo $dt->$keterangan; ?></td>
									
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
									$total=($num*3)+3;
							?>
								<tr>
								
								<td colspan="<?php echo $total?>">Kesimpulan</td>
								</tr>								
							<?php	
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									
									foreach($kes_hdr as $kes_hdr)
									{?>
										<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
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
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>			
									
									
									
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
							
									<td colspan="3"><?php echo $kesimpulan?></td>
							<?php
										}
									?>
									</tr>
									<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
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
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>		
									
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
											<td colspan="3"><?php echo $saran?></td>
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
											<td colspan="3"><?php echo $sumber_masalah;?></td>
																						
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
											<td colspan="3"><?php echo $desc ?></td>
											
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Sarana</td>
											<?php 
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td colspan="3"><?php echo $sarana;?></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($kesimpulan));?></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($sumber_masalah));?></td>
																						
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($desc_sm->deskripsi));?></td>
											
										<?php
											}
											else
											{
										?>
											<td colspan="3"></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Sarana</td>
											<?php 
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td colspan="3"><?php echo $sarana;?></td>
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
								}
							}
							else
							{
									?>
									
									<div style="overflow-x:auto;overflow-y:auto;height:350px;">
									<table class="table table-bordered">
									<thead>
									<th><center>Panelis</center></th>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									<?php
									$hdr=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
									foreach($hdr as $hd)
									{
									?>
										<th colspan="3"><center><u><a onclick="get_formula('<?php echo $hd->id_formula?>');"><?php echo $hd->kode.'<br>'.date('d-m-Y',strtotime($hd->tanggal));?></a></u></center></th>
									<?php
										
									}
									?>
									</thead>
									<tr>
									<?php
									$data=$this->Transaksi_model->nilai_kode_vs($item_selected,$panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
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
									?>
									<?php
										$panelis2=$dt->panelis;
										if($panelis2!=$panelis1)
										{
									?>
											<td rowspan="<?php echo $var[$dt->panelis]?>"><?php echo $dt->panelis; ?></td>
									<?php
										}
										$panelis1=$panelis2;
									?>
									<?php
										$var2=$dt->varr;
										if($var2!=$var1)
										{
									?>
											<td rowspan="<?php echo $var[$dt->varr]?>"><?php echo $dt->varr; ?></td>
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
											$keterangan='keterangan '.$hd->id_formula.' '.$hd->tanggal;
											$skala='skala '.$hd->id_formula.' '.$hd->tanggal;
											$nilai2=round((float)$dt->$vnilai,2);
											$skala2=round((float)$dt->$skala,2);
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
											else if(71<=$nilai2 and $nilai2<73)
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
								
											<td bgcolor="<?php echo $bgcolor;?>"><?php echo $nilai; ?></td>
											<td><?php echo $skala2; ?></td>
											<td><?php echo $dt->$keterangan; ?></td>
											
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
											$total=($num*3)+3;
									?>
										<tr>
										
										<td colspan="<?php echo $total?>">Kesimpulan</td>
										</tr>								
									<?php	
											$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
											$panelis1='';
											$panelis2='';
											$var1='';
											$var2='';
											
											foreach($kes_hdr as $kes_hdr)
											{?>
												<tr>
											<?php
												$panelis2=$kes_hdr->panelis;
												if($panelis2!=$panelis1)
												{?>
													<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
											<?php
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
												if($var2!=$var1)
												{?>
													<td rowspan="2"><?php echo $parameter?></td>		
											<?php
												}
												
												$var1=$var2;
											?>			
											
											
											
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
									
											<td colspan="3"><?php echo $kesimpulan?></td>
									<?php
												}
											?>
											</tr>
											<tr>
											<?php
												$panelis2=$kes_hdr->panelis;
												if($panelis2!=$panelis1)
												{?>
													<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
											<?php
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
												if($var2!=$var1)
												{?>
													<td rowspan="2"><?php echo $parameter?></td>		
											<?php
												}
												
												$var1=$var2;
											?>		
											
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
													<td colspan="3"><?php echo $saran?></td>
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
													<td colspan="3"><?php echo $sumber_masalah;?></td>
																								
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
													<td colspan="3"><?php echo $desc ?></td>
													
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
													<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
												<?php
												}
									?>
												</tr>
												<tr>
												<td colspan="3">Sarana</td>
													<?php 
												foreach($hdr as $hd)
												{
													$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
													if(count($sar)>0)
													{
														$sarana=$sar->sarana;
													}
													else
													{
														$sarana="";
													}?>
													<td colspan="3"><?php echo $sarana;?></td>
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
													<td colspan="3"><?php echo nl2br(htmlspecialchars($kesimpulan));?></td>
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
													<td colspan="3"><?php echo nl2br(htmlspecialchars($sumber_masalah));?></td>
																								
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
													<td colspan="3"><?php echo nl2br(htmlspecialchars($desc_sm->deskripsi));?></td>
													
												<?php
													}
													else
													{
												?>
													<td colspan="3"></td>
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
													<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
												<?php
												}
									?>
												</tr>
												<tr>
												<td colspan="3">Sarana</td>
													<?php 
												foreach($hdr as $hd)
												{
													$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
													if(count($sar)>0)
													{
														$sarana=$sar->sarana;
													}
													else
													{
														$sarana="";
													}?>
													<td colspan="3"><?php echo $sarana;?></td>
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
							}
							?>
							</div>
							<?php
							}
							else
							{
								
							
							?>
							<div class="x_content">
							<?php
							
							$num=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->num_rows();
							if($num>0)
							{
							
							
								$jdl=$this->Transaksi_model->resume_item($item_selected)->row();
								$jdl2=$this->Transaksi_model->lama_waktu2($item_selected,$ke)->row();
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
									<td valign="top" width="25%">Target Riset</td>
									<td width="75%"><?php echo nl2br(htmlspecialchars($jdl->kompetitor))?></td>
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
										<u><a href="<?php echo base_url().'mkt/kompetitor_dtl/'.$link->id_kompetitor.'-'.$item_selected?>"  target="_blank"><?php echo $link->nama.',';?></a></u>
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
							<?php
							if($ke==0)
							{
								for($panelis_selected=1;$panelis_selected<=3;$panelis_selected++)
								{
									$hdr=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
									if(count($hdr)>0)
									{
									if($panelis_selected==1)
									{
										echo '</br><h3>Risetman</h3></br>';
									}
									else if($panelis_selected==2)
									{
										echo '</br><h3>Internal</h3></br>';
									}
									else if($panelis_selected==3)
									{
										echo '</br><h3>Taste Specialist</h3></br>';
									}
							?>
							<div style="overflow-x:auto;overflow-y:auto;height:350px;">
							<table class="table table-bordered">
							<thead>
							
							<th><center>Var</center></th>
							<th><center>Subvar</center></th>
							<th><center>Panelis</center></th>
							<?php
							
							foreach($hdr as $hd)
							{
							?>
								<th colspan="3"><center><u><a onclick="get_formula('<?php echo $hd->id_formula?>');"><?php echo $hd->kode.'<br>'.date('d-m-Y',strtotime($hd->tanggal));?></a></u></center></th>
							<?php
								
							}
							?>
							</thead>
							<tr>
							<?php
							$data=$this->Transaksi_model->nilai_kode_vs2($item_selected,$panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
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
									echo '<td rowspan="'.$var[$dt->varr].'">'.$dt->varr.'</td>';
								}
								$var1=$var2;
								
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									echo '<td rowspan="'.$var[$dt->subvar].'">'.$dt->subvar.'</td>';
								}
								$subvar1=$subvar2;
							?>
							
							<td><?php echo $dt->panelis; ?></td>
							<?php
								$nilai1=0;
								$nilai2=0;
								$k=0;
								
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
									else if(71<=$nilai2 and $nilai2<73)
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
						
									<td bgcolor="<?php echo $bgcolor;?>"><?php echo $nilai; ?></td>
									<td><?php echo $skala2; ?></td>
									<td><?php echo $dt->$keterangan; ?></td>
									
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
									$total=($num*3)+3;
							?>
								<tr>
								
								<td colspan="<?php echo $total?>">Kesimpulan</td>
								</tr>								
							<?php	
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									
									foreach($kes_hdr as $kes_hdr)
									{?>
										<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
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
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>			
									
									
									
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
							
									<td colspan="3"><?php echo $kesimpulan?></td>
							<?php
										}
									?>
									</tr>
									<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
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
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>		
									
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
											<td colspan="3"><?php echo $saran?></td>
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
											<td colspan="3"><?php echo $sumber_masalah;?></td>
																						
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
											<td colspan="3"><?php echo $desc ?></td>
											
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Sarana</td>
											<?php 
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td colspan="3"><?php echo $sarana;?></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($kesimpulan));?></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($sumber_masalah));?></td>
																						
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($desc_sm->deskripsi));?></td>
											
										<?php
											}
											else
											{
										?>
											<td colspan="3"></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Sarana</td>
											<?php 
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td colspan="3"><?php echo $sarana;?></td>
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
								}
							}
							else
							{
							?>
							<div style="overflow-x:auto;overflow-y:auto;height:350px;">
							<table class="table table-bordered">
							<thead>
							
							<th><center>Var</center></th>
							<th><center>Subvar</center></th>
							<th><center>Panelis</center></th>
							<?php
							$hdr=$this->Transaksi_model->hdr_kode_vs($panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
							foreach($hdr as $hd)
							{
							?>
								<th colspan="3"><center><u><a onclick="get_formula('<?php echo $hd->id_formula?>');"><?php echo $hd->kode.'<br>'.date('d-m-Y',strtotime($hd->tanggal));?></a></u></center></th>
							<?php
								
							}
							?>
							</thead>
							<tr>
							<?php
							$data=$this->Transaksi_model->nilai_kode_vs2($item_selected,$panelis_selected,$kode1,$kode2,$kode3,$kode4,$kode5)->result();
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
									echo '<td rowspan="'.$var[$dt->varr].'">'.$dt->varr.'</td>';
								}
								$var1=$var2;
								
								$subvar2=$dt->subvar;
								if($subvar2!=$subvar1)
								{
									echo '<td rowspan="'.$var[$dt->subvar].'">'.$dt->subvar.'</td>';
								}
								$subvar1=$subvar2;
							?>
							
							<td><?php echo $dt->panelis; ?></td>
							<?php
								$nilai1=0;
								$nilai2=0;
								$k=0;
								
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
									else if(71<=$nilai2 and $nilai2<73)
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
						
									<td bgcolor="<?php echo $bgcolor;?>"><?php echo $nilai; ?></td>
									<td><?php echo $skala2; ?></td>
									<td><?php echo $dt->$keterangan; ?></td>
									
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
									$total=($num*3)+3;
							?>
								<tr>
								
								<td colspan="<?php echo $total?>">Kesimpulan</td>
								</tr>								
							<?php	
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									
									foreach($kes_hdr as $kes_hdr)
									{?>
										<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
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
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>			
									
									
									
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
							
									<td colspan="3"><?php echo $kesimpulan?></td>
							<?php
										}
									?>
									</tr>
									<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
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
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>		
									
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
											<td colspan="3"><?php echo $saran?></td>
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
											<td colspan="3"><?php echo $sumber_masalah;?></td>
																						
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
											<td colspan="3"><?php echo $desc ?></td>
											
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Sarana</td>
											<?php 
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td colspan="3"><?php echo $sarana;?></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($kesimpulan));?></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($sumber_masalah));?></td>
																						
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($desc_sm->deskripsi));?></td>
											
										<?php
											}
											else
											{
										?>
											<td colspan="3"></td>
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
											<td colspan="3"><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
										</tr>
										<tr>
										<td colspan="3">Sarana</td>
											<?php 
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td colspan="3"><?php echo $sarana;?></td>
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
							}
							?>
							</div>
							<?php
							}
							?>
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
							<table  id="tabel3" class=" table table-striped table-bordered"></table>
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