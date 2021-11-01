<html lang="en">
	<head>
		<title>Laporan Resume Formula</title>
		<script>
			function get_kode(){
			var id_item=$( "#item option:selected" ).val();
			var id_formula=<?php echo $id_formula;?>;
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
							$('#id_formula').html(html);//mengembalikan nilai option pada id kode			
							$('#id_formula').val(id_formula);
							

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
							<h2>Laporan Resume Formula: Laporan yang digunakan untuk resume formula dan hasil panelis</h2>
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
										<select name="id_formula" id="id_formula" class="kode form-control" required>
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
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
								<br />
								<?php
								$dt=$this->Transaksi_model->resume_formula($id_formula)->row();
								if(count($dt)>0)
								{
								?>
								<form class="form-horizontal form-label-left">
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Nama Produk :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->nama_item;?></label>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">P.Line :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->lineproduk;?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Risetman :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->risetman;?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Target Riset :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo nl2br(htmlspecialchars($dt->kompetitor));?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Awal Riset Produk:</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo date('d-m-Y',strtotime($dt->awal_riset));?></label>
										
									</div>
									
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Konsep Sebelumnya :</label>
										<?php
										if($dt->nama_konsep_sebelumnya!='')
										{
											?>
										<label class="col-md-9 col-sm-9 col-xs-12"><u><a href="<?php echo base_url().'/resume_produk/'.$dt->id_konsep_sebelumnya?>"><?php echo $dt->nama_konsep_sebelumnya;?></a></u></label>
										
										<?php
										}
										?>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Seri Formula :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->kode;?></label>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Tanggal Riset Formula :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo date('d-m-Y',strtotime($dt->tgl_riset));?></label>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Sumber Formula :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->risetman;?></label>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Tujun Formula :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->tujuan;?></label>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Link Formula :</label>
										<label class="col-md-9 col-sm-9 col-xs-12">
										<?php
										$link=$this->Master_model->get_ref_formula($id_formula)->result();
										foreach($link as $link)
										{
										?>
										<u><a href="<?php echo base_url().'/mkt/formula_dtl/'.$link->link_formula?>"  target="_blank"><?php echo $link->nama_item.' formula '.$link->kode.',';?></a></u>
										<?php
										}
										?>
										</label>
									</div>
									</br>
									<table  id="tabel" class=" table table-striped table-bordered">
									
									<?php $sarana=$this->Transaksi_model->formula_sarana_all($id_formula)->result();
										if(count($sarana)>0)
										{
									?>
											<th><center>Sarana</center></th>	
									<?php
											foreach($sarana as $sarana)
											{?>
												<td><center><?php echo $sarana->sarana;?></center></td>	
											<?php

											}
										}
										$bahan=$this->Transaksi_model->formula_bahan_all($id_formula)->result();
										if(count($bahan)>0)
										{
									?>
										<thead>
										<thead>
											<th><center>Bahan</center></th>	
											<th><center>Kadar</center></th>	
										</thead>
									<?php
											foreach($bahan as $bahan)
											{?>
											<tr>
												<td><center><?php echo $bahan->kode_bahan;?></center></td>
												<td><center><?php echo $bahan->kadar;?></center></td>	
											</tr>
											<?php

											}
										}
									?>
									
									</table>
								</form>
								<br />
								
								
								<?php 
								if($type==1)
								{
								$r1s=$this->Transaksi_model->penilaian_formula_list2($id_formula,1)->result();
								if(count($r1s)>0)
								{?>
								<h3>Panelis Risetman</h3>
								<table  id="tabel" class=" table table-striped table-bordered">
								<thead>
									<th><center>Nama</center></th>
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
								</thead>
								<?php
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
								?>
								<tr>
									<?php
										$panelis2=$r1->panelis.''.$r1->tanggal;
										if($panelis1!=$panelis2)
										{?>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r1->panelis;?></td>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r1->tanggal;?></td>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r1->tgl_real;?></td>

										<?php
										}
										$panelis1=$panelis2;
										$var2=$r1->varr;
										if($var1!=$var2)
										{?>
											<td rowspan="<?php echo $mer[$var2]?>"><?php echo $r1->varr;?></td>
										<?php
										}
										$var1=$var2;
											?>
									
									
									<td><?php echo $r1->subvar;?></td>
									<td><?php echo round($r1->nilai,2);?></td>
									<td><?php echo round($r1->skala,2);?></td>
									<td><?php echo $r1->keterangan;?></td>
								</tr>
								<?php
								}
								?>
								</table>
								
								<?php
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
											?>
										<table width="100%">
										<tr>
										<td valign="top" width="20%">Kesimpulan<td>
										<td><?php echo nl2br(htmlspecialchars($kesimpulan)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Sumber Masalah<td>
										<td><?php echo $sumber_masalah; ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Deskripsi Masalah<td>
										<td><?php echo nl2br(htmlspecialchars($desc)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Action Plan<td>
										<td><?php echo nl2br(htmlspecialchars($action)); ?><td>
										</tr>
										</table>
								<?php
								}
								?>
								<?php 
								$r2s=$this->Transaksi_model->penilaian_formula_list2($id_formula,2)->result();
								if(count($r2s)>0)
								{?>
								</br>
								<h3>Panelis Internal</h3>
								<table  id="tabel" class=" table table-striped table-bordered">
								<thead>
									<th><center>Nama</center></th>
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
								</thead>
								<?php
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
								?>
								<tr>
									<?php
										$panelis2=$r2->panelis.''.$r2->tanggal;
										if($panelis1!=$panelis2)
										{?>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r2->panelis;?></td>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r2->tanggal;?></td>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r2->tgl_real;?></td>

										<?php
										}
										$panelis1=$panelis2;
										$var2=$r2->varr;
										if($var1!=$var2)
										{?>
											<td rowspan="<?php echo $mer[$var2]?>"><?php echo $r2->varr;?></td>
										<?php
										}
										$var1=$var2;
											?>
									
									
									<td><?php echo $r2->subvar;?></td>
									<td><?php echo round($r2->nilai,2);?></td>
									<td><?php echo round($r2->skala,2);?></td>
									<td><?php echo $r2->keterangan;?></td>
								</tr>
								<?php
								}
								?>
								</table>
								<?php
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
											?>
										<table width="100%">
										<tr>
										<td valign="top" width="20%">Kesimpulan<td>
										<td><?php echo nl2br(htmlspecialchars($kesimpulan)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Sumber Masalah<td>
										<td><?php echo $sumber_masalah; ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Deskripsi Masalah<td>
										<td><?php echo nl2br(htmlspecialchars($desc)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Action Plan<td>
										<td><?php echo nl2br(htmlspecialchars($action)); ?><td>
										</tr>
										</table>
								<?php
								}
								?>
								<?php 
								$r3s=$this->Transaksi_model->penilaian_formula_list2($id_formula,3)->result();
								if(count($r3s)>0)
								{?>
								</br>
								<h3>Panelis Taste Specialist</h3>
								<table  id="tabel" class=" table table-striped table-bordered">
								<thead>
									<th><center>Nama</center></th>
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
								</thead>
								<?php
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
								?>
								<tr>
									<?php
										$panelis2=$r3->panelis.''.$r3->tanggal;
										if($panelis1!=$panelis2)
										{?>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r3->panelis;?></td>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r3->tanggal;?></td>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r3->tgl_real;?></td>

										<?php
										}
										$panelis1=$panelis2;
										$var2=$r3->varr;
										if($var1!=$var2)
										{?>
											<td rowspan="<?php echo $mer[$var2]?>"><?php echo $r3->varr;?></td>
										<?php
										}
										$var1=$var2;
											?>
									
									
									<td><?php echo $r3->subvar;?></td>
									<td><?php echo round($r3->nilai,2);?></td>
									<td><?php echo round($r3->skala,2);?></td>
									<td><?php echo $r3->keterangan;?></td>
								</tr>
								<?php
								}
								?>
								</table>
								
								<table  id="tabel" class=" table table-striped table-bordered">
							<?php 
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($dt->id_item)->num_rows();
								if($numk>0)
								{?>
							<thead>
							<td>Panelis</td>
							<td>Var</td>
							<td>Kesimpulan</td>
							<td>Action Plan</td>
							</thead>
							<?php
									//$total=($numkom*2)+9;
									
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($dt->id_item)->result();
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
									echo $parameter;
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
									?></td>
									<td><?php echo $kesimpulan; ?></td>
									<td><?php echo $saran; ?></td>
							
							
							<?php
										
										
									}
								}
								?>
								</table>
								
								<?php
									
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
											?>
										<table width="100%">
										
										<tr>
										<td valign="top" width="20%">Sumber Masalah<td>
										<td><?php echo $sumber_masalah; ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Deskripsi Masalah<td>
										<td><?php echo nl2br(htmlspecialchars($desc)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Action Plan<td>
										<td><?php echo nl2br(htmlspecialchars($action)); ?><td>
										</tr>
										</table>
								<?php
								}
								}
								else
								{
								
								
								$r1s=$this->Transaksi_model->penilaian_formula_list2($id_formula,1)->result();
								if(count($r1s)>0)
								{?>
								<h3>Panelis Risetman</h3>
								<table  id="tabel" class=" table table-striped table-bordered">
								<thead>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Nama</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
								</thead>
								<?php
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
								$mtgl1='';
								$mtgl2='';
								$var1='';
								$var2='';
								$mtglr1='';
								$mtglr2='';
								foreach($r1s as $r1)
								{
								?>
								<tr>
								<?php
										$var2=$r1->varr;
										if($var1!=$var2)
										{?>
											<td rowspan="<?php echo $mer[$var2]?>"><?php echo $r1->varr;?></td>
										<?php
										}
										$var1=$var2;
										?>
										<?php
										$subvar2=$r1->subvar;
										if($subvar1!=$subvar2)
										{?>
											<td rowspan="<?php echo $mer[$subvar2]?>"><?php echo $r1->subvar;?></td>
										<?php
										}
										$subvar1=$subvar2;
										?>
										
										<?php
										$mtgl2=$r1->tanggal;
										if($mtgl1!=$mtgl2)
										{
											echo '<td rowspan="'.$mer[$mtgl2].'">'.$r1->tanggal.'</td>';
										}
										$mtgl1=$mtgl2;
										
										$mtglr2=$r1->tgl_real;
										if($mtglr1!=$mtglr2)
										{
											echo '<td rowspan="'.$mer[$mtglr2.'r'].'">'.$r1->tgl_real.'</td>';
										}
										$mtglr1=$mtglr2;
										?>
									
										<td><?php echo $r1->panelis;?></td>
										
									<td><?php echo round($r1->nilai,2);?></td>
									<td><?php echo round($r1->skala,2);?></td>
									<td><?php echo $r1->keterangan;?></td>
								</tr>
								<?php
								}
								?>
								</table>
								
								<?php
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
											?>
										<table width="100%">
										<tr>
										<td valign="top" width="20%">Kesimpulan<td>
										<td><?php echo nl2br(htmlspecialchars($kesimpulan)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Sumber Masalah<td>
										<td><?php echo $sumber_masalah; ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Deskripsi Masalah<td>
										<td><?php echo nl2br(htmlspecialchars($desc)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Action Plan<td>
										<td><?php echo nl2br(htmlspecialchars($action)); ?><td>
										</tr>
										</table>
								<?php
								}
								?>
								<?php 
								$r2s=$this->Transaksi_model->penilaian_formula_list3($id_formula,2)->result();
								if(count($r2s)>0)
								{?>
								</br>
								<h3>Panelis Internal</h3>
								<table  id="tabel" class=" table table-striped table-bordered">
								<thead>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Nama</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
								</thead>
								<?php
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
								?>
								<tr>
									<?php
									$var2=$r2->varr;
										if($var1!=$var2)
										{?>
											<td rowspan="<?php echo $mer[$var2]?>"><?php echo $r2->varr;?></td>
										<?php
										}
										$var1=$var2;
										
										$subvar2=$r2->subvar;
										if($subvar1!=$subvar2)
										{?>
											<td rowspan="<?php echo $mer[$subvar2]?>"><?php echo $r2->subvar;?></td>
										<?php
										}
										$subvar1=$subvar2;
										
											?>
										<?php
										$mtgl2=$r2->tanggal;
										if($mtgl1!=$mtgl2)
										{
											echo '<td rowspan="'.$mer[$mtgl2].'">'.$r2->tanggal.'</td>';
										}
										$mtgl1=$mtgl2;
										
										$mtglr2=$r2->tgl_real;
										if($mtglr1!=$mtglr2)
										{
											echo '<td rowspan="'.$mer[$mtglr2.'r'].'">'.$r2->tgl_real.'</td>';
										}
										$mtglr1=$mtglr2;
										?>

										<td><?php echo $r2->panelis;?></td>
										
								
									<td><?php echo round($r2->nilai,2);?></td>
									<td><?php echo round($r2->skala,2);?></td>
									<td><?php echo $r2->keterangan;?></td>
								</tr>
								<?php
								}
								?>
								</table>
								<?php
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
											?>
										<table width="100%">
										<tr>
										<td valign="top" width="20%">Kesimpulan<td>
										<td><?php echo nl2br(htmlspecialchars($kesimpulan)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Sumber Masalah<td>
										<td><?php echo $sumber_masalah; ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Deskripsi Masalah<td>
										<td><?php echo nl2br(htmlspecialchars($desc)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Action Plan<td>
										<td><?php echo nl2br(htmlspecialchars($action)); ?><td>
										</tr>
										</table>
								<?php
								}
								?>
								<?php 
								$r3s=$this->Transaksi_model->penilaian_formula_list3($id_formula,3)->result();
								if(count($r3s)>0)
								{?>
								</br>
								<h3>Panelis Taste Specialist</h3>
								<table  id="tabel" class=" table table-striped table-bordered">
								<thead>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Nama</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
								</thead>
								<?php
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
								?>
								<tr>
									<?php
									$var2=$r3->varr;
										if($var1!=$var2)
										{?>
											<td rowspan="<?php echo $mer[$var2]?>"><?php echo $r3->varr;?></td>
										<?php
										}
										$var1=$var2;
										
										$subvar2=$r3->subvar;
										if($subvar1!=$subvar2)
										{?>
											<td rowspan="<?php echo $mer[$subvar2]?>"><?php echo $r3->subvar;?></td>
										<?php
										}
										$subvar1=$subvar2;
										
											?>
										<?php
										$mtgl2=$r3->tanggal;
										if($mtgl1!=$mtgl2)
										{
											echo '<td rowspan="'.$mer[$mtgl2].'">'.$r3->tanggal.'</td>';
										}
										$mtgl1=$mtgl2;
										
										$mtglr2=$r3->tgl_real;
										if($mtglr1!=$mtglr2)
										{
											echo '<td rowspan="'.$mer[$mtglr2.'r'].'">'.$r3->tgl_real.'</td>';
										}
										$mtglr1=$mtglr2;
									//	echo '<td>'.$r3->tgl_real.$mer[$mtglr2.'r'].'</td>';
										?>

										<td><?php echo $r3->panelis;?></td>
										
									<td><?php echo round($r3->nilai,2);?></td>
									<td><?php echo round($r3->skala,2);?></td>
									<td><?php echo $r3->keterangan;?></td>
								</tr>
								<?php
								}
								?>
								</table>
								
								<table  id="tabel" class=" table table-striped table-bordered">
							<?php 
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($dt->id_item)->num_rows();
								if($numk>0)
								{?>
							<thead>
							<td>Panelis</td>
							<td>Var</td>
							<td>Kesimpulan</td>
							<td>Action Plan</td>
							</thead>
							<?php
									//$total=($numkom*2)+9;
									
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($dt->id_item)->result();
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
									echo $parameter;
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
									?></td>
									<td><?php echo $kesimpulan; ?></td>
									<td><?php echo $saran; ?></td>
							
							
							<?php
										
										
									}
								}
								?>
								</table>
								
								<?php
									
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
											?>
										<table width="100%">
										
										<tr>
										<td valign="top" width="20%">Sumber Masalah<td>
										<td><?php echo $sumber_masalah; ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Deskripsi Masalah<td>
										<td><?php echo nl2br(htmlspecialchars($desc)); ?><td>
										</tr>
										<tr>
										<td valign="top" width="20%">Action Plan<td>
										<td><?php echo nl2br(htmlspecialchars($action)); ?><td>
										</tr>
										</table>
								<?php
								}
								}
								}
								?>
							</div>
					</div>			
		
				</div>
			
			</div>
		
		<script type="text/javascript">
			$(document).ready(function() {
			$('#item').select2();
			$('#id_formula').select2();
			});
//$('table').excelTableFilter();
		</script>
	
		</body>
</html>