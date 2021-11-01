<html lang="en">
	<head>
		<title>Laporan All Kompetitor</title>
		<script>
		function get_dtl(id)
		{
			var id =  id;			
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_dtl_konsep',
					method:'post',
					data:{
							id:id
						},
					success:function(data)
					{
						
							 var html = '';
							 var judul = 'Detail Konsep';
							 var i=0;
							 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
							//membuat header kolom
							 html='<thead ><th class="text-center">Nama Produk</th><th class="text-center">Tanggal</th></thead>';
							 for(i=0; i<obj.length; i++){
								 
								html += '<tr align=left><td>'+obj[i].nama_item+'</td><td>'+obj[i].awal_riset+'</td></tr>';
							}
							//menaruh variabel html pada tabel
							$('#tabel').html(html);
							
							 $('#myModalLabel').html(judul);
						 

					}
				});	
			 
			 
			$('#myModal').modal('show');
			
			
		}
		</script>
			<style type="text/css">
		table {
		  text-align: left;
		  position: relative;
		
		}

		th {
		  background: white;
		  position: sticky;
		  top: 0;
		}
	</style>
	</head>
	<body class="nav-md">

<?php
$this->load->model('Transaksi_model', '', TRUE);
$this->load->model('Master_model', '', TRUE);
?>	
		<div class="right_col" role="main">
			<div class="page-title">
				
				<div class="title_right">
				</div>
			</div>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Laporan All Kompetitor : Laporan list kompetitor sesuai line produk yang dipilih</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" method="post">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Produk Line</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="line[]" id="line"  multiple="multiple" class="ui fluid dropdown">
										<?php
											foreach ($line as $line) {
												$str_flag="";
												if(in_array($line->id_lp,$linea))
												{	
												$str_flag = "selected";
												}
												else 
												{
													$str_flag="";
												}
												?>
												<option <?php echo $str_flag ?> value="<?php echo $line->id_lp ?>">
													<?php echo $line->lineproduk ?>
												</option>
												<?php
											}	
										?>					
										</select>
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<input type="submit" class="btn btn-success" value="Submit" onclick="javascript: form.action='<?php echo $form?>';"/>
										
									
									</div>
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
								<h2>List</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								<?php
							if($lines!='')
							{
								//echo $lines;
								$num=$this->Transaksi_model->rekap_all_kompetitor($lines)->num_rows();
								if($num>0)
								{
							?>
								<table class="table table-bordered">
									<thead>
										<th><center>No</center></th>
										<th><center>Produk Line</center></th>
										<th><center>Nama Produk</center></th>
										<th><center>Nama Kompetitor</center></th>
										<th><center>Foto Kompetitor</center></th>
										<th><center>Status Kompetitor</center></th>
										
									</thead>
							<?php
									$no=0;
									$ro=$this->Transaksi_model->rekap_all_kompetitor_akses($lines)->result();
									foreach($ro as $dt)
									{
										$no++;
							?>
									<tr>
										<td><?php echo $no;?></td>
										<td><?php echo $dt->lineproduk;?></td>
										<td><?php echo $dt->nama_item;?></td>
										<td><a href="<?php echo base_url().'mkt/kompetitor_dtl/'.$dt->id_kompetitor.'-'.$dt->id_produk?>."  target="_blank"><u><?php echo $dt->nama?></u></a></td>
										<?php
										if(!empty($dt->foto))
										{
										?>
										<td><?php echo "<img src='../uploads/kompetitor/$dt->foto' width='70' height='90' />";?></td>
										<?php
										}
										else
										{
											echo '<td></td>';
											
										}
										?>
										<td><?php
										if($dt->status==1)
										{
											$status="Approve";
										}
										else if($dt->status==-1)
										{
											$status="Drop";
										}
										else
										{
											$status="";
										}
										echo $status;?></td>
										
									</tr>
							<?php
									}
							?>
								</table>
								
							<?php
								}
							}
							
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
						</div>
					</div>
				</div>
				<br>
			</div>
			<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel2">Judul</h4>
						</div>
						<div class="modal-body">	
							<h5 id="deskripsi_kode"></h5>
							<p id="deskripsi_tgl"></p>
							<p id="deskripsi_risetman"></p>
							<p id="deskripsi_formulaby"></p>
							<p id="deskripsi_tujuan"></p>
							<p id="deskripsi_status"></p>

							<table  id="tabel2" class=" table table-striped table-bordered"></table>
						</div>
					</div>
				</div>
				<br>
			</div>
		</div>
		<script type="text/javascript">
			/* $(document).ready(function() {
			$('#line').select2();

			}); */
			$(function() {
				$('#line').multiselect({
					includeSelectAllOption: true,
						maxHeight: 150      
				});
			});

			$('table').excelTableFilter();
		</script>
	
		</body>
</html>