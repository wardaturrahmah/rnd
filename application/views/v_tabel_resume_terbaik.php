<html lang="en">
	<head>
		<title>Laporan Resume Formula Terbaik</title>
		<script>
		
		function get_formula(id)
		{
			var arr = id.split("_");
			var kode =  arr[0];
			var id_penilaian =  arr[1];
			var judul='';
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_formula',
					method:'post',
					data:{
							kode:kode,
						},
					success:function(data)
					{
							
							 var html = '';
							 var i=0;
							 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
							//membuat header kolom
							 html='<thead ><th class="text-center">Kode Bahan</th><th class="text-center">Kategori</th><th class="text-center">Kadar</th></thead>';							//mengisi kolom anggota keluarga
							 for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].kadar*10)/10;
								html += '<tr align=left><td>'+obj[i].kode_bahan+'</td><td>'+obj[i].kategori+'</td><td>'+score+'</td></tr>';
							}
							var arr2 = obj[0].tgl_riset.split("-");
							var tanggal = arr2[2]+ '-' +arr2[1] + '-' +arr2[0];

							judul+='Formula '+obj[0].kode+' Tanggal '+tanggal;
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
				$.ajax({
							url:'<?php echo base_url();?>mkt/get_tabel_sarana_formula',
							method:'post',
							data:{
									id_formula:kode,
								},
							success:function(data)
							{
								 var html2 = '';
								 var i=0;
								 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
								 html2='<thead ><th class="text-center">Sarana</th></thead>';
								 for(i=0; i<obj.length; i++){
									 
									html2 += '<tr align=left><td>'+obj[i].sarana+'</td></tr>';
								}
								$('#tabel3').html(html2);
							}
						});		
				$.ajax({
							url:'<?php echo base_url();?>tabel/get_dtl_penilaian_terbaik',
							method:'post',
							data:{
									id_formula:kode,
									id_penilaian:id_penilaian,
								},
							success:function(data)
							{
								 var html3 = '';
								 var i=0;
								 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
								 html3='<thead ><th class="text-center">Panelis Subvar '+obj[0].subvar+'</th><th class="text-center">Nilai</th><th class="text-center">Skala</th><th class="text-center">Keterangan</th></thead>';
								 for(i=0; i<obj.length; i++){
									 
									html3 += '<tr align=left><td>'+obj[i].panelis+'</td><td>'+Math.round(obj[i].nilai * 100) / 100+'</td><td>'+Math.round(obj[i].skala * 100) / 100+'</td><td>'+obj[i].keterangan+'</td></tr>';
								}
								$('#tabel4').html(html3);
								judul+=' Subvar '+obj[0].subvar;
								
							}
						});		
			$('#myModal2').modal('show');
		}  
		
		
		</script>
	

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
								<h2>Laporan Resume Formula Terbaik : Laporan Formula Terbaik dari Semua Produk</h2>
								
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
							<?php
								
								foreach($produk as $pro)
								{
									$jdl=$this->Transaksi_model->resume_item($pro->id)->row();
									$total_formula=$this->Transaksi_model->list_formula($pro->id)->num_rows();
									echo '<table width="100%">';
									echo '<tr>';
										echo '<td width="25%">Line Produk</td>';
										echo '<td width="75%">'.$jdl->lineproduk.'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td width="25%">Nama Produk</td>';
										echo '<td width="75%">'.$jdl->nama_item.'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td width="25%">Awal Riset</td>';
										echo '<td width="75%">'.date('d-m-Y',strtotime($jdl->awal_riset)).'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td width="25%">Risetman</td>';
										echo '<td width="75%">'.$jdl->risetman.'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td width="25%">Target Riset</td>';
										echo '<td width="75%">'.nl2br(htmlspecialchars($jdl->kompetitor)).'</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td width="25%">Referensi Kompetitor</td>';
										$link=$this->Transaksi_model->list_kompetitor($pro->id)->result();
										echo '<td width="75%">';
										foreach($link as $link)
										{
											
											if($link->status_kompetitor==1)
											{
												echo '<a href="'.base_url().'mkt/kompetitor_dtl/'.$link->id_kompetitor.'-'.$pro->id.'"  target="_blank"><u> '.$link->nama.' </u>,</a>';
											}
											
										}
										echo '</td>';
										
									echo '</tr>';
									echo '<tr>';
										echo '<td width="25%">Lama Waktu Riset</td>';
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
										echo '<td width="75%">'.$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari</td>';
									echo '</tr>';
									echo '</table>';
									echo '</br>';
									$terbaik=$this->Transaksi_model->rekap_formula_terbaik($pro->id)->row();
									if(count($terbaik)>0)
									{
										$dtl=$this->Transaksi_model->dtl_formula_terbaik($terbaik->id)->result();
										echo '<table  class="table table-striped table-bordered">';
										echo '<thead>';
										echo '<th>Var</th>';
										echo '<th>Subar</th>';
										echo '<th>Formula Terbaik '.date("d-m-Y",strtotime($terbaik->tanggal)).'</th>';
										echo '</thead>';
										
										foreach($dtl as $dt)
										{
											
												echo '<tr>';
													echo '<td>'.$dt->varr.'</td>';
													echo '<td>'.$dt->subvar.'</td>';
													$ln="'".$dt->id_formula.'_'.$dt->id."'";
													echo '<td><a onclick="get_formula('.$ln.')"><u>'.$dt->kode.'</u></a></td>';
												echo '</tr>';
											
										}
										echo '</table>';
									}
									echo '<div class="ln_solid"></div>';
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
							<table  id="tabel2" class=" table table-striped table-bordered"></table>
							<table  id="tabel3" class=" table table-striped table-bordered"></table>
							<table  id="tabel4" class=" table table-striped table-bordered"></table>
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