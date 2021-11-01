<html lang="en">
	<head>
		<title>Akses</title>
		<script>
		$(document).ready(function() {
			var id = "<?php echo $id; ?>";
			var table = $('#datatable').DataTable();
			$('#submit').click( function() {
			var data = table.$('input, select').serialize();
				 $.ajax({
					 url: '<?php echo $form; ?>',
					 method:'post',
					 data: data,
					 
					 success: function(data){
						console.log('Server response', data);
						location.reload();

					 }
				  });
	
			} );
			$('#select-all').on('click', function(){
				  var rows = table.rows({ 'search': 'applied' }).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
		} );

		function toggle(source) 
		{			
			checkboxes =  document.getElementsByClassName('pil');
			for(var i=0, n=checkboxes.length;i<n;i++) 
			{
				checkboxes[i].checked = source.checked;
			}
		}

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
							<h2>Akses <?php echo $group_produk; ?> <?php echo $username; ?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" id="form_akses" action="<?php echo $form?>" method="post">
								
								<?php
									echo '<table class="table table-striped table-bordered" id="datatable">';
									echo '<thead>';
									echo '<th>'.$group_produk.'</th>';
									//echo '<th>Akses <input type="checkbox" onClick="toggle(this)" name="select_all" /></th>';
									echo '<th>Akses <input type="checkbox" name="select_all" id="select-all" /></th>';
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
									$name1="c-".$ak->id;
									
									echo '<tr>';
									echo '<td>'.$ak->nama_item.'</td>';
									echo '<td><input name="'.$name1.'" class="pil" type="checkbox" '.$c.' value="'.$ak->username.'"></td>';
									echo '</tr>';
									
									
								}
									echo '</table>';
								?>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<a class="btn btn-success" id="submit">Simpan</a>
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