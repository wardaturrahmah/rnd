<html lang="en">
	<head>
		<title>Akses Item</title>
		
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
			<div class="page-title">
				<div class="title_left">
					<h3>
						Akses Item
					</h3>

				</div>
		
			</div>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>List Formula</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								<?php
								if(count($list)>0)
								{
								?>
								<table id="datatable" class="table table-striped table-bordered">
									<thead>
										<th>Nama Item</th>
										<th>Line</th>
										<th>Risetman</th>
										<th>Target Riset</th>
										<th>Awal riset</th>
										<th>Status</th>
										<th>Tanggal Status</th>
										<th>Keterangan Status</th>
										<th>Action</th>
									</thead>
									<?php 
									foreach($list as $list)
									{
										
										if($list->status==0)
										{
											$status="Progress";
											$tgl_status=date('d-m-Y');
										}
										else if($list->status==1)
										{
											$status="Launching";
											$tgl_status=date('d-m-Y',strtotime($list->tgl_status));
										}
										else if($list->status==-1)
										{
											$status="Terminate";
											$tgl_status=date('d-m-Y',strtotime($list->tgl_status));
										}
										else if($list->status==2)
										{
											$status="Bank Produk-ACC";
											$tgl_status=date('d-m-Y',strtotime($list->tgl_status));
										}
										$cek=$this->Transaksi_model->pending_now($list->id)->row();
										if(count($cek)>0)
										{
											$status="Pending";
											$tgl_status=date('d-m-Y',strtotime($cek->tgl_awal));
										}
									
									?>
									<tr>
										<td><?php echo $list->nama_item; ?></td>
										<td><?php echo $list->lineproduk; ?></td>
										<td><?php echo $list->risetman; ?></td>
										<td><?php echo nl2br(htmlspecialchars($list->kompetitor)); ?></td>
										<td data-sort="<?php echo strtotime($list->awal_riset)?>"><?php echo date('d-m-Y',strtotime($list->awal_riset))?></td>
										<td><?php echo $status;?></td>
										<td data-sort="<?php echo strtotime($tgl_status)?>"><?php echo $tgl_status;?></td>
										<td><?php echo nl2br(htmlspecialchars($list->ket_status)); ?></td>
										<td><a href="<?php echo base_url('mkt/akses').'/'.$list->id;?>">
										<button type="button" class="btn"><i class="fa fa-cog blue"
										style="color:#000;"></i></button></a></td>
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