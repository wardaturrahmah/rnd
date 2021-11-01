<html lang="en">
	<head>
		<title>Resume</title>
		<script>
		function get_tabel(id)
		{	
			//mengisi tabel
			$.ajax({
				url:'<?php echo base_url();?>mkt/get_tabel_formula', //menjalankan ini
				method:'post',
				data:{
						
						id:id,
					},
				success:function(data) //jika berhasil
				{
						 var html = '';
						 var html2 = '';
						 var judul = '';
						 var i=0;
						 var j=0;
						 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
						//membuat header kolom
						 html='<thead ><th class="text-center">Bahan</th><th class="text-center">Kadar</th></thead>';
						 for(i=0; i<obj.length; i++){
							html += '<tr align=left><td>'+obj[i].kode_bahan+'</td><td>'+Math.round(obj[i].kadar * 1000) / 1000+'</td></tr>';
						}
						
						$.ajax({
						url:'<?php echo base_url();?>mkt/get_tabel_sarana_formula', //menjalankan ini
						method:'post',
						data:{
								
								id_formula:id, //parameter untuk ambil di php adl post('no')
							},
						success:function(data2) //jika berhasil
						{
							
							var obj2 = jQuery.parseJSON(data2);
							html2='<thead ><th class="text-center">Sarana</th></thead>';
							for(j=0; j<obj2.length; j++){
							html2 += '<tr align=left><td>'+obj2[j].sarana+'</td></tr>';
							}
							$('#tabel2').html(html2);
						}});	
						judul='Formula '+obj[0].kode;
						kode2='Kode Formula : '+obj[0].kode;
						var tgl2 =new Date(obj[0].tgl_riset)
						var tanggal = tgl2.getDate()+ '-' + (tgl2.getMonth() + 1) + '-' + tgl2.getFullYear();

						tgl_riset='Tanggal Riset : '+tanggal;
						risetman='Risetman : '+obj[0].risetman_hdr;
						sumber='Sumber Formula : '+obj[0].risetman;
						tujuan='Tujuan : '+obj[0].tujuan;
						
						//menaruh variabel html pada tabel
						$('#tabel').html(html);
						$('#myModalLabel').html(judul);
						$('#kode').html(kode2);
						$('#tgl_riset').html(tgl_riset);
						$('#risetman').html(risetman);
						$('#sumber').html(sumber);
						$('#tujuan').html(tujuan);
				}
			});	
			var base_url = '<?php echo base_url();?>'
			document.getElementById("prn2").href=base_url+"print_formula/"+id; 
			//document.getElementById("prn2").href=base_url; 
		}  
		</script>
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
					<h3>
						<a class="btn btn-warning" href="<?php echo $form2?>"><i class="fa fa-home">HOME</i></a>
						<?php 
							if($auth_menu12->C==1)
							{
						?>
								<a class="btn btn-success" href="<?php echo $form?>">Create Formula</a>
						<?php
							}
							if($auth_menu13->A==1)
							{
						?>
								<a class="btn btn-info" href="<?php echo $form3?>">Approve Risetman</a>
						<?php
							}
							if($auth_menu17->R==1)
							{
						?>
								<a class="btn btn-primary" href="<?php echo $form4?>">Formula Terbaik</a>
						<?php
							}
							if($auth_menu18->R==1)
							{
						?>
								<a class="btn btn-danger" href="<?php echo $form5?>">Pending Produk</a>
						<?php
							}
						?>
						
						
						
					</h3>

				
				
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Resume Produk</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								<form class="form-horizontal form-label-left">
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Nama Produk :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $nama_item;?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">P.Line :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $line;?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Risetman :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $risetman;?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Target Riset :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo nl2br(htmlspecialchars($kompetitor));?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Awal Riset :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo date('d-m-Y',strtotime($awal_riset));?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Penilaian base :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $base;?></label>
										
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Penilaian Rasa Aroma :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $rasa_aroma;?></label>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Penilaian Total Rasa :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $total_rasa;?></label>
									</div>
									<!--<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Panelis :</label>
										<label class="col-md-9 col-sm-9 col-xs-12"><?php echo $panelis;?></label>
									</div>-->
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Konsep Sebelumnya :</label>
										<?php
										if($nama_konsep_sebelumnya!='')
										{?>
										<label class="col-md-9 col-sm-9 col-xs-12"><u><a href="<?php echo base_url().'/resume_produk/'.$id_konsep_sebelumnya?>"><?php echo $nama_konsep_sebelumnya;?></a></u></label>
										
										<?php
										}
										?>
									</div>
									<div class="form-group">										
										<label class="col-md-3 col-sm-3 col-xs-12">Referensi Link :</label>
										<label class="col-md-9 col-sm-9 col-xs-12">
										<?php
										foreach($link as $link)
										{
										?>
										<u><a href="<?php echo base_url().'/resume_produk/'.$link->id?>"><?php echo $link->nama_item.',';?></a></u>
										<?php
										}
										?>
										</label>
									</div>
								</form>
							
						</div>
					</div>
				</div>			
		
			</div>
			
			
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>List Formula</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								
								<?php echo ! empty($table) ? $table : '';?>
								<table id="datatable" class="table table-striped table-bordered">
								
								<thead>
								<td><center>Seri Formula</center></td>
								<td><center>Tgl Riset</center></td>
								<td><center>Risetman</center></td>
								<td><center>Sumber Formula</center></td>
								<td><center>Tujuan</center></td>
								<td><center>Status</center></td>
								<td><center>Action</center></td>
								</thead>
								<?php 
								foreach($list as $list)
								{
									
									$action='';
									if($auth_menu12->U==1)
									{
										$action.=anchor('edit_formula/'.$list->id,"Ubah",array('class' => 'btn btn-success'));
									}
									if($auth_menu12->R==1)
									{
										$action.=anchor('mkt/formula_dtl/'.$list->id,"Lihat",array('class' => 'btn btn-dark'));
									}
									if($auth_menu12->D==1)
									{
										$action.=anchor('mkt/delete_formula/'.$list->id.'-'.$list->id_item,"hapus",array('class' => 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
									}
									if($auth_menu13->R==1)
									{
										$action.=anchor('panelis_risetman/'.$list->id,"Panelis risetman",array('class' => 'btn btn-info'));
									}
									$status='';
									if($list->approve1==1)
									{
										$status='Approve Risetman';
									}
									else if($list->approve1==-1)
									{
										$status='Drop By Risetman';
									}
									if($list->approve2==1)
									{
										$status='Approve Internal';
									}
									else if($list->approve2==-1)
									{
										$status='Drop By Internal';
									}
									if($list->approve3==1)
									{
										$status='Approve Taste Specialist';
									}
									else if($list->approve3==-1)
									{
										$status='Drop By Taste Specialist';
									}
									if($list->approve1==1)
									{
										if($auth_menu14->R==1)
										{
											$action.=anchor('panelis_internal/'.$list->id,"Panelis Internal",array('class' => 'btn btn-warning'));
										}
									}
									if($list->approve2==1)
									{
										if($auth_menu15->R==1)
										{
											$action.=anchor('panelis_ts/'.$list->id,"Panelis Taste Specialist",array('class' => 'btn btn-primary'));
										}
										if($auth_menu16->R==1)
										{
											$action.=anchor('kesimpulan/'.$list->id,"Kesimpulan Taste Specialist",array('class' => 'btn btn-primary'));
										}
									}
									
								?>
								<tr>
								<td><?php echo $list->kode; ?></td>
								<td data-sort="<?php echo strtotime($list->tgl_riset)?>"><?php echo date("d-m-Y",strtotime($list->tgl_riset));?></td>
								<td><?php echo $list->risetman_hdr; ?></td>
								<td><?php echo $list->risetman; ?></td>
								<td><?php echo $list->tujuan; ?></td>
								<td><?php echo $status; ?></td>
								<td><?php echo $action; ?></td>
								</tr>
								<?php
									
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
							<h4 class="modal-title" id="myModalLabel"></h4>
						</div>
						<div class="modal-body">	
							<p id="kode_formula"></p>
							<p id="tgl_riset"></p>
							<p id="risetman"></p>
							<p id="sumber"></p>
							<p id="tujuan"></p>
							<button type="button"><a href="" id="prn2" target="_blank">Print</a></button>

							<table  id="tabel" class=" table table-striped table-bordered"></table>
							<table  id="tabel2" class=" table table-striped table-bordered"></table>
							
						</div>
					</div>
				</div>
				<br>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#item').select2();
			});

		</script>
	
		</body>
</html>