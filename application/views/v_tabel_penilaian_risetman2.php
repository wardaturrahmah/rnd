<html lang="en">
	<head>
		<title>Laporan Penilaian Risetman</title>
		
	</head>
	<body class="nav-md" >

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
							<h2>Laporan Penilaian Risetman : Laporan jumlah formula yang dibuat oleh risetman beserta panelis terakhir tiap formula</h2>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Awal</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker'>
												<input type='text' class="form-control" name="tgl_awal" value="<?php echo  isset($tgl_awal) ? $tgl_awal : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Akhir</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker2'>
												<input type='text' class="form-control" name="tgl_akhir" value="<?php echo  isset($tgl_akhir) ? $tgl_akhir : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
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
								if($tgl1!='')
								{
									$tgl1=date('Y-m-d',strtotime($tgl1));
									$tgl2=date('Y-m-d',strtotime($tgl2));
									$num=$this->Transaksi_model->hdr_penilaian_risetmans_akses($tgl1,$tgl2)->num_rows();
									if($num>0)
									{?>
										<table class="table table-bordered table-intel" id="table">
										<thead>
										<th>Nama Risetman</th>
										<th>Produk Line</th>
										<th>Nama Produk</th>
										<th>Jumlah Formula</th>
										<th>Status Produk</th>
										</thead>
									<?php
									$ro=$this->Transaksi_model->hdr_penilaian_risetmans_akses($tgl1,$tgl2)->result();
									foreach($ro as $dt)
									{?>
										<tr>
											<td><?php echo $dt->risetman?></td>
											<td><?php echo $dt->lineproduk?></td>
											<td><?php echo $dt->nama_item?></td>
											<td><a href="tabel_dtl_risetman/<?php echo $dt->line.'_'.$dt->id_item.'_'.$tgl1.'_'.$tgl2.'_'.$dt->risetman;?>"><u><?php echo $dt->jumlah?></u></a></td>
											<td><?php 
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
											}											echo $status;
											
											?></td>
											
										</tr>
									<?php	
									}
									?>
										</table>
								<?php	}
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
			$('table').excelTableFilter();
		</script>
	
		</body>
</html>