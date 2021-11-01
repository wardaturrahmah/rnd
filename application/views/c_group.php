<!DOCTYPE html>
<html lang="en">
	
	<head>
		<title>Create Group</title>
		<script>
		function cek_spasi(my_text)
		{
			var ket=document.getElementsByName(my_text)[0];
			cek=ket.value.substring((ket.value.length-1), ket.value.length);
			if (cek == " ") {
			var msg = "Tidak boleh ada spasi";
			alert(msg);
			ket.value = ket.value.substring(0, ket.value.length-1);
			 }
		}
		</script>
		
	</head>
	<body class="nav-md">
		<!-- page content -->
        <div class="right_col" role="main">
            
				<div class="title_left">
					<h3>Form Group Menu </h3>
				</div>
			<?php if ($auth_menu40->C==1){?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Buat Group Menu</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								
								<div class="item form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Group Menu</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="group" type="text" name="group" class="form-control col-md-7 col-xs-12">
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success">Submit</button>
										<button type="reset" class="btn btn-primary">Reset</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
				
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Tabel Group</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<table id="datatable" class="table table-striped table-bordered">
								<thead>
								<th>Group</th>
								<th>Action</th>
								</thead>
								<?php
								foreach($list as $ls)
								{
									$action='';
									if($auth_menu40->U==1)
									{
										//$action.='<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_edit_'.$ls->id.'"><a><i class="fa fa-edit blue" style="color:#fff;"></i></a></button>';
									}
									if($auth_menu40->R==1)
									{
										$ed=site_url('c_user/akses_menu/'.$ls->id);
										$action.='
											<a href="'.$ed.'"><button type="button" class="btn"><i class="fa fa-cog" style="color:#000;"></i></button></a>';
									}
									echo '<tr>';
									echo '<td>'.$ls->group_menu.'</td>';
									echo '<td>'.$action.'</td>';
								?>
								<div class="modal fade" id="modal_edit_<?echo $ls->id?>" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
										<div class="modal-dialog">
										  <div class="modal-content">
											<div class="modal-header">
											  <h4 class="modal-title">Edit Group Menu</h4>
											  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											  </button>
											</div>
											<div class="modal-body">
											  <form class="form-horizontal" action="<?php echo $form3; ?>" method="post" id="form1">
												<input type="hidden" value="<?php echo $ls->id ?>" name="u_id"></input>
												
												<div class="form-group row">
													<label for="inputName" class="col-sm-3 col-form-label" style="text-align: right;">Nama</label>
													<div class="col-sm-9">
														<input type="text" value="<?php echo $ls->group_menu ?>" class="form-control form-control-sm" id="inputName" placeholder="Name" name="u_group" required="" readonly>
													</div>
												</div>
												
												<div class="col-3">
												  </div>
												  <div class="modal-footer justify-content-between">
													  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													  <input type="submit" name="save" value="update" class="btn btn-primary">
													</div>
												
											</form>
											</div>
				
										  </div>
										  <!-- /.modal-content -->
										</div>
										<!-- /.modal-dialog -->
									</div>
								
								<?php
									echo '</tr>';
								}
								?>
								
							</table>
						</div>
					</div>
				</div>
			</div>	
		
		</div>
		<!-- page content -->
	</body>
	<script>
	$(document).ready(function() {
		$('#head').select2();
		}
	);
	</script>
</html>