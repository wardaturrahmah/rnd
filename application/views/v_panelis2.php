<html lang="en">
	<head>
		<title>Panelis Internal</title>
				<script>
		$(document).ready(function(){
			$("#approve").click(function(){
				var ket = prompt("Alasan Approve?");
				//var id_formula = $("#id_formula").val();
				var id_formula = <?php echo $default['id_formula'] ?>;
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_formula:id_formula,
						approve:1,
						stage:'approve2',
						ke:2,
						
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
								var base_url =  <?php echo json_encode(base_url()); ?>+'resume_produk/'+obj.id_item;
						window.location.href=base_url;
					
						}
					}
				});			
			});
			$("#drop").click(function(){
				var ket = prompt("Alasan Drop?");
				//var id_formula = $("#id_formula").val();
				var id_formula = <?php echo $default['id_formula'] ?>;
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_formula:id_formula,
						approve:-1,
						stage:'approve2',
						ke:2,
						
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
							var base_url =  <?php echo json_encode(base_url()); ?>+'resume_produk/'+obj.id_item;
							window.location.href=base_url;

						}
					}
				});			
			});
			$("#unapprove").click(function(){
				var ket = prompt("Alasan unapprove?");
				//var id_formula = $("#id_formula").val();
				var id_formula = <?php echo $default['id_formula'] ?>;
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_formula:id_formula,
						approve:0,
						stage:'approve2',
						ke:2,
						
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
							var base_url =  <?php echo json_encode(base_url()); ?>+'resume_produk/'+obj.id_item;
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
				url:'<?php echo base_url();?>mkt/get_tabel', //menjalankan ini
				method:'post',
				data:{
						
						id:id, //parameter untuk ambil di php adl post('no')
						ke:2
					},
				success:function(data) //jika berhasil
				{
						 var html = '';
						 var judul = '';
						 var i=0;
						 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
						//membuat header kolom
						 html='<thead ><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Skala</th><th class="text-center">Keterangan</th></thead>';							//mengisi kolom anggota keluarga
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
						function nl2br (str, is_xhtml) {
							 var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
							 return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
						  } 
						judul=obj[0].panelis+' Tanggal '+obj[0].tanggal;
						saran='Saran : '.bold()+obj[0].saran;
						kesimpulan='Kesimpulan : '.bold()+nl2br(obj[0].kesimpulan);
						sumber_masalah='Sumber Masalah : '.bold()+nl2br(obj[0].masalah);
						deskripsi='Deskripsi Masalah : '.bold()+nl2br(obj[0].deskripsi);
						action_plan='Action Plan : '.bold()+nl2br(obj[0].action_plan);
						//menaruh variabel html pada tabel
						 $('#tabel').html(html);
						 $('#myModalLabel').html(judul);
						 $('#saran').html(saran);
						 $('#kesimpulan').html(kesimpulan);
						 $('#sumber_masalah').html(sumber_masalah);
						 $('#deskripsi').html(deskripsi);
						 $('#action_plan').html(action_plan);
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
					if($status=='-')
					{
							
						if($auth_menu14->A==1)
						{
					?>
						<a class="btn btn-success"  id="approve">Approved</a>
						<a class="btn btn-warning"  id="drop">Drop</a>
					<?php
						}
					}
					else
					{
						if($auth_menu14->UA==1)
						{
					?>
					<a class="btn btn-danger"  id="unapprove">Unapproved</a>
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
				if($auth_menu14->C==1)
				{
				?>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Panelis Internal</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								<input name="id_formula" id="id_formula" type="hidden" class="form-control" value="<?php echo  isset($default['id_formula']) ? $default['id_formula'] : ''; ?>">
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
								<?php 
								echo form_hidden('k',$default['k']) ;
								echo ! empty($table) ? $table : '';
								?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Saran</label>
									<div class="col-md-9 col-sm-9  xdisplay_inputx form-group has-feedback">
										<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" name="saran" onKeyUp="check_length('saran')"><?php echo ! empty ($default['saran']) ? $default['saran'] : '';?></textarea>

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
			<?php 
				if($auth_menu14->C==1)
				{
					if (empty($note3))
					{
					?>
					<div class="row">	  
						<div class="col-md-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Kesimpulan</h2>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<br />
									<form class="form-horizontal form-label-left" action="<?php echo $form5?>" method="post">
										<input name="id_formula" id="id_formula" type="hidden" class="form-control" value="<?php echo  isset($default['id_formula']) ? $default['id_formula'] : ''; ?>">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Kesimpulan</label>
											<div class="col-md-9 col-sm-9  xdisplay_inputx form-group has-feedback">
												<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" required name="kesimpulan" id="txt_kesimpulan" onKeyUp="check_length('kesimpulan')"><?php echo ! empty ($default['kesimpulan']) ? $default['kesimpulan'] : '';?></textarea>
											</div>
										</div>	
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Action Plan</label>
											<div class="col-md-9 col-sm-9  xdisplay_inputx form-group has-feedback">
												<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" required name="action_plan" onKeyUp="check_length('action_plan')"><?php echo ! empty ($default['action_plan']) ? $default['action_plan'] : '';?></textarea>

											</div>
										</div>									
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Sumber Masalah</label>
											
										</div>	
										<div class="form-group">
											<div class="col-md-3 col-sm-3 form-group">
											</div>
											<div class="col-md-9 col-sm-9 form-group">
											<?php 
											
													foreach($masalah as $masalah)
													{
											?>
													<input type="checkbox" name="masalah<?php echo $masalah->id_masalah?>" value="<?php echo $masalah->id_masalah?>" <?php echo ! empty ($default['masalah'.$masalah->id_masalah]) ? $default['masalah'.$masalah->id_masalah] : '';?>><?php echo $masalah->masalah?><br>
											<?php
													}
													
											?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi Masalah</label>
											<div class="col-md-9 col-sm-9  xdisplay_inputx form-group has-feedback">
												<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" required name="deskripsi" onKeyUp="check_length('deskripsi')"><?php echo ! empty ($default['deskripsi']) ? $default['deskripsi'] : '';?></textarea>

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
				}
			?>
			<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Kesimpulan</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								  </li>
								</ul>
									<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<?php 
								if (! empty($note3))
								{
								?>
									<table class="table table-bordered">
									<tr>
									<td>Kesimpulan</td><td> <?php echo nl2br(htmlspecialchars($list3->kesimpulan));?></td>
									</tr>
									<tr>
									<td>Action plan</td><td><?php echo nl2br(htmlspecialchars($list3->action_plan));?></td>
									</tr>
									<tr>
									<td>Sumber masalah</td><td><?php foreach($masalah3 as $masalah3)
									{ echo $masalah3->masalah.' , ';} ?></td>
									</tr>
									<tr>
									<td>Deskripsi Sumber Masalah</td><td><?php echo nl2br(htmlspecialchars($list3->deskripsi));?></td>
									</tr>
									
									</table>
								<?php 
									if($auth_menu14->C==1)
									{
								?>
									<a class="btn btn-info" href="<?php echo $form6; ?>">Update</a>

								<?php
									}
								}
								?>		   
							</div>
						</div>
					</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#panelis').select2({allowClear: false,placeholder: "--Pilih Panelis--"});
			});


		</script>
	</body>
</html>