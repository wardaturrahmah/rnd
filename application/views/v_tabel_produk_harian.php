<html lang="en">
	<head>
		<title>Laporan Produk Harian</title>
		<script>
		function get_produk(){
		var line=$( "#line option:selected" ).val();
		var a='<?php echo $item_selected;?>';
			
			if(line!='')
			{
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_produk',
					method:'post',
					data:{
							id_lp:line,
						},
					success:function(data)
					{
							
							var html = '';
							var i=0;
							var obj = jQuery.parseJSON(data);//memisahkan data, yang berisi array koding
							//loop opsi
							for(i=0; i<obj.length; i++){
								html += '<option value="'+obj[i].id+'">'+obj[i].nama_item+'</option>';
							}
							$('#item').html(html);//mengembalikan nilai option pada id kode			
							if(a!='')
							{
								$('#item').val(a);
							}

					}
				});	
			}
			else//jika unit kosong, pilihan kembali kosong
			{
				 html = '<option> - </option>';
				 $('#item').html(html);	
			}
			
		}  
		
		
		function get_dtl(id)
		{
			var arr = id.split("_");
			var kode =  arr[0];
			var ke =  arr[1];
			var id_item =  arr[2];
			var tgl =  arr[3];
			var tgl2 =new Date(tgl)
			var tanggal = tgl2.getDate()+ '-' + (tgl2.getMonth() + 1) + '-' + tgl2.getFullYear();

				$.ajax({
					url:'<?php echo base_url();?>tabel/get_penilaian_ke',
					method:'post',
					data:{
							kode:kode,
							ke:ke,
							id_item:id_item,
							tgl:tgl,
						},
					success:function(data)
					{
							
							 var html = '';
							 var judul = '';
							 var i=0;
							 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
							//membuat header kolom
							 html='<thead ><th class="text-center">Panelis</th><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Keterangan</th></thead>';							//mengisi kolom anggota keluarga
							 for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].nilai*10)/10;
								html += '<tr align=left><td>'+obj[i].panelis+'</td><td>'+obj[i].varr+'</td><td>'+obj[i].subvar+'</td><td>'+score+'</td><td>'+obj[i].keterangan+'</td></tr>';
							}
							if(ke==1)
							{
								var panelis_='Panelis Risetman';
							}
							else if(ke==2)
							{
								var panelis_='Panelis Internal';
							}
							else if(ke==3)
							{
								var panelis_='Panelis Taste Specialist';
							}
							var tgl_riset =new Date(obj[0].tgl_riset)
							var tanggal_riset = tgl_riset.getDate()+ '-' + (tgl_riset.getMonth() + 1) + '-' + tgl_riset.getFullYear();

							judul='Penilaian '+panelis_+' Formula '+kode+' Tanggal '+tanggal;
							//menaruh variabel html pada tabel
							 $('#tabel').html(html);
							 $('#myModalLabel').html(judul);
							 $('#deskripsi_produk2').html('Nama Produk :'+obj[0].nama_item);
							 $('#deskripsi_kode2').html('Seri Formula :'+obj[0].kode);
							 $('#deskripsi_tgl2').html('Tanggal Riset :'+tanggal_riset);
							 $('#deskripsi_risetman2').html('Risetman :'+obj[0].risetman_hdr);
							 $('#deskripsi_formulaby2').html('Formula By :'+obj[0].risetman);
							 $('#deskripsi_tujuan2').html('Tujuan :'+obj[0].tujuan);
							 

					}
				});	
			document.getElementById("prn2").href="print_nilai/"+id; 
			$('#myModal').modal('show');
		}
		
		function get_dtl_kompetitor(id)
		{
			var arr = id.split("_");
			var id_formula =  arr[0];
			var tgl =  arr[1];
			var tgl2 =new Date(tgl)
			var tanggal = tgl2.getDate()+ '-' + (tgl2.getMonth() + 1) + '-' + tgl2.getFullYear();

				$.ajax({
					url:'<?php echo base_url();?>tabel/get_penilaian_kompetitor',
					method:'post',
					data:{
							id_formula:id_formula,
							tgl:tgl,
						},
					success:function(data)
					{
							
							 var html = '';
							 var judul = '';
							 var i=0;
							 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
							//membuat header kolom
							 html='<thead ><th class="text-center">Panelis</th><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Keterangan</th></thead>';							//mengisi kolom anggota keluarga
							 for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].nilai*10)/10;
								html += '<tr align=left><td>'+obj[i].panelis+'</td><td>'+obj[i].varr+'</td><td>'+obj[i].subvar+'</td><td>'+score+'</td><td>'+obj[i].keterangan+'</td></tr>';
							}
							
							
							judul='Penilaian Kompetitor';
							//menaruh variabel html pada tabel
							 $('#tabel').html(html);
							 $('#myModalLabel').html(judul);
							 $('#deskripsi_produk2').html('Nama Produk :'+obj[0].nama_item);
							 $('#deskripsi_kode2').html('Nama Kompetitor :'+obj[0].nama);
							 $('#deskripsi_tujuan2').html('Tujuan :'+obj[0].tujuan);

							 

					}
				});	
			document.getElementById("prn2").href="print_nilai/"+id; 
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
	<body class="nav-md" onload="get_produk();">

