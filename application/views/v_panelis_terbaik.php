<html lang="en">
	<head>
		<title>Formula Terbaik</title>
				<script>
		$(document).ready(function(){
			$("#approve").click(function(){
				var ket = prompt("Alasan Approve?");
				var id_formula = $("#id_formula").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_formula:id_formula,
						approve:1,
						stage:'approve1',
						ke:1,
						
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
				var id_formula = $("#id_formula").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_formula:id_formula,
						approve:-1,
						stage:'approve1',
						ke:1,
						
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
				var id_formula = $("#id_formula").val();
				$.ajax({
				url:'<?php echo base_url();?>mkt/keterangan/',
				method:'post',
				data:{
						
						keterangan:ket,
						id_formula:id_formula,
						approve:0,
						stage:'approve1',
						ke:1,
						
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
				url:'<?php echo base_url();?>mkt/get_formula_terbaik', //menjalankan ini
				method:'post',
				data:{
						
						id:id, //parameter untuk ambil di php adl post('no')
					},
				success:function(data) //jika berhasil
				{
						 var html = '';
						 var judul = '';
						 var i=0;
						 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
						//membuat header kolom
						 html='<thead ><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Formula</th></thead>';
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
							var formula=obj[i].kode;
							var kode="";
							if(formula!=null)
							{
								kode=obj[i].kode;
							}
							else
							{
								kode="-";
							}
							if(var1!=var2)
							{
								html += '<tr align=left><td rowspan='+mer[obj[i].varr]+'>'+obj[i].varr+'</td><td>'+obj[i].subvar+'</td><td>'+kode+'</td></tr>';
							}
							else
							{
								html += '<tr align=left><td>'+obj[i].subvar+'</td><td>'+kode+'</td></tr>';
							}
							var1=var2;
							
						}
						
						judul=obj[0].nama_item+' Tanggal '+obj[0].tanggal;
						
						 $('#tabel').html(html);
						 $('#myModalLabel').html(judul);
						
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
		</script>
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
				<div class="title_left">
					<h3><?php echo  isset($default['judul']) ? $default['judul'] : '';?></h3>

				</div>
			<div class="title_right">
					<h3>
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
				if($auth_menu17->C==1)
				{
					
				?>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Panelis Formula Terbaik</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								<input name="id_item" id="id_item" type="hidden" class="form-control" value="<?php echo  isset($default['id_item']) ? $default['id_item'] : ''; ?>">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal</label>
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
								<?php 
								echo form_hidden('k',$default['k']) ;
								
								echo ! empty($table) ? $table : '';
								?>	
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
								<h2>Tabel Formula Terbaik</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								  </li>
								</ul>
									<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table id="datatable" class="table table-striped table-bordered">	   
									<thead>
										<td>Tanggal</td>
										<td>Action</td>
									</thead>
									<?php 
									foreach($table2 as $tb)
									{
										$action='';
										if($auth_menu17->U==1)
										{
											$action.=anchor('mkt/edit_formula_terbaik_form/'.$tb->id,"Ubah",array('class' => 'btn btn-success'));
										}
										if($auth_menu17->R==1)
										{
											$action.=anchor('modal',"lihat",array('class' => 'btn btn-info','data-target'=>'#myModal','data-toggle'=>'modal','onclick'=>' get_tabel('.$tb->id.')'));
										}
										if($auth_menu17->D==1)
										{
											$action.=anchor('mkt/delete_formula_terbaik/'.$tb->id.'-'.$tb->id_item,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
										}
										echo '<tr>';
										echo '<td data-sort="'.strtotime($tb->tanggal).'">'.date("d-m-Y",strtotime($tb->tanggal)).'</td>';
										echo '<td>'.$action.'</td>';
										echo '</tr>';
										
									}
									?>
								</table>	   
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
			$('.nilai').select2();
			});
		</script>
	</body>
</html>