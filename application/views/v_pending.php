<html lang="en">
	<head>
		<title>Pending</title>
				<script>
		
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
	<body class="nav-md">            
		<div class="right_col" role="main">
				<div class="title_left">
					<h3><?php echo  isset($default['judul']) ? $default['judul'] : '';?></h3>

				</div>
			<div class="title_right">
					<h3>
					<a class="btn btn-info" href="<?php echo $form4?>">Back</a></h3>

				</div>
				
			
			<?php
			if($auth_menu18->C==1)
			{
			?>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Pending</h2>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Awal</label>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Akhir</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker2'>
												<input type='text' class="form-control" name="tgl2" required value="<?php echo  isset($default['tgl2']) ? $default['tgl2'] : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
									<div class="col-md-9 col-sm-9  xdisplay_inputx form-group has-feedback">
										<textarea class="col-md-12 col-sm-12 col-xs-12 form-control" required name="keterangan" onKeyUp="check_length('keterangan',980)"><?php echo ! empty ($default['keterangan']) ? $default['keterangan'] : '';?></textarea>
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Pending By</label>
									<div class="col-md-3 col-sm-3  xdisplay_inputx form-group has-feedback">
										<input name="pending_by" id="pending_by" type="text" class="form-control" value="<?php echo  isset($default['pending_by']) ? $default['pending_by'] : ''; ?>" onKeyUp="check_length('pending_by',98)" required>
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Approved By</label>
									<div class="col-md-3 col-sm-3  xdisplay_inputx form-group has-feedback">
										<input name="approved_by" id="approved_by" type="text" class="form-control" value="<?php echo  isset($default['approved_by']) ? $default['approved_by'] : ''; ?>" onKeyUp="check_length('approved_by',98)" required>
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
								<h2>Tabel Pending</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								  </li>
								</ul>
									<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table id="datatable" class="table table-striped table-bordered">	   
									<thead>
										<td>Tanggal Awal</td>
										<td>Tanggal Akhir</td>
										<td>Keterangan</td>
										<td>Pending By</td>
										<td>Approved By</td>
										<td>Action</td>
									</thead>
									<?php 
									foreach($table2 as $tb)
									{
										$action='';
										if($auth_menu18->U==1)
										{
											$action.=anchor('mkt/edit_pending_form/'.$tb->id,"Ubah",array('class' => 'btn btn-success'));
										}
										if($auth_menu18->D==1)
										{
											$action.=anchor('mkt/delete_pending/'.$tb->id.'-'.$tb->id_produk,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
										}
										echo '<tr>';
										echo '<td data-sort="'.strtotime($tb->tgl_awal).'">'.date("d-m-Y",strtotime($tb->tgl_awal)).'</td>';
										echo '<td data-sort="'.strtotime($tb->tgl_akhir).'">'.date("d-m-Y",strtotime($tb->tgl_akhir)).'</td>';
										echo '<td>'.$tb->keterangan.'</td>';
										echo '<td>'.$tb->pending_by.'</td>';
										echo '<td>'.$tb->approved_by.'</td>';
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