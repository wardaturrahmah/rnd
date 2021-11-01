<html lang="en">
	<head>
		<title>Resume Formula</title>
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
			<div class="page-title">
				<div class="title_left">
					<h3>
						<a class="btn btn-danger" href="<?php echo $form2?>"><i class="fa fa-home">HOME</i></a>
						<a class="btn btn-success" href="<?php echo $form3?>">EXCEL</a>
					</h3>
				</div>
				
			</div>
		
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Resume Formula</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
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
							
						</div>
					</div>
				</div>			
		
			</div>
			
			
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>List Panelis</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								
								
								<?php 
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
								?>

							</div>
					</div>
				</div>			
		
			</div>
			
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#item').select2();
			});

		</script>
	
		</body>
</html>