<?php
$this->load->model('Transaksi_model', '', TRUE);
$this->load->model('Master_model', '', TRUE);
$ke=3;
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
							<h2>Item</h2>
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
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Produk</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="produk" id="produk">
										<?php
											foreach ($produk as $produk) {
												?>
												<option <?php echo $produk_selected == $produk->id ? 'selected="selected"' : '' ?> value="<?php echo $produk->id ?>">
													<?php echo $produk->nama_item ?>
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
										<input type="submit" class="btn btn-info" value="Excel" onclick="javascript: form.action='<?php echo $form2?>';"/>
									
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
							if($produk_selected!='')
							{
							?>
								<h4><b>Formula dibuat</b></h4>
								<div style="overflow-x:auto;">
								<table class="table table-bordered">
								<thead>
								<th>Risetman</th>
								<th>Item</th>
								<?php
								$tgl1=$tgl_awal;
								$tgl2=$tgl_akhir;
								while (strtotime($tgl1) <= strtotime($tgl2)) {
								?>
								<th><?php echo $tgl1;?></th>
								<?php 
									$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
								}
								?>
								</thead>
								<?php
								
								$dt=$this->Transaksi_model->rekap_harian_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
								foreach($dt as $dt)
								{
								if(!empty($dt->risetman_hdr))
								{
								?>
								<tr>
									<td><?php echo $dt->risetman_hdr;?></td>
									<td><?php echo $dt->nama_item;?></td>
								<?php
									$tgl1=$tgl_awal;
									$tgl2=$tgl_akhir;
									while (strtotime($tgl1) <= strtotime($tgl2)) 
									{
									$tgl=date("Y-m-d", strtotime($tgl1));
									if(empty($dt->$tgl))
									{
										$jum=0;
									}
									else
									{
										$jum=$dt->$tgl;
									}
								?>
									
										
									<td><?php echo $jum;?></td>
								<?php 
									$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

									}
									?>
									</tr>
									<?php
								}
								}
								?>
								</table>
								</div>
								<h4><b>Kontribusi</b></h4>
								<div style="overflow-x:auto;">
								<table class="table table-bordered">
								<thead>
								<th>Risetman</th>
								<th>Item</th>
								<?php
								$tgl1=$tgl_awal;
								$tgl2=$tgl_akhir;
								while (strtotime($tgl1) <= strtotime($tgl2)) {
								?>
								<th><?php echo $tgl1;?></th>
								<?php 
									$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));
								}
								?>
								</thead>
								<?php
								
								$dt=$this->Transaksi_model->rekap_kontribusi_harian_produk($produk_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
								foreach($dt as $dt)
								{
								if(!empty($dt->risetman))
								{
								?>
								<tr>
									<td><?php echo $dt->risetman;?></td>
									<td><?php echo $dt->nama_item;?></td>
								<?php
									$tgl1=$tgl_awal;
									$tgl2=$tgl_akhir;
									while (strtotime($tgl1) <= strtotime($tgl2)) 
									{
									$tgl=date("Y-m-d", strtotime($tgl1));
									if(empty($dt->$tgl))
									{
										$jum=0;
									}
									else
									{
										$jum=$dt->$tgl;
									}
								?>
									
										
									<td><?php echo $jum;?></td>
								<?php 
									$tgl1 = date ("d-m-Y", strtotime("+1 days", strtotime($tgl1)));

									}
									?>
									</tr>
									<?php
								}
								}
								?>
								</table>
								</div>
							<?php
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
							<h5 id="deskripsi_produk2"></h5>
							<h5 id="deskripsi_kode2"></h5>
							<p id="deskripsi_tgl2"></p>
							<p id="deskripsi_risetman2"></p>
							<p id="deskripsi_formulaby2"></p>
							<p id="deskripsi_tujuan2"></p>
							<p id="deskripsi_status2"></p>
							<button type="button"><a href="" id="prn2" target="_blank">Print</a></button>
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
							<h5 id="deskripsi_produk"></h5>
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
			$('#produk').select2();
			});
$thingToCollapse.collapse({ 'toggle': false }).collapse('hide');


		</script>
	
		</body>
</html>