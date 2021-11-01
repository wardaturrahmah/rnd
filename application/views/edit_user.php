<!DOCTYPE html>
<html lang="en">
	
	<head>
		<title>Edit Password</title>
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
				<?php } ?>
            
            <div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Ganti Password</small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<form class="form-horizontal form-label-left" action="<?php echo $form2?>" method="post">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">User</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="user" name="user" type="text" class="form-control" placeholder="User" value="<?php echo $user;?>" readonly="readonly">
									</div>
								</div>
								<div class="item form-group">
									<label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Password Lama</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="passwordl" type="password" name="password3" class="form-control col-md-7 col-xs-12" onchange="cek_pass();">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
										<div class="red" id="notice">
											
										</div>
									</div>
								</div>
								<div class="item form-group">
									<label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Password baru</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password" type="password" name="password4" class="form-control col-md-7 col-xs-12">
									</div>
								</div>
								<div class="item form-group">
									<label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Ulangi Password</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password2" type="password" name="password5" data-validate-linked="password4" class="form-control col-md-7 col-xs-12" required="required">
									</div>
								</div>						  
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success">Submit</button>
										<button type="reset" class="btn btn-primary">Reset</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>	
				
		</div>
		<!-- page content -->
	</body>
</html>