<html lang="en">
	<head>
		<title>Panelis Kompetitor</title>
				<script>
		$(document).ready(function(){
			$("#approve").click(function(){
				var ket = prompt("Alasan Approve?");
				var id_kompetitor = $("#id_kompetitor").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan_kompetitor/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_kompetitor:id_kompetitor,
						approve:1,						
					},
				success:function(data)
					{
						//location.reload();
					
						var obj = jQuery.parseJSON(data); 
						if(obj.id_item==0)
						{
							location.reload();
							
						}
						else
						{
							var base_url =  <?php echo json_encode(base_url()); ?>+'produk_kompetitor/'+obj.id_item;
							window.location.href=base_url;
					
						}
					}
				});			
			});
			$("#drop").click(function(){
				var ket = prompt("Alasan Drop?");
				var id_kompetitor = $("#id_kompetitor").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan_kompetitor/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_kompetitor:id_kompetitor,
						approve:-1,
						
						
					},
					success:function(data)
					{
						var obj = jQuery.parseJSON(data); 
						if(obj.id_item==0)
						{
							location.reload();
							
						}
						else
						{
							var base_url =  <?php echo json_encode(base_url()); ?>+'produk_kompetitor/'+obj.id_item;
							window.location.href=base_url;

						}
					}
				});			
			});
			$("#unapprove").click(function(){
				var ket = prompt("Alasan unapprove?");
				var id_kompetitor = $("#id_kompetitor").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan_kompetitor/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_kompetitor:id_kompetitor,
						approve:0,
						
					},
				success:function(data)
					{			
						var obj = jQuery.parseJSON(data); 
						if(obj.id_item==0)
						{
							location.reload();
							
						}
						else
						{
							var base_url =  <?php echo json_encode(base_url()); ?>+'produk_kompetitor/'+obj.id_item;
							window.location.href=base_url;

						}
					}
				});			
			});
		
		
		});
		
		function get_tabel(id)
		{	
			//mengisi tabel
			$.ajax({
				url:'<?php echo base_url();?>mkt/get_tabel_kompetitor', //menjalankan ini
				method:'post',
				data:{
						
						id:id, //parameter untuk ambil di php adl post('no')
						ke:3
					},
				success:function(data) //jika berhasil
				{
						 var html = '';
						 var judul = '';
						 var i=0;
						 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
						//membuat header kolom
						 html='<thead ><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Skala</th><th class="text-center">Keterangan</th></thead>';
						var var1='';
						 var var2='';
						 var jum_var=0;
						 var mer=[];
						 for(i=0; i<obj.length; i++){
							 var2=obj[i].varr;
							 if(var1!=var2)
							 {
								 var sv=0;
								 jum_var++;
							 }
							var1=var2;
							sv++;
							mer[obj[i].varr] = sv;							
						}
						for(i=0; i<obj.length; i++){
							var2=obj[i].varr;
							if(var1!=var2)
							{
								html += '<tr align=left><td rowspan='+mer[obj[i].varr]+'>'+obj[i].varr+'</td><td>'+obj[i].subvar+'</td><td>'+Math.round(obj[i].nilai * 100) / 100+'</td><td>'+Math.round(obj[i].skala * 100) / 100+'</td><td>'+obj[i].keterangan+'</td></tr>';
							}
							else
							{
								html += '<tr align=left><td>'+obj[i].subvar+'</td><td>'+Math.round(obj[i].nilai * 100) / 100+'</td><td>'+Math.round(obj[i].skala * 100) / 100+'</td><td>'+obj[i].keterangan+'</td></tr>';
							}
							var1=var2;
							
						}
						judul=obj[0].panelis+' Tanggal '+obj[0].tanggal;
						kesimpulan='Kesimpulan : '+obj[0].kesimpulan;
						 $('#tabel').html(html);
						 $('#myModalLabel').html(judul);
						 $('#kesimpulan').html(kesimpulan);
						
				}
			});	
		
		}  
		function check_length(my_text)
		{
			var ket=document.getElementsByName(my_text)[0];
			 maxLen = 980; // max number of characters input
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
		function harusAngka(evt){
		 var charCode = (evt.which) ? evt.which : event.keyCode
		 //alert(charCode);
		if ((charCode >= 48 && charCode <= 57) || charCode==44 || charCode==46)
		{
			//alert(charCode);
			return true;
			//return false;
		}
		else
		{
			alert("tidak bisa input selain angka dan desimal");	
			return false;
		}
		 
		}
		</script>
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
				<div class="title_left">
					<h3><?php echo  isset($default['judul']) ? $default['judul'] : '';?></h3>

				</div>
				<div class="title_right">
					<h3>
					

					<?php
					if(empty($status_kompetitor))
					{
						if($auth_menu_kompetitor_panelis->A==1)
						{
					?>
						<a class="btn btn-success"  id="approve">Approved</a>
						<a class="btn btn-danger"  id="drop">Drop</a>
					<?php
						}
					}
					else
					{
						if($auth_menu_kompetitor_panelis->UA==1)
						{
					?>
						<a class="btn btn-warning"  id="unapprove">Unapproved</a>
					<?php
						}
					}
					?>
					<a class="btn btn-info" href="<?php echo $form4?>">Back</a></h3>

				</div>
				
			
			<?php if($this->session->flashdata('message_approve')!='') 
						{
				?>
				<div class="page-title">
								<div class="alert alert-danger alert-dismissible " role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<strong><?php echo $this->session->flashdata('message_approve')?></strong>
					</div>
				</div>
				<?php } 
				if($auth_menu_kompetitor_panelis->C==1)
				{
				?>
				
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Panelis <?php echo $nama?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								<input name="id_kompetitor" id="id_kompetitor" type="hidden" class="form-control" value="<?php echo  isset($default['id_kompetitor']) ? $default['id_kompetitor'] : ''; ?>">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="panelis" id="panelis" required>
													<option></option>
												<?php
													foreach ($panelis as $panelis) {
														?>
														<option <?php echo $panelis_selected == $panelis->panelis ? 'selected="selected"' : '' ?> value="<?php echo $panelis->panelis ?>">
															<?php echo $panelis->panelis ?>
														</option>
														<?php
													}	
												?>					
												</select>
									</div>															
																
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Panelis</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker'>
												<input type='text' class="form-control" name="tgl" required value="<?php echo  isset($default['tgl']) ? $default['tgl'] : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Real</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker2'>
												<input type='text' class="form-control" name="tgl_real" required value="<?php echo  isset($default['tgl_real']) ? $default['tgl_real'] : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">										
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Step Panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="ke" id="ke">
											<option <?php echo $ke_selected == 1 ? 'selected="selected"' : '' ?> value="1">Risetman</option>
											<option <?php echo $ke_selected == 2 ? 'selected="selected"' : '' ?> value="2">Internal</option>
											<option <?php echo $ke_selected == 3 ? 'selected="selected"' : '' ?> value="3">Taste Specialist</option>
										</select>
									</div>									
								</div>
								
								<?php 
								echo form_hidden('k',$default['k']) ;
								echo ! empty($table) ? $table : '';
								?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Kesimpulan</label>
									<div class="col-md-9 col-sm-9  xdisplay_inputx form-group has-feedback">
										<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" name="kesimpulan" onKeyUp="check_length('kesimpulan')"><?php echo ! empty ($default['kesimpulan']) ? $default['kesimpulan'] : '';?></textarea>
									</div>
								</div>	
								<button type="submit" class="btn btn-primary">Submit</button>
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
								<?php echo ! empty($table2) ? $table2 : '';
								?>		   
							</div>
						</div>
					</div>
			</div>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Judul</h4>
						</div>
						<div class="modal-body">	
							<table  id="tabel" class=" table table-striped table-bordered"></table>
							<p id="saran"></p>
							<p id="kesimpulan"></p>
							<p id="sumber_masalah"></p>
							<p id="deskripsi"></p>
							<p id="action_plan"></p>
						</div>
					</div>
				</div>
				<br>
			</div>
		
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#panelis').select2({allowClear: false,placeholder: "--Pilih Panelis--"});
			});


		</script>
	</body>
</html>