<html lang="en">
	<head>
		<title>Akses Menu</title>
		<script>
		$(document).ready(function() {
			var id = "<?php echo $id; ?>";
			var table = $('#datatable').DataTable();
			$('#submit').click( function() {
			var data = table.$('input, select').serialize();
				 $.ajax({
					 url: '../edit_akses_menu/'+id,
					 method:'post',
					 data: data,
					 
					 success: function(data){
						console.log('Server response', data);
						location.reload();

					 }
				  });
	
			} );
			$('#select-all-c').on('click', function(){
				  var rows = table.column(2).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
			   $('#select-all-r').on('click', function(){
				  var rows = table.column(3).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
			   $('#select-all-u').on('click', function(){
				  var rows = table.column(4).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
			   $('#select-all-d').on('click', function(){
				  var rows = table.column(5).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
			   $('#select-all-a').on('click', function(){
				  var rows = table.column(6).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
			   $('#select-all-ua').on('click', function(){
				  var rows = table.column(7).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			   });
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
							<h2>Akses Menu <?php echo $id; ?></h2>
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
									echo '<th>No</th>';
									echo '<th>Menu</th>';
									//echo '<th>Akses <input type="checkbox" onClick="toggle(this)" name="select_all" /></th>';
									echo '<th>Create <input type="checkbox" name="select_all_c" id="select-all-c" '.$dis.' /></th>';
									echo '<th>Read <input type="checkbox" name="select_all_r" id="select-all-r" '.$dis.' /></th>';
									echo '<th>Update <input type="checkbox" name="select_all_u" id="select-all-u" '.$dis.' /></th>';
									echo '<th>Delete <input type="checkbox" name="select_all_d" id="select-all-d" '.$dis.' /></th>';
									echo '<th>Approve <input type="checkbox" name="select_all_a" id="select-all-a" '.$dis.' /></th>';
									echo '<th>Unapprove <input type="checkbox" name="select_all_ua" id="select-all-ua" '.$dis.' /></th>';
									echo '</thead>';
									
									$no=0;
								foreach($akses as $ak)
								{
									
									$no++;
									echo '<tr>';
									echo '<td>'.$no.'</td>';
									echo '<td>'.$ak->menu.'</td>';
									
									if($ak->CD==1)
									{	
										if($ak->C==1)
										{
											$c='checked="checked"';
										}
										else
										{
											$c="";
										}
										$name1="c-".$ak->id_menu;
										echo '<td><input name="'.$name1.'" class="c" type="checkbox"  '.$dis.' '.$c.' value="'.$ak->menu.'"></td>';
									}
									else
									{
										echo '<td></td>';
									}
									if($ak->RD==1)
									{	
										if($ak->R==1)
										{
											$r='checked="checked"';
										}
										else
										{
											$r="";
										}
										$name1="r-".$ak->id_menu;
										echo '<td><input name="'.$name1.'" class="r" type="checkbox" '.$dis.' '.$r.' value="'.$ak->menu.'"></td>';
									}
									else
									{
										echo '<td></td>';
									}
									if($ak->UD==1)
									{	
										if($ak->U==1)
										{
											$u='checked="checked"';
										}
										else
										{
											$u="";
										}
										$name1="u-".$ak->id_menu;
										echo '<td><input name="'.$name1.'" class="u" type="checkbox" '.$dis.' '.$u.' value="'.$ak->menu.'"></td>';
									}
									else
									{
										echo '<td></td>';
									}
									if($ak->DD==1)
									{	
										if($ak->D==1)
										{
											$d='checked="checked"';
										}
										else
										{
											$d="";
										}
										$name1="d-".$ak->id_menu;
										echo '<td><input name="'.$name1.'" class="d" type="checkbox" '.$dis.' '.$d.' value="'.$ak->menu.'"></td>';
									}
									else
									{
										echo '<td></td>';
									}
									if($ak->AD==1)
									{	
										if($ak->A==1)
										{
											$a='checked="checked"';
										}
										else
										{
											$a="";
										}
										$name1="a-".$ak->id_menu;
										echo '<td><input name="'.$name1.'" class="a" type="checkbox" '.$dis.' '.$a.' value="'.$ak->menu.'"></td>';
									}
									else
									{	
										echo '<td></td>';
									}
									
									if($ak->UAD==1)
									{	
										if($ak->UA==1)
										{
											$ua='checked="checked"';
										}
										else
										{
											$ua="";
										}
										$name1="ua-".$ak->id_menu;
										echo '<td><input name="'.$name1.'" class="ua" type="checkbox" '.$dis.' '.$ua.' value="'.$ak->menu.'"></td>';
									}
									else
									{
										echo '<td></td>';
									}
									
									echo '</tr>';
									
									
								}
									echo '</table>';
								?>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
									<?php 
									if($dis!='disabled')
									{
										?>
										<a class="btn btn-success" id="submit">Simpan</a>
									<?php
									}
									?>
										
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