<html lang="en">
	<head>
		<title>Approve</title>
	</head>
	<body class="nav-md" onload="get_op();">            
		<div class="right_col" role="main">
			<div class="page-title">
				<div class="title_left">
					<h3></h3>
				</div>
			</div>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Approve <?php echo $produk; ?></h2>
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Formula</label>
											<div class='col-sm-4'>
												<div class="form-group">
													<div class='input-group date' id='myDatepicker'>
														<input type='text' class="form-control" name="tgl" value="<?php echo  isset($default['tgl']) ? $default['tgl'] : date('d-m-Y'); ?>"/>
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
										<button type="submit" class="btn btn-success">Cari</button>
										<a href="<?php echo $form3?>" class="btn btn-danger">Back</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Tabel Approve</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								  </li>
								</ul>
									<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<form class="form-horizontal form-label-left" action="<?php echo $form2?>" method="post">
								<?php 
								echo form_hidden('k',$default['k']) ;
								echo ! empty($table) ? $table : '';
								?>
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>  
							</div>
						</div>
					</div>
			</div>
		</div>
		
	</body>
</html>