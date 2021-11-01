<html lang="en">
	<head>
		<title>Tabel</title>
	</head>
	<body class="nav-md">

<?php
$this->load->model('Transaksi_model', '', TRUE);
$this->load->model('Master_model', '', TRUE);
?>	
		<div class="right_col" role="main">
			<div class="page-title">
				
				<div class="title_right">
				</div>
			</div>
			<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">

			<input name="panelis" type="hidden" class="form-control" value="<?php echo  isset($ke) ? $ke : ''; ?>">
			<input name="item" type="hidden" class="form-control" value="<?php echo  isset($items) ? $items : ''; ?>">				
			<button type="submit" class="btn btn-danger">BACK</button>
			</form>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>List Penilaian <?php echo $kode;?></h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
							
							<table class="table table-bordered">	
							<thead>
							<th>Panelis</th>
							<th>Var</th>
							<th>Subvar</th>
							<th>Nilai</th>
							<th>Komentar</th>
							</thead>
							<?php
							$list=$this->Transaksi_model->tabel_dtl2($items,$ke,$kode,$tanggal)->result();
							foreach($list as $list)
							{
							?>
							<tr>
							<td><?php echo $list->panelis;?></td>
							<td><?php echo $list->varr;?></td>
							<td><?php echo $list->subvar;?></td>
							<td><?php echo round($list->nilai,2);?></td>
							<td><?php echo $list->keterangan;?></td>
							</tr>
							<?php } ?>
							</table>
							
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