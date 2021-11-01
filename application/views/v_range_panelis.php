<html lang="en">
	<head>
		<title>Laporan Range Panelis</title>
		<script>
		function get_dtl(id)
		{
			var arr = id.split("_");
			var id_item =  arr[0];
			var ke =  arr[1];
			var id_masalah =  arr[2];
			var tgl_awal =  arr[3];
			var tgl_akhir =  arr[4];
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_dtl_masalah',
					method:'post',
					data:{
							id_item:id_item,
							ke:ke,
							id_masalah:id_masalah,
							tgl_awal : tgl_awal,
							tgl_akhir :tgl_akhir
							
						},
					success:function(data)
					{
						
							var html = '';
							var obj = jQuery.parseJSON(data); 
							html='<thead ><th class="text-center">Seri Formula</th><th class="text-center">Deskripsi</th></thead>';
							for(i=0; i<obj.length; i++){
							html += '<tr align=left><td>'+obj[i].kode+'</td><td>'+obj[i].deskripsi+'</td></tr>';
							}
							//menaruh variabel html pada tabel
							$('#tabel').html(html);
							
							
							 $('#myModalLabel').html('Detail masalah '+obj[0].masalah);
						 

					}
				});	
			 
			 
			$('#myModal').modal('show');
			
			
		}
		
		function get_formula(id)
		{
			var kode =  id;
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_formula',
					method:'post',
					data:{
							kode:kode,
						},
					success:function(data)
					{
							
							 var html = '';
							 var judul = '';
							 var i=0;
							 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
							//membuat header kolom
							 html='<thead ><th class="text-center">Kode Bahan</th><th class="text-center">Kategori</th><th class="text-center">Kadar</th></thead>';							//mengisi kolom anggota keluarga
							 for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].kadar*10)/10;
								html += '<tr align=left><td>'+obj[i].kode_bahan+'</td><td>'+obj[i].kategori+'</td><td>'+score+'</td></tr>';
							}
							var tgl2 =new Date(obj[0].tgl_riset)
							var tanggal = tgl2.getDate()+ '-' + (tgl2.getMonth() + 1) + '-' + tgl2.getFullYear();

							judul='Formula '+obj[0].kode+' Tanggal '+tanggal;
							//menaruh variabel html pada tabel
							 $('#tabel2').html(html);
							 $('#myModalLabel2').html(judul);
							 $('#deskripsi_kode').html('Seri Formula :'+obj[0].kode);
							 $('#deskripsi_tgl').html('Tanggal Riset :'+tanggal);
							 $('#deskripsi_risetman').html('Risetman :'+obj[0].risetman_hdr);
							 $('#deskripsi_formulaby').html('Formula By :'+obj[0].risetman);
							 $('#deskripsi_tujuan').html('Tujuan :'+obj[0].tujuan);
							 var status="";
							 if(obj[0].approve1==1)
							 {
								 status="approve by risetman";
							 }
							 else if(obj[0].approve1==-1)
							 {
								 status="Drop by risetman";
							 }
							 if(obj[0].approve2==1)
							 {
								 status="approve by internal";
							 }
							 else if(obj[0].approve1==-1)
							 {
								 status="Drop by internal";
							 }
							 if(obj[0].approve3==1)
							 {
								 status="approve by Taste Specialist";
							 }
							 else if(obj[0].approve3==-1)
							 {
								 status="Drop by Taste Specialist";
							 }
							 $('#deskripsi_status').html('Status :'+status);
						 

					}
				});	
			document.getElementById("prn").href="print_formula/"+kode; 

			$('#myModal2').modal('show');
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
							<h2>Laporan Range Panelis</h2>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Item</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="item" id="item">
										<?php
											foreach ($item as $item) {
												?>
												<option <?php echo $item_selected == $item->id ? 'selected="selected"' : '' ?> value="<?php echo $item->id ?>">
													<?php echo $item->nama_item ?>
												</option>
												<?php
											}	
										?>					
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="panelis" id="panelis">
										
												<option <?php echo $panelis_selected == 1 ? 'selected="selected"' : '' ?> value="1">Risetman</option>
												<option <?php echo $panelis_selected == 2 ? 'selected="selected"' : '' ?> value="2">Internal</option>
												<option <?php echo $panelis_selected == 3 ? 'selected="selected"' : '' ?> value="3">Taste Specialist</option>
													
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Awal</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker'>
												<input type='text' class="form-control" name="tgl_awal" id="tgl_awal" value="<?php echo  isset($tgl_awal) ? $tgl_awal : ''; ?>"/>
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
												<input type='text' class="form-control" name="tgl_akhir" id="tgl_akhir" value="<?php echo  isset($tgl_akhir) ? $tgl_akhir : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<input type="submit" class="btn btn-success" value="Submit" onclick="javascript: form.action='<?php echo $form?>';"/>
										<!--<input type="submit" class="btn btn-info" value="Excel" onclick="javascript: form.action='<?php echo $form2?>';"/>-->
								
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
								
							<?php
							
							$dt=$this->Transaksi_model->range_panelis($items,date('Y-m-d',strtotime($tgl_awal)),date('Y-m-d',strtotime($tgl_akhir)))->result();

							if(count($dt)>0)
							{
								$jdl=$this->Transaksi_model->resume_item($items)->row();
								$jdl2=$this->Transaksi_model->lama_waktu2($items,$ke)->row();
								$total_formula=$this->Transaksi_model->list_formula($items)->num_rows();
							?>
							<table width="100%">
							<tr>
									<td width="25%">Line Produk</td>
									<td width="75%"><?php echo $jdl->lineproduk; ?></td>
									
							</tr>
							<tr>
									<td width="25%">Nama Produk</td>
									<td width="75%"><?php echo $jdl->nama_item; ?></td>
							</tr>
							<tr>
									<td width="25%">Awal Riset</td>
									<td width="75%"><?php echo date('d-m-Y',strtotime($jdl->awal_riset)); ?></td>
							</tr>
							<tr>
									<td width="25%">Risetman</td>
									<td width="75%"><?php echo $jdl->risetman?></td>
							</tr>
							<tr>
									<td valign="top" width="25%">Target Riset</td>
									<td width="75%"><?php echo nl2br(htmlspecialchars($jdl->kompetitor)); ?></td>
							</tr>
							<tr>
									<td width="25%">Referensi Kompetitor</td>
									<td width="75%">
									<?php
									$link=$this->Transaksi_model->list_kompetitor($items)->result();
										foreach($link as $link)
										{
											if($link->status_kompetitor==1)
											{
									?>
										<u><a href="<?php echo base_url().'mkt/kompetitor_dtl/'.$link->id_kompetitor.'-'.$items?>"  target="_blank"><?php echo $link->nama.',';?></a></u>
									<?php
											}
										}
									?>
									</td>
							</tr>
							<tr>
									<td width="25%">Lama Waktu Riset</td>
									<td width="75%"><?php 
										$tanggal  = strtotime($jdl->awal_riset);
										$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
										$total   = $sekarang - $tanggal;
										$tahun=floor($total/(60 * 60 * 24 * 365));
										$sisa=$total-($tahun*(60 * 60 * 24 * 365));
										$bulan=floor($sisa/(60 * 60 * 24 * 30));
										$hari_ini = date("Y-m-d");
										$tgl_awal_riset=date('d',$tanggal);
										$tgl_pertama = date('Y-m-'.$tgl_awal_riset, strtotime($hari_ini));
										$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										
								echo $tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari';?></td>
							</tr>
							<tr>
									<td width="25%">Lama Waktu Panelis Terakhir</td>
									<td width="75%"><?php 
										$tanggal  = strtotime($jdl2->tgl_panelis);
										$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
										$total   = $sekarang - $tanggal;
										$tahun=floor($total/(60 * 60 * 24 * 365));
										$sisa=$total-($tahun*(60 * 60 * 24 * 365));
										$bulan=floor($sisa/(60 * 60 * 24 * 30));
										$hari_ini = date("Y-m-d");
										$tgl_awal_panelis=date('d',$tanggal);
										$tgl_pertama = date('Y-m-'.$tgl_awal_panelis, strtotime($hari_ini));
										$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										
								echo $tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )';?></td>
							</tr>
							
						</table>
						</br>
							<table class="table table-bordered">
							
								<thead>
									<th>Kode Formula</th>
									<th>Tanggal Riset</th>
									<th>Risetman</th>
									<th>Internal</th>
									<th>Taste Panelis</th>
								</thead>
							<?php
								foreach($dt as $dt)
								{
								?>
								<tr>
									<td><?php echo $dt->kode?></td>
									<td><?php echo date('d-m-Y',strtotime($dt->tgl_riset)); ?></td>
									<?php
									
									if(!is_null($dt->risetman))
									{
										$tgl_risetman=date('d-m-Y',strtotime($dt->risetman));
									}
									else
									{
										$tgl_risetman='-';
									}
									
									if(!is_null($dt->internal))
									{
										$tgl_internal=date('d-m-Y',strtotime($dt->internal));
										$hari=date('d',strtotime($dt->internal)-strtotime($dt->risetman));
										$range1=($hari-1).' hari';
										$tgl_internal.=' ( '.$range1.' )';
									}
									else
									{
										$tgl_internal='-';
										$range1='-';
									}
									if(!is_null($dt->ts))
									{
										$tgl_ts=date('d-m-Y',strtotime($dt->ts));
										$hari2=date('d',strtotime($dt->ts)-strtotime($dt->internal));
										$range2=($hari2-1).' hari';
										$tgl_ts.=' ( '.$range2.' )';
									}
									else
									{
										$tgl_ts='-';
										$range2='-';
									}
									?>
									<td><?php echo $tgl_risetman ?></td>
									<td><?php echo $tgl_internal;?></td>
									<td><?php echo $tgl_ts;?></td>
			
								</tr>
								<?php
								}
								?>
							</table>
							<?php } ?>
							
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
							<table  id="tabel" class=" table table-striped table-bordered"></table>
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
							<h4 class="modal-title" id="myModalLabel2"></h4>
						</div>
						<div class="modal-body">	
							<h5 id="deskripsi_kode"></h5>
							<p id="deskripsi_tgl"></p>
							<p id="deskripsi_risetman"></p>
							<p id="deskripsi_formulaby"></p>
							<p id="deskripsi_tujuan"></p>
							<p id="deskripsi_status"></p>
							<button type="button"><a href="" id="prn" target="_blank">Print</a></button>

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
//$('table').excelTableFilter();
		</script>
	
		</body>
</html>