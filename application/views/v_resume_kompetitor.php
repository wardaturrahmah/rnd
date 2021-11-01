<html lang="en">
	<head>
		<title>Resume Kompetitor</title>
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
								<h2>Resume Kompetitor</h2>
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
										<label class="col-md-3 col-sm-3 col-xs-12">Nama Kompetitor :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $dt->nama;?></label>
									</div>
									<?php
									if($dt->foto!="")
									{
									?>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Foto Poduk :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><img src="<?php echo base_url()?>/uploads/kompetitor/<?php echo $dt->foto;?>" width="70" height="90" /></label>
									</div>
									<?php
									}
									?>
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
								$r1s=$this->Transaksi_model->penilaian_kompetitor_list($id_formula,1)->result();
								if(count($r1s)>0)
								{?>
								<table  id="tabel" class=" table table-bordered">
								<thead>
									<th><center>Nama</center></th>
									<th><center>Tanggal Panelis</center></th>
									<th><center>Tanggal Real</center></th>
									<th><center>Var</center></th>
									<th><center>Subvar</center></th>
									<th><center>Nilai</center></th>
									<th><center>Skala</center></th>
									<th><center>Keterangan</center></th>
									<th><center>Kesimpulan</center></th>
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
									<?php
										if($panelis1!=$panelis2)
										{?>
											<td rowspan="<?php echo $mer[$panelis2]?>"><?php echo $r1->kesimpulan;?></td>
										<?php
										}
										$panelis1=$panelis2;
										?>
								</tr>
								<?php
								}
								?>
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