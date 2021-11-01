<html lang="en">
	<head>
		<title>Input Data</title>
		<style type="text/css">
			form{
				margin: 20px 0;
			}
			form input, button{
				padding: 5px;
			}
			table{
				width: 100%;
				margin-bottom: 20px;
				border-collapse: collapse;
			}
			table, th, td{
				border: 1px solid #cdcdcd;
			}
			table th, table td{
				padding: 10px;
				text-align: left;
			}
		</style>
		<script type="text/javascript">
		$(document).ready(function(){
			$(".add-row").click(function(){
				var id_formula = $("#id_formula").val();
				var bahan = $("#bahan").val();
				var kadar = $("#kadar").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/add_bahan_formula',
				method:'post',
				data:{
						
						id_formula:id_formula,
						bahan:bahan,
						kadar:kadar,
					},
				success:function(data)
				{
					document.getElementById("bahan").value = "";
					document.getElementById("kadar").value = "";
					
					
							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].kode_bahan + "</td><td>" + Math.round( obj[i].kadar * 1000 ) / 1000 + "</td></tr>";
								//$("table tbody").append(markup);
								$("#empTable tbody").append(markup);
								}
							}
							
					$.ajax({
						url:'<?php echo base_url();?>mkt/total_kadar',
						method:'post',
						data:{
								
								id_formula:id_formula,
								
							},
						success:function(data)
						{
							var obj = jQuery.parseJSON(data);
							kadar=Math.round(obj[0].kadar * 1000) / 1000;
							document.getElementById("total_kadar").innerHTML = "Total :"+kadar;
						}				
						});
						
						
				}				
				});	
				
				
				
			});
			$(".add-bahan").click(function(){
				var id_formula = $("#id_formula").val();
				var kode_formula = $("#t_formula").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/transfer_bahan',
				method:'post',
				data:{
						
						id_formula:id_formula,
						kode_formula:kode_formula,

					},
				success:function(data)
				{
							
							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].kode_bahan + "</td><td>" + Math.round( obj[i].kadar * 1000 ) / 1000 + "</td></tr>";
								//$("table tbody").append(markup);
								$("#empTable tbody").append(markup);
								}
							}
							
				
						$.ajax({
						url:'<?php echo base_url();?>mkt/total_kadar',
						method:'post',
						data:{
								
								id_formula:id_formula,
								
							},
						success:function(data)
						{
							var obj = jQuery.parseJSON(data);
							kadar=Math.round(obj[0].kadar * 1000) / 1000;
							document.getElementById("total_kadar").innerHTML = "Total :"+kadar;
						}				
						});
						
				}				});	
				
			});
			// Find and remove selected table rows
			$(".delete-row").click(function(){
				$("#empTable tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("tr").remove();
						var id=$(this).val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/delete_bahan_formula',
						method:'post',
						data:{
								
								id:id,
								
							},
						success:function(data)
						{
							
							$(this).parents("tr").remove();						
						}				});	
					}
				});
			});
		
			$(".update-row").click(function(){
				$("#empTable tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						var id=$(this).val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/update_bahan_formula',
						method:'post',
						data:{
								
								id:id,
								
							},
						success:function(data)
						{
							var obj = jQuery.parseJSON(data);
							$('#bahan').select2('destroy');
							$('#bahan').val(obj[0].kode_bahan);

							
							
							document.getElementById("kadar").value = Math.round(obj[0].kadar * 1000) / 1000;
						}				
						});	
					}
				});
			});
			$(".update2-row").click(function(){
				$("#empTable tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("tr").remove();
						var id=$(this).val();
						var bahan = $("#bahan").val();
						var kadar = $("#kadar").val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/update_bahan_formula2',
						method:'post',
						data:{
								id:id,
								bahan:bahan,
								kadar:kadar,
							},
						success:function(data)
						{
							//document.getElementById("bahan").value = "";
							document.getElementById("kadar").value = "";
							$('#bahan').select2();

							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].kode_bahan + "</td><td>" + Math.round( obj[i].kadar * 1000 ) / 1000 + "</td></tr>";
								$("#empTable tbody").append(markup);
								}
							}
						}				
						});	
					}
				});
			});
			
			//add sarana
			$(".add-row2").click(function(){
				var id_formula = $("#id_formula").val();
				var sarana = $("#sarana").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/add_sarana_formula',
				method:'post',
				data:{
						
						id_formula:id_formula,
						sarana:sarana,
					},
				success:function(data)
				{
					document.getElementById("sarana").value = "";
					var obj = jQuery.parseJSON(data);
					if(obj.length > 0)
					{
						for(i=0; i<obj.length; i++)
						{
							var markup = "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].sarana + "</td></tr>";
							//$("table tbody").append(markup);
							$("#empTable2 tbody").append(markup);
						}
					}
							
				
						
						
				}				
				});	
			});
			$(".add-sarana").click(function(){
				var id_formula = $("#id_formula").val();
				var kode_formula = $("#t_formula2").val();
				$.ajax({
					url:'<?php echo base_url();?>mkt/transfer_sarana',
					method:'post',
					data:{
							
							id_formula:id_formula,
							kode_formula:kode_formula,

						},
					success:function(data)
					{
								
								var obj = jQuery.parseJSON(data);
								if(obj.length > 0)
								{
									for(i=0; i<obj.length; i++)
									{
									var markup = "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].sarana + "</td></tr>";
									$("#empTable2 tbody").append(markup);
									}
								}
								
				
					}				
				});	
				
			});
			// Find and remove selected table rows
			$(".delete-row2").click(function(){
				$("#empTable2 tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("tr").remove();
						var id=$(this).val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/delete_sarana_formula',
						method:'post',
						data:{
								
								id:id,
								
							},
						success:function(data)
						{
							
							$(this).parents("tr").remove();						
						}				});	
					}
				});
			});
		
			$(".update-row2").click(function(){
				$("#empTable2 tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						var id=$(this).val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/update_sarana_formula',
						method:'post',
						data:{
								
								id:id,
								
							},
						success:function(data)
						{
							var obj = jQuery.parseJSON(data);
							$('#sarana').select2('destroy');
							$('#sarana').val(obj[0].id_sarana);							
						}				
						});	
					}
				});
			});
			$(".update2-row2").click(function(){
				$("#empTable2 tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("tr").remove();
						var id=$(this).val();
						var sarana = $("#sarana").val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/update_sarana_formula2',
						method:'post',
						data:{
								id:id,
								sarana:sarana,
							},
						success:function(data)
						{
							//document.getElementById("bahan").value = "";
							document.getElementById("sarana").value = "";
							$('#sarana').select2();

							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].sarana + "</td></tr>";
								$("#empTable2 tbody").append(markup);
								}
							}
						}				
						});	
					}
				});
			});
			
			$(".def-formula").click(function(){
				var id_formula = $("#tdef_formula").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/get_hdr_formula',
				method:'post',
				data:{
						
						id:id_formula,
					},
				success:function(data)
				{
					
					var obj = jQuery.parseJSON(data);
					
					document.getElementById("kode").value = obj[0].kode;					
					var tgl_riset=obj[0].tgl_riset;
					var da = tgl_riset.split("-");
					document.getElementById("tgl").value = da[2]+'-'+da[1]+'-'+da[0];
					$("#risetman").val(obj[0].risetman);
					$('#risetman').select2().trigger('change');
					document.getElementById("tujuan").value = obj[0].tujuan;
					
						
						
				}				
				});	
				
				
				
			});
			
		});    
		
		function showTableData() 
		{
			var id_formula = $("#id_formula").val();
			$.ajax({
			url:'<?php echo base_url();?>mkt/get_tabel_bahan_formula', //menjalankan ini
			method:'post',
			data:{
					
					id_formula:id_formula, 
				},
			success:function(data)
			{
					 var html = '';
                     var i=0;
					 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
					 //membuat header kolom

					for(i=0; i<obj.length; i++){
                        html += "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].kode_bahan + "</td><td>" +Math.round( obj[i].kadar * 1000 ) / 1000  + "</td></tr>";
                    }
					$("#empTable  tbody").append(html);

					//menaruh variabel html pada tabel jaminan


			}
			});	
			
			$.ajax({
			url:'<?php echo base_url();?>mkt/get_tabel_sarana_formula', //menjalankan ini
			method:'post',
			data:{
					
					id_formula:id_formula, 
				},
			success:function(data)
			{
					 var html = '';
                     var i=0;
					 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
					 //membuat header kolom

					for(i=0; i<obj.length; i++){
                        html += "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].sarana + "</td></tr>";
                    }
					$("#empTable2 tbody").append(html);

					//menaruh variabel html pada tabel jaminan


			}
			});	
		}
		
		function cek()
		{
			var kode=$("#kode").val();
			var tgl=$("#tgl").val();
			var risetman_hdr=$("#risetman_hdr").val();
			var risetman=$("#risetman").val();
			var tujuan=$("#tujuan").val();
							
					var ket="";
					if(kode=="")
					{
						ket+=" seri , ";
					}
					if(tgl=="")
					{
						ket+=" tanggal riset, ";
					}
					if(risetman_hdr=="")
					{
						ket+=" risetman , ";
					}
					if(risetman=="")
					{
						ket+=" sumber formula , ";
					}
					if(tujuan=="")
					{
						ket+=" tujuan ";
					}
					if(ket!="")
					{
						ket.slice(1, -1);
						ket+=" tidak boleh kosong"
						alert(ket);
						$('#myTab li:first-child a').tab('show') // Select first tab

					}
		}
		
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
	<body class="nav-md" onload="showTableData();">            
		<div class="right_col" role="main">
			<div class="title_left">
					<h3>Input</h3>

			</div>
			<?php if($this->session->flashdata('message_formula')!='') 
						{
				?>
				<div class="page-title">
								<div class="alert alert-danger alert-dismissible " role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<strong><?php echo $this->session->flashdata('message_formula')?></strong>
					</div>
				</div>
				<?php } 
				if($auth_menu12->C==1)
				{
					
				
				?>
		</br>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
							<div class="x_title">
	 								<h2><b>Formula <?php echo $nama_item; ?></b></h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
								<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
									<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Formula</a>
									</li>
									<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Bahan</a>
									</li>
									<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Sarana</a>
									</li>
									
								 </ul>
								<form class="form-horizontal" action="<?php echo $form; ?>" method="post">

									<div id="myTabContent" class="tab-content">
										<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
											<div class="form-group">										
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input name="id_item" type="hidden" id="id_item"  class="form-control"  value="<?php echo  isset($default['id_item']) ? $default['id_item'] : ''; ?>">
												</div>										
											</div>
											<div class="form-group">										
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input name="id_formula" type="hidden" id="id_formula"  class="form-control"  value="<?php echo  isset($default['id_formula']) ? $default['id_formula'] : ''; ?>">
												</div>										
											</div>
											<div class="form-group">										
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Transfer Default</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="tdef_formula" id="tdef_formula">
												<?php
													foreach ($formula as $formula2) 
													{
														?>
														<option <?php echo $formula_selected == $formula2->id ? 'selected="selected"' : '' ?> value="<?php echo $formula2->id ?>">
															<?php echo $formula2->kode ?>
														</option>
														<?php
													}	
												?>					
												</select>
												</div>	
											<input type="button" class="def-formula" value="Transfer Default">
												
											</div>
											<div class="form-group">	
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Seri Formula</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input name="kode" type="text" id="kode"  class="form-control"  value="<?php echo  isset($default['kode']) ? $default['kode'] : ''; ?>" onKeyUp="check_length('kode',50)">
												</div>										
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Riset</label>
												<div class='col-sm-4'>
													<div class="form-group">
														<div class='input-group date' id='myDatepicker'>
															<input type='text' class="form-control" id="tgl" name="tgl" required value="<?php echo  isset($default['tgl']) ? $default['tgl'] : ''; ?>"/>
															<span class="input-group-addon">
															   <span class="glyphicon glyphicon-calendar"></span>
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">	
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Risetman</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<!--<input name="risetman_hdr[]" type="text" id="risetman_hdr"  class="form-control"  value="<?php echo  isset($default['risetman_hdr']) ? $default['risetman_hdr'] : ''; ?>">
-->
													<select class="form-control" name="risetman_hdr[]" id="risetman_hdr"  multiple="multiple" class="ui fluid dropdown">
													<?php
														foreach ($risetman_hdr as $risetman2) {
															$str_flag="";
															if(in_array($risetman2->risetman,$risetmana))
															{	
															$str_flag = "selected";
															}
															else 
															{
																$str_flag="";
															}
															?>
															<option <?php echo $str_flag ?> value="<?php echo $risetman2->risetman ?>">
																<?php echo $risetman2->risetman ?>
															</option>
															<?php
														}	
													?>					
													</select>
												</div>										
											</div>
											<div class="form-group">	
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Sumber Formula</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="risetman" id="risetman" required>
													<option></option>

												<?php
													foreach ($risetman as $risetman2) {
														?>
														<option <?php echo $risetman_selected == $risetman2->id_risetman ? 'selected="selected"' : '' ?> value="<?php echo $risetman2->id_risetman ?>">
															<?php echo $risetman2->risetman ?>
														</option>
														<?php
													}	
												?>					
												</select>
											</div>										
											</div>
											<div class="form-group">	
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Tujuan</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input name="tujuan" type="text" id="tujuan"  class="form-control"  value="<?php echo  isset($default['tujuan']) ? $default['tujuan'] : ''; ?>" onKeyUp="check_length('tujuan',300)">
												</div>										
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Link Formula</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<select class="form-control" name="link[]" id="link"  multiple="multiple" class="ui fluid dropdown">
													<?php
														foreach ($link as $link) {
															$str_flag="";
															if(in_array($link->id,$linka))
															{	
															$str_flag = "selected";
															}
															else 
															{
																$str_flag="";
															}
															?>
															<option <?php echo $str_flag ?> value="<?php echo $link->id ?>">
																<?php echo $link->nama_item.' formula '.$link->kode ?>
															</option>
															<?php
														}	
													?>					
													</select>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="home-tab">
											
											<div class="form-group">										
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Transfer dari Formula</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="t_formula" id="t_formula">
												<?php
													foreach ($formula as $formula2) 
													{
														?>
														<option <?php echo $formula_selected == $formula2->id ? 'selected="selected"' : '' ?> value="<?php echo $formula2->id ?>">
															<?php echo $formula2->kode ?>
														</option>
														<?php
													}	
												?>					
												</select>
												</div>	
											<input type="button" class="add-bahan" value="Transfer Bahan">
												
											</div>
											<div class="form-group">										
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Bahan</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="bahan" id="bahan">
												<?php
													foreach ($bahan as $bahan) {
														?>
														<option <?php echo $bahan_selected == $bahan->kode ? 'selected="selected"' : '' ?> value="<?php echo $bahan->kode ?>">
															<?php echo $bahan->kode ?>
														</option>
														<?php
													}	
												?>					
												</select>
												</div>									
											</div>
											<div class="form-group">										
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input name="kadar" id="kadar" type="text" class="form-control" value="<?php echo  isset($default['kadar']) ? $default['kadar'] : ''; ?>">
												</div>
											</div>
											<input type="button" class="add-row" value="Tambah Bahan">
											<input type="button" class="update2-row" value="Save Update">
											<br>
											<br>
											<table id="empTable">
												<thead>
													<tr>
														<th>Select</th>
														<th>Kode Bahan</th>
														<th>Unit</th>
														
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
											<p id="total_kadar">Total:</p>
											<button type="button" class="delete-row">Delete Row</button>
											<button type="button" class="update-row">Update Row</button>
												

										</div>
										<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="home-tab">
											
											<div class="form-group">										
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Transfer dari Formula</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="t_formula2" id="t_formula2">
												<?php
													foreach ($formula as $formula2) {
														?>
														<option <?php echo $formula_selected == $formula2->id ? 'selected="selected"' : '' ?> value="<?php echo $formula2->id ?>">
															<?php echo $formula2->kode ?>
														</option>
														<?php
													}	
												?>					
												</select>
												</div>	
											<input type="button" class="add-sarana" value="Transfer Sarana">
												
											</div>
											<div class="form-group">										
												<label class="control-label col-md-3 col-sm-3 col-xs-12">Sarana</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="sarana" id="sarana">
												<?php
													foreach ($sarana as $sarana) {
														?>
														<option <?php echo $sarana_selected == $sarana->id_sarana ? 'selected="selected"' : '' ?> value="<?php echo $sarana->id_sarana ?>">
															<?php echo $sarana->sarana ?>
														</option>
														<?php
													}	
												?>					
												</select>
												</div>									
											</div>
											<input type="button" class="add-row2" value="Tambah Sarana">
											<input type="button" class="update2-row2" value="Save Update">
											<br>
											<br>
											<table id="empTable2">
												<thead>
													<tr>
														<th>Select</th>
														<th>Sarana</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
											<button type="button" class="delete-row2">Delete Row</button>
											<button type="button" class="update-row2">Update Row</button>
											<div class="ln_solid"></div>
											<div class="form-group">
												<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
													<button type="submit" class="btn btn-success" onclick="cek();">Submit</button>
												</div>
											</div>
										</div>
										
									</div>
								</form>
							</div>
							</div>
					</div>
				</div>			
		
			</div>
			<?php
				}
			?>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#bahan').select2();
			$('#risetman').select2();
			$('#risetman_hdr').select2();
			$('#t_formula').select2();
			$('#t_formula2').select2();
			$('#tdef_formula').select2();
			$('#sarana').select2();
			});
			
			$(function() {
				$('#link').multiselect({
					includeSelectAllOption: true,
						maxHeight: 150      
				});
			});
		</script>
	
		</body>
</html>