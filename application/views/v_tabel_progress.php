<html lang="en">
	<head>
		<title>Tabel</title>
		
	</head>
	<body class="nav-md" onload="get_produk();">

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
							<h2>Item</h2>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Produk Line</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="line" id="line">
										<?php
											foreach ($line as $line) {
												?>
												<option <?php echo $line_selected == $line->id_lp ? 'selected="selected"' : '' ?> value="<?php echo $line->id_lp ?>">
													<?php echo $line->lineproduk ?>
												</option>
												<?php
											}	
										?>					
										</select>
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success">Submit</button>
										
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
							if($line_selected!='')
							{
								$num=$this->Transaksi_model->lama_waktu($line_selected)->num_rows();
								if($num>0)
								{
							?>
								<table class="table table-bordered">
									<thead>
										<th><center>Nama Produk</center></th>
										<th><center>Akhir Progress</center></th>
										<th><center>Tahun</center></th>
										<th><center>Bulan</center></th>
										<th><center>Minggu</center></th>
										<th><center>hari</center></th>
									</thead>
							<?php
									$ro=$this->Transaksi_model->rekap_progress($line_selected)->result();
									foreach($ro as $dt)
									{
										
							?>
									<tr>
										<td><?php echo $dt->nama_item;?></td>
										<td><?php 
										if(is_null($dt->tgl_panelis))
										{
										$tgl=$dt->tgl_formula;
										}
										else
										{
											if($dt->tgl_panelis>=$dt->tgl_formula)
											{
												$tgl=$dt->tgl_panelis;
											}
											else
											{
												$tgl=$dt->tgl_formula;
											} 
										}
										echo $tgl;?></td>
										<td><?php 
										$tanggal  = strtotime($tgl);
										$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
										$total   = $sekarang - $tanggal;
										$tahun=floor($total/(60 * 60 * 24 * 365));
										$sisa=$total-($tahun*(60 * 60 * 24 * 365));
										$bulan=floor($sisa/(60 * 60 * 24 * 30));
										$hari_ini = date("Y-m-d");
										$tgl_awal=date('d',strtotime($tgl));
										$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
										$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										$minggu=floor($hari/7);
										$sisa=$hari-($minggu*7);
										$hari=$sisa;
										echo $tahun;
										 ?></td>
										<td><?php echo $bulan;?></td>
										<td><?php echo $minggu;?></td>
										<td><?php echo $hari;?></td>
									</tr>
							<?php
									}
							?>
								</table>
								
							<?php
								}
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
							<h4 class="modal-title" id="myModalLabel">Judul</h4>
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
							<h4 class="modal-title" id="myModalLabel2">Judul</h4>
						</div>
						<div class="modal-body">	
							<h5 id="deskripsi_kode"></h5>
							<p id="deskripsi_tgl"></p>
							<p id="deskripsi_risetman"></p>
							<p id="deskripsi_formulaby"></p>
							<p id="deskripsi_tujuan"></p>
							<p id="deskripsi_status"></p>

							<table  id="tabel2" class=" table table-striped table-bordered"></table>
						</div>
					</div>
				</div>
				<br>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#line').select2();

			});
			$('table').excelTableFilter();

		</script>
	
		</body>
</html>