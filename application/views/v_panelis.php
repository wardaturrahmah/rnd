<html lang="en">
	<head>
		<title>Master panelis</title>
		<script>
		function check_length(my_text,pj)
		{
			var ket=document.getElementsByName(my_text)[0];
			 maxLen = pj; // max number of characters input
			if (ket.value.length > maxLen) {
			// Alert message if maximum limit is reached. 
			// If required Alert can be removed. 
			var msg = "Teks anda terlalu panjang. Teks akan terpotong";
			alert(msg);
			// Reached the Maximum length so trim the textarea
				ket.value = ket.value.substring(0, maxLen);
			 }
			else{ // Maximum length not reached so update the value of my_text counter
				
			} 
		}
		</script>
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
			
				<div class="title_left">
					<h3>Master</h3>
				</div>
				<?php if($this->session->flashdata('message_panelis')!='') 
						{
				?>
				<div class="page-title">

						<div class="alert alert-danger alert-dismissible " role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<strong><?php echo $this->session->flashdata('message_panelis')?></strong>
					</div>
				</div>
				<?php } 
				if($auth_menu->C==1)
				{
				?>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>panelis</h2>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input name="panelis" type="text" class="form-control" value="<?php echo  isset($default['panelis']) ? $default['panelis'] : ''; ?>" onKeyUp="check_length('panelis',50)">
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
			<?php
				}
			?>
			<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Tabel panelis</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								  </li>
								</ul>
									<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<?php echo ! empty($table) ? $table : '';
									 echo ! empty($note) ? $note : '';
								?>		   
							</div>
						</div>
					</div>
			</div>
		</div>
		
	</body>
</html>