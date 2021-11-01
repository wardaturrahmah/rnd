<!DOCTYPE html>
<html lang="en">
	
	<head>
		<title>Create User</title>
		<script>
		function cek_pass()
		{
			var pass=$("#passwordl").val();
			var user=$("#user").val();
			$.ajax({
				url:'<?php echo base_url();?>c_user/cek_pass',
				method:'post',
				data:{
						pass:pass,
						user:user,
					},
				success:function(data)
				{
						//var obj = jQuery.parseJSON(data);
						
						if (data=='false')
						{
							
							$('#notice').html('Password Lama Salah');
							
						}
						else
						{
							document.getElementById("notice").innerHTML = "";
						}

				}
			});	
			
		}
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
					<h3>Form User </h3>
				</div>
				<?php if($this->session->flashdata('message_pass')!='') 
						{
				?>
				<div class="page-title">
								<div class="alert alert-danger alert-dismissible " role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<strong><?php echo $this->session->flashdata('message_pass')?></strong>
					</div>
				</div>
				<?php } 
				if($auth_menu38->C==1)
				{
				?>
            
			
			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Buat User</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">User</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input name="userb" type="text" class="form-control" placeholder="User"  required="required" onKeyUp="cek_spasi('userb');">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Real Name</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input name="realname" type="text" class="form-control" placeholder="Real Name"  required="required">
									</div>
								</div>
								<div class="item form-group">
									<label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password" type="password" name="password" class="form-control col-md-7 col-xs-12">
									</div>
								</div>
								<div class="item form-group">
									<label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Ulangi Password</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password2" type="password" name="password2" data-validate-linked="password" class="form-control col-md-7 col-xs-12" required="required">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Head</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="head" id="head">
											<?php
											foreach($head as $hd)
											{
												echo '<option value="'.$hd->id.'">'.$hd->Username.'</option>';
											}
											?>
											
										</select>
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Group Menu</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="group_menu" id="group_menu">
											<?php
											foreach($group_menu as $gm)
											{
												echo '<option value="'.$gm->id.'">'.$gm->group_menu.'</option>';
											}
											?>
										</select>
									</div>
								</div>									
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Group Produk</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="group_produk">
											<option value="1">Line Produk</option>
											<option value="2">Produk</option>
										</select>
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
			<?php
				}
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Tabel User</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<table id="datatable" class="table table-striped table-bordered">
								<thead>
								<th>User</th>
								<th>Real Name</th>
								<th>Head</th>
								<th>Group Menu</th>
								<th>Group Produk</th>
								<th>Action</th>
								</thead>
								<?php
								foreach($list as $ls)
								{
									if($ls->Username==$user)
									{
										
									}
									else
									{
										$action='';
										if($auth_menu38->U==1)
										{
											$action.='<button type="button" class="btn btn-info" 
											data-toggle="modal" data-target="#modal_edit_'.$ls->id.'">
											<a><i class="fa fa-edit blue" style="color:#fff;"></i></a></button>';
										}
										if($auth_menu39->U==1)
										{
											if($ls->Group_produk==1)
											{
												$lpe=site_url('c_user/akses_lp/'.$ls->id);
												$action.='
												<a href="'.$lpe.'"><button type="button" class="btn"><i class="fa fa-cog" style="color:#000;"></i></button></a>';
											}
											else
											{
												$lpe=site_url('c_user/akses/'.$ls->id);
												$action.='
												<a href="'.$lpe.'"><button type="button" class="btn"><i class="fa fa-cog" style="color:#000;"></i></button></a>';
											}
										}
										echo '<tr>';
										echo '<td>'.$ls->Username.'</td>';
										echo '<td>'.$ls->realname.'</td>';
										echo '<td>'.$ls->head.'</td>';
										echo '<td>'.$ls->group_menu.'</td>';
										echo '<td>'.$ls->group_produk.'</td>';
										echo '<td>'.$action.'</td>';
									}
								?>
								<div class="modal fade" id="modal_edit_<?echo $ls->id?>" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
										<div class="modal-dialog">
										  <div class="modal-content">
											<div class="modal-header">
											  <h4 class="modal-title">Edit User</h4>
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
														<input type="text" value="<?php echo $ls->Username ?>" class="form-control form-control-sm" id="inputName" placeholder="Name" name="u_name" required="" readonly>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputName" class="col-sm-3 col-form-label" style="text-align: right;">Real Name</label>
													<div class="col-sm-9">
														<input type="text" value="<?php echo $ls->realname ?>" class="form-control form-control-sm" id="inputName" placeholder="Real Name" name="u_realname" required="">
													</div>
												</div>
												<div class="form-group row">
													<label for="inputName" class="col-sm-3 col-form-label" style="text-align: right;">Head</label>
													<div class="col-sm-9">
														<select class="form-control form-control-sm select2" style="width: 100%;" name="u_head" id="u_head">
														<?php
														foreach($head as $hd)
														{
															if($ls->id_head==$hd->id)
															{
																echo '<option value="'.$hd->id.'" selected>'.$hd->Username.'</option>';
															}
															else
															{
																echo '<option value="'.$hd->id.'">'.$hd->Username.'</option>';
															}
														}
														?>
														
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputName" class="col-sm-3 col-form-label" style="text-align: right;">Group Menu</label>
													<div class="col-sm-9">
														<select class="form-control form-control-sm select2" style="width: 100%;" name="u_gm" id="u_gm">
														<?php
														foreach($group_menu as $gm)
														{
															if($ls->Group_menu==$gm->id)
															{
																echo '<option value="'.$gm->id.'" selected>'.$gm->group_menu.'</option>';
															}
															else
															{
																echo '<option value="'.$gm->id.'">'.$gm->group_menu.'</option>';
															}
														}
														?>
														
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputName" class="col-sm-3 col-form-label" style="text-align: right;">Group Produk</label>
													<div class="col-sm-9">
														<select class="form-control form-control-sm select2" style="width: 100%;" name="u_gp" id="u_gp">
															<option <?php echo $ls->Group_produk == '1' ? 'selected="selected"' : '' ?> value="1">Line Produk</option>
															<option <?php echo $ls->Group_produk == '2' ? 'selected="selected"' : '' ?> value="2">Produk</option>
														</select>
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
		$('#group_menu').select2();
		$('#group_produk').select2();
		$('#u_head').select2();
		$('#u_gm').select2();
		$('#u_gp').select2();
		}
	);
	</script>
</html>