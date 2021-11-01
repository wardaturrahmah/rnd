<html lang="en">
	<head>
		<title>Tabel Detail Panelis Terakhir</title>
		<script>
		function get_dtl(id)
		{
			var arr = id.split("_");
			var kode =  arr[0];
			var ke =  arr[1];
			var id_item =  arr[2];
			var tgl =  arr[3];
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
							 html='<thead ><th class="text-center">Panelis</th><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Skala</th><th class="text-center">Keterangan</th></thead>';
							var var1='';
							var var2='';
							var panelis1='';
							var panelis2='';
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

							panelis2=obj[i].panelis;
							 if(panelis1!=panelis2)
							 {
								 var pan=0;
							 }
							panelis1=panelis2;
							pan++;
							mer[obj[i].panelis] = pan;
							}
							
							var var1='';
							var var2='';
							var panelis1='';
							var panelis2='';
							var jum_var=0;
							for(i=0; i<obj.length; i++){
								html +='<tr align="left">';
								panelis2=obj[i].panelis;
								if(panelis1!=panelis2)
								{
									html += '<td rowspan='+mer[obj[i].panelis]+'>'+obj[i].panelis+'</td>';
								}
								else
								{
									html += '';
								}
								panelis1=panelis2;
								
								var2=obj[i].varr;
								if(var1!=var2)
								{
									html += '<td rowspan='+mer[obj[i].varr]+'>'+obj[i].varr+'</td>';
								}
								else
								{
									html += '';
								}
								var1=var2;
								
								html += '<td>'+obj[i].subvar+'</td><td>'+Math.round(obj[i].nilai * 100) / 100+'</td><td>'+Math.round(obj[i].skala * 100) / 100+'</td><td>'+obj[i].keterangan+'</td>';
								html +='</tr>';
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
							judul='Penilaian '+panelis_+' '+kode;
							//menaruh variabel html pada tabel
							 $('#tabel').html(html);
							 $('#myModalLabel').html(judul);
							$('#deskripsi_produk').html('Nama Produk :'+obj[0].nama_item);
							 $('#deskripsi_kode').html('Kode Formula :'+obj[0].kode);
							 $('#deskripsi_tujuan').html('Tujuan :'+obj[0].tujuan);


					}
				});	
			
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
							 $('#deskripsi_produk').html('Nama Produk :'+obj[0].nama_item);
							 $('#deskripsi_kode').html('Nama Kompetitor :'+obj[0].nama);
							 $('#deskripsi_tujuan').html('Tujuan :'+obj[0].tujuan);

							 

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
							var deskripsi=obj[0].kode+'<\br>'+obj[0].tgl_riset;
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
							<h2>Laporan Detail Panelis Terakhir:3 nilai terakhir panelis TS</h2>
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
								<!--input name="line[]" type="hidden" class="form-control" value="<?php echo  isset($line) ? $line : ''; ?>">-->

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="panelis" id="panelis">
										
												<option <?php echo $panelis_selected == 3 ? 'selected="selected"' : '' ?> value="3">Taste Specialist</option>
													
										</select>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-info">Excel</button>
										
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
							$num=$this->Transaksi_model-> penilaian_all($items)->num_rows();

							if($num>0)
							{
								$jdl=$this->Transaksi_model->resume_item($items)->row();
								$jdl2=$this->Transaksi_model->lama_waktu2($items,3)->row();
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
										<td width="75%"><?php echo nl2br(htmlspecialchars($jdl->kompetitor));?></td>
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
											$tgl_awal=date('d',$tanggal);
											$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
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
											$tgl_awal=date('d',$tanggal);
											$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($hari_ini));
											$tgl_terakhir = date('Y-m-d', strtotime($hari_ini));
											$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
											$hari=$hari-1;
											
									echo $tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari ( '.date('d-m-Y',strtotime($jdl2->tgl_panelis)).' )';?></td>
								</tr>
								</table>
							<table class="table table-bordered">	
							<thead>
							<th><center>Var</center></th>
							<th><center>Subvar</center></th>
							
							
							<?php
							$hdk=$this->Transaksi_model->tabel_kode_kompetitor($items)->result();
							$hdr=$this->Transaksi_model->tabel_kode($items,$ke)->result();
							
							foreach($hdk as $hd2)
							{
							?>
							<th><center><?php echo $hd2->kode.'<br>'.date('d-m-Y',strtotime($hd2->tanggal));?></center></th>
							<?php
							}
							foreach($hdr as $hd)
							{
							?>
							<th><center><a onclick="get_formula('<?php echo $hd->id_formula?>');"><u><?php echo $hd->kode.'<br>'.date('d-m-Y',strtotime($hd->tanggal));?></u></a></center></th>
							<?php
							}?>
							</thead>
							<?php
							$listed=$this->Transaksi_model->penilaian_all($items)->result();
							$var1='';
							$var2='';
							$mer=array();
							foreach($listed as $list)
							{
								$var2=$list->varr;
								if($var1!=$var2)
								{
									$sv=0;
								}
								$sv++;
								$mer[$var2]=$sv;
								$var1=$var2;
							}
							$var1='';
							$var2='';
							foreach($listed as $list)
							{
								
								
							?>
							<tr>
							<?php
								$var2=$list->varr;
								if($var1!=$var2)
								{?>
									<td rowspan="<?php echo $mer[$var2];?>"><?php echo $list->varr;?></td>
								<?php }
								$var1=$var2;
							?>
							
							<td><?php echo $list->subvar;?></td>
							<?php
							$nilai1=0;
							$nilai2=0;
							$hdr=$this->Transaksi_model->tabel_kode($items,$ke)->result();
							$k=0;
							foreach($hdk as $hd2)
							{
								$k++;
								$num=$this->Transaksi_model->tabel_avg_param_kompetitor($items,$list->subvar,$hd2->kode,$hd2->tanggal)->num_rows();
								if($num>0)
								{
									$a=$this->Transaksi_model->tabel_avg_param_kompetitor($items,$list->subvar,$hd2->kode,$hd2->tanggal)->row();
									$nilai2=round($a->nilai,2);
								}
								else
								{
									$nilai2=0;
								}     
								?>
								<td><u><center>
								<a onclick="get_dtl_kompetitor('<?php echo $hd2->id_formula.'_'.$hd2->tanggal?>');"><?php echo $nilai2;?></a></center></u></td>
							

							<?php
							
							}
							foreach($hdr as $hd)
							{
								$k++;
								$num=$this->Transaksi_model->tabel_avg_param($items,$ke,$list->subvar,$hd->kode,$hd->tanggal)->num_rows();
								if($num>0)
								{
									$a=$this->Transaksi_model->tabel_avg_param($items,$ke,$list->subvar,$hd->kode,$hd->tanggal)->row();
									$nilai2=round($a->nilai,2);
								}
								else
								{
									$nilai2=0;
								}     
								
								if($nilai2>$nilai1)
								{
									$tanda='+';
								}
								else if($nilai2<$nilai1)
								{
									$tanda='-';
								}
								else if($nilai2==0)
								{
									$tanda='';
								}
								else
								{
									$tanda="";
								}
								if($k==1)
								{
									$tanda="";
								}
								if($nilai2<=70.9)
								{
									$nilai="<font color='red'>".$nilai2.$tanda."</font>";
									$bgcolor="#ffc0cb ";
								}
								else if(71<=$nilai2 and $nilai2<=72.9)
								{
									$nilai="<font color='black'>".$nilai2.$tanda."</font>";
									$bgcolor="yellow ";
								}
								else if($nilai2==73)
								{
									$nilai="<font color='green'>".$nilai2.$tanda."</font>";
									$bgcolor="#00ff00";
								}
								else if($nilai2>73)
								{
									$nilai="<font color='white'>".$nilai2.$tanda."</font>";
									$bgcolor="blue";
								}
								$nilai1=$nilai2;
							?>
								<td bgcolor="<?php echo $bgcolor;?>"><u><center>
								<a onclick="get_dtl('<?php echo $hd->kode.'_'.$ke.'_'.$item_selected.'_'.$hd->tanggal?>');"><?php echo $nilai;?></a></center></u></td>
							

							<?php
							
							}
							?>
							</tr>
							<?php
							}
							
							?>
							<tr>
										<td colspan="2">Sarana</td>
							<?php 
										foreach($hdk as $hd)
										{
							?>
											<td></td>
							<?php
										}
										foreach($hdr as $hd)
										{
											$sar=$this->Transaksi_model->sarana_formula($hd->id_formula)->row();
											if(count($sar)>0)
											{
												$sarana=$sar->sarana;
											}
											else
											{
												$sarana="";
											}?>
											<td><?php echo $sarana;?></td>
										<?php
										}
							?>
									</tr>
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
							<h4 class="modal-title" id="myModalLabel">Judul</h4>
						</div>
						<div class="modal-body">
							<p id="deskripsi_produk"></p>
							<p id="deskripsi_kode"></p>
							<p id="deskripsi_tujuan"></p>						
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
			$(document).ready(function() {
			$('#item').select2();
			});

		</script>
	
		</body>
</html>