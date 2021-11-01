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
				var id_item = $("#id_item").val();
				var subvar = $("#subvar").val();
				var vari = $("#vari").val();
				var skala = $("#skala").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/add_penilaian',
				method:'post',
				data:{
						
						id_item:id_item,
						subvar:subvar,
						vari:vari,
						skala:skala,
					},
				success:function(data)
				{
					//var markup = "<tr><td><input type='checkbox' name='record'></td><td>" + subvar + "</td><td>" + vari + "</td></tr>";
					//$("table tbody").append(markup);
					document.getElementById("subvar").value = "";
					document.getElementById("skala").value = "";
					document.getElementById("vari").value = "Base";
					
					
							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr id='"+obj[i].id+"'><td><input type='checkbox' name='record' id='cb"+obj[i].id+"' value='"+obj[i].id+"'></td><td>" + obj[i].varr + "</td><td>" + obj[i].subvar + "</td><td>" + Math.round( obj[i].skala * 100 ) / 100 + "</td></tr>";
								$("#empTable tbody").append(markup);
								}
							}
							
				
						
						
				}				});	
				
			});
			//add panelis
			$(".add-panelis").click(function(){
				var id_item = $("#id_item").val();
				var panelis = $("#panelis").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/add_panelis',
				method:'post',
				data:{
						
						id_item:id_item,
						panelis:panelis,
					},
				success:function(data)
				{
					//var markup = "<tr><td><input type='checkbox' name='record'></td><td>" + subvar + "</td><td>" + vari + "</td></tr>";
					//$("table tbody").append(markup);
									
							document.getElementById("panelis").value = "";
							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr><td><input type='checkbox' name='record' id='"+obj[i].id+"' value='"+obj[i].id+"'></td><td>" + obj[i].panelis + "</td></tr>";
								$("#panelisTable tbody").append(markup);
								}
							}
							
				
						
						
				}				});	
				
			});
			
			// Find and remove selected table rows
			$(".delete-row").click(function(){
				
				$("table tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						r=confirm('Apakah anda yakin akan menghapus?');
						if(r==true)
						{
							var id=$(this).val();
							$.ajax({
							url:'<?php echo base_url();?>mkt/delete_penilaian',
							method:'post',
							data:{
									
									id:id,
									
								},
							success:function(data)
							{
								var obj = jQuery.parseJSON(data);
								console.log(obj.id);
								if(obj.id>0)
								{
									//var markup = "<tr><td><input type='checkbox' name='record' id='"+obj.id+"' value='"+obj.id+"'></td><td>" + obj.varr + "</td><td>"+obj.subvar+"</td><td>" + Math.round( obj.skala * 100 ) / 100 + "</td></tr>";
									//$("#empTable tbody").append(markup);
									$('#cb'+id).prop('checked', false); // Checks it
									alert('Sudah ada panelis. Subvar tidak dapat dihapus');
								}
								else
								{
									$('#empTable tr#'+id).remove();
								}
							}				
							});	
						}
						
					}
				});
			});
			$(".update-row").click(function(){
				$("table tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						//$(this).parents("tr").remove();
						var id=$(this).val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/get_penilaian/'+id,
						method:'post',
						data:{
								
								id:id,
								
							},
						success:function(data)
						{
							
							var obj = jQuery.parseJSON(data);
							
							$('#vari').val(obj[0].varr);							
							document.getElementById("subvar").value = obj[0].subvar;
							document.getElementById("skala").value = Math.round(obj[0].skala * 100) / 100;
						}				});	
					}
				});
			});
			$(".update2-row").click(function(){
				$("#empTable tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("tr").remove();
						var id=$(this).val();
						var varr = $("#vari").val();
						var subvar = $("#subvar").val();
						var skala = $("#skala").val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/update_penilaian',
						method:'post',
						data:{
								id:id,
								varr:varr,
								subvar:subvar,
								skala:skala,
							},
						success:function(data)
						{
							//document.getElementById("bahan").value = "";
							document.getElementById("subvar").value = "";
							document.getElementById("skala").value = "";

							var obj = jQuery.parseJSON(data);
							if(obj.length > 0)
							{
								for(i=0; i<obj.length; i++)
								{
								var markup = "<tr id='"+obj[i].id+"'><td><input type='checkbox' name='record'  id='cb"+obj[i].id+"' value='"+obj[i].id+"'></td><td>" + obj[i].varr + "</td><td>"+obj[i].subvar+"</td><td>" + Math.round( obj[i].skala * 100 ) / 100 + "</td></tr>";
								$("#empTable tbody").append(markup);
								}
							}
						}				
						});	
					}
				});
			});
			
			$(".delete-panelis").click(function(){
				$("table tbody").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("tr").remove();
						var id=$(this).val();
						$.ajax({
						url:'<?php echo base_url();?>mkt/delete_panelis',
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
		
		
		});    
		
		function table_penilaian()
		{
			var id_item = $("#id_item").val();
			$.ajax({
			url:'<?php echo base_url();?>mkt/get_tabel_penilaian', //menjalankan ini
			method:'post',
			data:{
					
					id_item:id_item, //parameter untuk ambil di php adl post('no')
				},
			success:function(data)
			{
					 var html = '';
                     var i=0;
					 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
					 //membuat header kolom

					for(i=0; i<obj.length; i++){
						
                        html += "<tr id='"+obj[i].id+"'><td><input type='checkbox' name='record'  id='cb"+obj[i].id+"' value='"+obj[i].id+"'></td><td>" + obj[i].varr + "</td><td>" + obj[i].subvar + "</td><td>"+Math.round(obj[i].skala * 100) / 100+"</td></tr>";
                    }
					$("#empTable tbody").append(html);
					
					
					//menaruh variabel html pada tabel jaminan


			}
			});	
		}
		function table_panelis()
		{
			var id_item = $("#id_item").val();
			$.ajax({
			url:'<?php echo base_url();?>mkt/get_tabel_panelis', //menjalankan ini
			method:'post',
			data:{
					
					id_item:id_item, //parameter untuk ambil di php adl post('no')
				},
			success:function(data)
			{
					 var html = '';
                     var i=0;
					 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
					 //membuat header kolom

					for(i=0; i<obj.length; i++){
                        html += "<tr><td><input type='checkbox' name='record' value='"+obj[i].id+"'></td><td>" + obj[i].panelis + "</td></tr>";
                    }
					$("#panelisTable tbody").append(html);
					
					
					//menaruh variabel html pada tabel jaminan


			}
			});	
		}
		function showTableData() 
		{
			 table_penilaian();
			 table_panelis();
		}
		function cek()
		{
			var item=$("#item").val();
					var lp=$("#lp").val();
					var risetman=$("#risetman").val();
					var kompetitor=$("#kompetitor").val();
					var tgl=$("#tgl").val();
					var status=$("#status").val();
					var tgl_status=$("#tgl_status").val();
					
					
					var ket="";
					if(item=="")
					{
						ket+=" nama , ";
					}
					if(lp=="")
					{
						ket+="line produk ,";
					
					}
					if(risetman==null)
					{
						ket+=" risetman, ";
					}
					if(kompetitor=="")
					{
						ket+=" target riset, ";
					}
					if(tgl=="")
					{
						ket+=" tanggal riset, ";
					}
					if(status=="")
					{
						ket+=" status, ";
					}
					if(tgl_status=="")
					{
						ket+="tanggal status";
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
	<body class="nav-md" onload="showTableData()";>
        <!-- page content -->
        <div class="right_col" role="main">
            <div class="page-title">
				<div class="title_left">
				</div>
            </div>
			
            <div class="clearfix"></div>

            <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Header</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
							  </li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">


						<div class="" role="tabpanel" data-example-id="togglable-tabs">
							  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
								<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Item</a>
								</li>
								<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Penilaian</a>
								</li>
								<!--<li role="presentation" class="<?php echo ! empty($active_dt) ? $active_dt : '';?>"><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Panelis</a>
								</li>-->
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Produk</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input name="item" id="item" type="text" class="form-control" value="<?php echo  isset($default['item']) ? $default['item'] : ''; ?>"  onKeyUp="check_length('item',100)">
											</div>										
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Produk Line</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="p_line" id="lp" required>
													<option>
													</option>
												<?php
													
													foreach ($lineproduk as $lineproduk) {
														?>
														<option <?php echo $lineproduk_selected == $lineproduk->id_lp ? 'selected="selected"' : '' ?> value="<?php echo $lineproduk->id_lp ?>">
															<?php echo $lineproduk->lineproduk ?>
														</option>
														<?php
													}	
												?>					
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Risetman</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="risetman[]" id="risetman"  multiple="multiple" readonly class="ui fluid dropdown">
												<?php
													foreach ($risetman as $risetman) {
														$str_flag="";
														if(in_array($risetman->risetman,$risetmana))
														{	
														$str_flag = "selected";
														}
														else 
														{
															$str_flag="";
														}
														?>
														<option <?php echo $str_flag ?> value="<?php echo $risetman->risetman ?>">
															<?php echo $risetman->risetman ?>
														</option>
														<?php
													}	
												?>					
												</select>
											</div>
										</div>
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Target Riset</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												
												<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" name="kompetitor" id="kompetitor" rows="5" required  onKeyUp="check_length('kompetitor',980)"><?php echo ! empty ($default['kompetitor']) ? $default['kompetitor'] : '';?></textarea>
											</div>										
										</div>
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Konsep Sebelumnya</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="konsep" id="konsep">
													<option value="0">-</option>
												<?php
													foreach ($konsep as $konsep) {
														?>
														<option <?php echo $konsep_selected == $konsep->id ? 'selected="selected"' : '' ?> value="<?php echo $konsep->id ?>">
															<?php echo $konsep->nama_item ?>
														</option>
														<?php
													}	
												?>					
												</select>
											</div>									
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Awal Riset</label>
											<div class='col-sm-4'>
												<div class="form-group">
													<div class='input-group date' id='myDatepicker'>
														<input type='text' class="form-control" name="tgl" id="tgl" required value="<?php echo  isset($default['tgl']) ? $default['tgl'] : ''; ?>"/>
														<span class="input-group-addon">
														   <span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
								
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="status" id="status">
													<option <?php echo $status_selected == 0 ? 'selected="selected"' : '' ?> value="0">Progress</option>
													<option <?php echo $status_selected == -1 ? 'selected="selected"' : '' ?> value="-1">Terminate</option>
													<option <?php echo $status_selected == 1 ? 'selected="selected"' : '' ?> value="1">Launching</option>
													<option <?php echo $status_selected == 2 ? 'selected="selected"' : '' ?> value="2">Bank Produk-ACC</option>
												</select>
											</div>									
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Status</label>
											<div class='col-sm-4'>
												<div class="form-group">
													<div class='input-group date' id='myDatepicker2'>
														<input type='text' class="form-control" name="tgl_status" id="tgl_status" required value="<?php echo  isset($default['tgl_status']) ? $default['tgl_status'] : ''; ?>"/>
														<span class="input-group-addon">
														   <span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan Status</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												
												<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" name="keterangan" id="kompetitor" rows="5" onKeyUp="check_length('keterangan',980)"><?php echo ! empty ($default['keterangan']) ? $default['keterangan'] : '';?></textarea>
											</div>										
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Link</label>
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
															<?php echo $link->nama_item ?>
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Var</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="vari" id="vari">
													<option value="Base">
														Base
													</option>
													<option value="Rasa Aroma">
														Rasa Aroma
													</option>
													<option value="Total Rasa">
														Total Rasa
													</option>											
												</select>
											</div>
											
										</div>
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Subvar</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input name="subvar" id="subvar" type="text" class="form-control" value="<?php echo  isset($default['subvar']) ? $default['subvar'] : ''; ?>">
											</div>										
										</div>
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Skala</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input name="skala" id="skala" type="text" class="form-control" value="<?php echo  isset($default['skala']) ? $default['skala'] : ''; ?>">
											</div>										
										</div>
										<input type="button" class="add-row" value="Tambah Kriteria">
										<input type="button" class="update2-row" value="Save Update">
										
										<br>	<br>

										<table id="empTable">
											<thead>
												<tr>
													<th>Select</th>
													<th>Var</th>
													<th>Subvar</th>
													<th>Skala</th>

												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
										<button type="button" class="delete-row">Delete Kriteria</button>
										<button type="button" class="update-row">Update Kriteria</button>
										<div class="ln_solid"></div>
										<div class="form-group">
											<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
												<button type="submit" class="btn btn-success" onclick="cek();">Submit</button>
											</div>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="home-tab">
										
										<div class="form-group">										
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Panelis</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control" name="panelis" id="panelis">
												<?php
													foreach ($panelis as $panelis) {
														?>
														<option <?php echo $panelis_selected == $panelis->id_panelis ? 'selected="selected"' : '' ?> value="<?php echo $panelis->nama_panelis ?>">
															<?php echo $panelis->nama_panelis ?>
														</option>
														<?php
													}	
												?>					
												</select>
											</div>									
										</div>
										<input type="button" class="add-panelis" value="Tambah Panelis">
										<br>	<br>

										<table id="panelisTable">
											<thead>
												<tr>
													<th>Select</th>
													<th>Nama Panelis</th>
													

												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
										<button type="button" class="delete-panelis">Delete Panelis</button>
										<div class="ln_solid"></div>
										<div class="form-group">
											<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
												<button type="submit" class="btn btn-success">Submit</button>
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
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Tabel</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								
								<?php echo ! empty($table) ? $table : '';
										 echo ! empty($note) ? $note : '';
									?>	
									
							
						</div>
					</div>
				</div>			
		
			</div>
			<script type="text/javascript">
			$(document).ready(function() {
			$('#lp').select2();
			$('#panelis').select2();
			$('#konsep').select2();
			
			});
		 
			$(function() {
				$('#link').multiselect({
					includeSelectAllOption: true,
						maxHeight: 150      
				});
			});
			$(function() {
				$('#risetman').multiselect({
					includeSelectAllOption: true,
						maxHeight: 150,
						readonly : true
				});
			});
			
			
		</script>
        <!-- /page content -->
	 </body>
</html>