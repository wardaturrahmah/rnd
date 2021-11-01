<html lang="en">
	<head>
		<title>Akses Item</title>
		<script>
		$(document).ready(function() {
			var id = "<?php echo $id; ?>";
			var table = $('#datatable').DataTable();
			$('#submit').click( function() {
			var data = table.$('input, select').serialize();
				 $.ajax({
					 url: '../edit_akses_lp/'+id,
					 method:'post',
					 data: data,
					 
					 success: function(data){
						//console.log('Server response', data);
						location.reload();

					 }
				  });
	
			} );
		} );

 
		</script>
	</head>
	<body class="nav-md">            
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
							<h2>Akses Line <?php echo $produk; ?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								
								<?php
									echo '<table class="table table-striped table-bordered" id="datatable">';
									echo '<thead>';
									echo '<th>Username</th>';
									echo '<th>Akses</th>';
									echo '</thead>';
									
								foreach($akses as $ak)
								{
									if($ak->akses==1)
									{
										$c='checked="checked"';
									}
									else
									{
										$c="";
									}
									$name1="c-".$ak->id_user;
									
									echo '<tr>';
									echo '<td>'.$ak->username.'</td>';
									echo '<td><input name="'.$name1.'" type="checkbox" '.$c.' value="'.$ak->id_user.'"></td>';
									echo '</tr>';
									
									
								}
									echo '</table>';
								?>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										
										<a id="submit" class="btn btn-success">Simpan</a>
										<a href="<?php echo $form3?>" class="btn btn-danger">Back</a>
										
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</body>
</html>