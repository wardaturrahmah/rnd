<html lang="en">
	<head>
		<title>Laporan Date to Date</title>
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
							var obj = jQuery.parseJSON(data);
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
							 html='<thead ><th class="text-center">Panelis</th><th class="text-center">Var</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Skala</th><th class="text-center">Keterangan</th></thead>';
							 /* for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].nilai*10)/10;
								html += '<tr align=left><td>'+obj[i].panelis+'</td><td>'+obj[i].varr+'</td><td>'+obj[i].subvar+'</td><td>'+score+'</td><td>'+obj[i].keterangan+'</td></tr>';
							}
							 */
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
				
					$.ajax({
							url:'<?php echo base_url();?>mkt/get_tabel_sarana_formula',
							method:'post',
							data:{
									id_formula:kode,
								},
							success:function(data)
							{
								 var html2 = '';
								 var judul = '';
								 var i=0;
								 var obj = jQuery.parseJSON(data); //memisahkan variabel data menjadi array obj
								 html2='<thead ><th class="text-center">Sarana</th></thead>';
								 for(i=0; i<obj.length; i++){
									 
									html2 += '<tr align=left><td>'+obj[i].sarana+'</td></tr>';
								}
								$('#tabel3').html(html2);
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
							<h2>Laporan Date to Date : Laporan Panelis Taste Specialist dg rentang tanggal yang dipilih</h2>
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
										<select class="form-control" name="line" id="line" onchange="get_produk();">
										<?php
											foreach ($line as $line) {
												?>
												<option <?php echo $line_selected == $line->id_lp ? 'selected="selected"' : '' ?> value="<?php echo $line->id_lp ?>">
													<?php echo $line->lineproduk ?>
												</option>
												<?php
											}	
										?>					
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Item</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="item" id="item" class="kode form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
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
							if($tgl_awal!='')
							{
							$tgl1=date('Y-m-d',strtotime($tgl_awal));
							$tgl2=date('Y-m-d',strtotime($tgl_akhir));
							
							$num=$this->Transaksi_model->hdr_date($tgl1,$tgl2,$item_selected)->num_rows();
							if($num>0)
							{
								$ro=$this->Transaksi_model->hdr_date($tgl1,$tgl2,$item_selected)->result();
								$rok=$this->Transaksi_model->tabel_kode_kompetitor($item_selected)->result();
								$jdl=$this->Transaksi_model->resume_item($item_selected)->row();
								$jdl2=$this->Transaksi_model->lama_waktu2($item_selected,3)->row();
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
								<td width="25%">Konsep Sebelumnya :</td>
										<?php
										if($jdl->nama_konsep_sebelumnya!='')
										{?>
										<td width="75%"><?php echo $jdl->nama_konsep_sebelumnya;?></td>
										
										<?php
										}
										?>
							</tr>
							<tr>
									<td width="25%">Referensi :</td>
									<td>
									<?php
									$num_link=$this->Master_model->get_ref_link($jdl->id)->num_rows();
									if($num_link>0)
									{
										$link=$this->Master_model->get_ref_link($jdl->id)->result();
										foreach($link as $link)
										{
											echo $link->nama_item.',';
										}
									}
									?>	
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
							<tr>
									<td width="25%">Panelis</td>
									<td width="75%">Taste Specialist</td>
							</tr>
							<tr>
									<td width="25%">Total Formula</td>
									<td width="75%"><?php echo $num;?></td>
							</tr>
							<?php
								$formulaby=$this->Transaksi_model->list_formula_by2($item_selected,$tgl1,$tgl2)->result();
								foreach($formulaby as $fb)
								{?>
								<tr>
									
									<td width="25%"><?php echo $fb->risetman;?></td>
									<td width="75%"><?php echo $fb->jumlah;?></td>
								</tr>
							<?php }
							?>
							
							
							</table>
							
						
							</br>
							<div style="overflow-x:auto;overflow-y:auto;height:350px;">
							<table class="table table-bordered">
								<thead>
								<th width="100"><center></center></th>
								<th width="100"><center>Var</center></th>
								<th width="100"><center>Subvar</center></th>
							<?php
								foreach($rok as $dt)
								{
							?>
								<th width="100"><center><?php echo $dt->kode.'<br>'.date('d-m-Y',strtotime($dt->tanggal))?></center></th>
							<?php	
								}
							?>
							<?php
								foreach($ro as $dt)
								{
									if($dt->ke==1)
									{
										$state="Risetman";
									}
									else if($dt->ke==2)
									{
										$state="Internal";
									}
									else if($dt->ke==3)
									{
										$state="Taste Specialist";
									}
							?>
								<th width="100"><u><center><a onclick="get_formula('<?php echo $dt->id_formula?>');"><?php echo $dt->kode.'<br>'.date('d-m-Y',strtotime($dt->tanggal))?></a></center></u></th>
							<?php	
								}
							?>
								</thead>
							<?php
								$listed=$this->Transaksi_model->penilaian_all($item_selected)->result();
								$var1='';
								$var2='';
								
								foreach($listed as $list)
								{
									$var2=$list->varr;
									if($var2!=$var1)
									{
										$sv=0;
									}
									$sv++;
									$var[$list->varr]=$sv;
									$var1=$var2;
								}
								$var1='';
								$var2='';
								
								foreach($listed as $list)
								{
							?>
									<tr>
									<td></td>
									
									<?php
										$var2=$list->varr;
										if($var2!=$var1)
										{
									?>
											<td rowspan="<?php echo $var[$list->varr]?>"><?php echo $list->varr; ?></td>
									<?php
										}
										$var1=$var2;
									?>
									<td><?php echo $list->subvar;?></td>
							<?php
									$nilai1=0;
									$nilai2=0;
									$k=0;
									foreach($rok as $dt)
									{
										$numa=$this->Transaksi_model->tabel_avg_param_kompetitor($item_selected,$list->subvar,$dt->kode,$dt->tanggal)->num_rows();
										if($numa>0)
										{
											$a=$this->Transaksi_model->tabel_avg_param_kompetitor($item_selected,$list->subvar,$dt->kode,$dt->tanggal)->row();
											$nilai2=round($a->nilai,2);
										}
										else
										{
											$nilai2=0;
										}
								?>
											<td><u><center><a onclick="get_dtl_kompetitor('<?php echo $dt->id_formula.'_'.$dt->tanggal?>');"><?php echo $nilai2;?></center></a></u></td>
								<?php
										 	
									}
									foreach($ro as $dt)
									{
										$k++;
										$numa=$this->Transaksi_model->tabel_avg_param($item_selected,$dt->ke,$list->subvar,$dt->kode,$dt->tanggal)->num_rows();
										if($numa>0)
										{
											$a=$this->Transaksi_model->tabel_avg_param($item_selected,$dt->ke,$list->subvar,$dt->kode,$dt->tanggal)->row();
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
										if($numa>0)
										{
								?>
										
											<td bgcolor="<?php echo $bgcolor;?>"><u><center><a onclick="get_dtl('<?php echo $dt->kode.'_'.$dt->ke.'_'.$dt->id_item.'_'.$dt->tanggal?>');"><?php echo $nilai;?></a></center></u></td>
								<?php
										}
										else
										{
								?>
											<td bgcolor="<?php echo $bgcolor;?>"><u><center><a onclick="get_dtl('<?php echo $dt->kode.'_'.$dt->ke.'_'.$dt->id_item.'_'.$dt->tanggal?>');"><?php echo $nilai;?></a></center></u></td>
								
								<?php
										} 	
									}
								?>
								</tr>
							<?php
								}?>
								
							<?php
								$numk=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->num_rows();
								if($numk>0)
								{
							?>
								<tr>
								<td colspan="<?php echo $num+3;?>">Kesimpulan</td>
								</tr>								
							<?php	
									$kes_hdr=$this->Transaksi_model->hdr_kesimpulan_ts2($item_selected)->result();
									$panelis1='';
									$panelis2='';
									$var1='';
									$var2='';
									
									foreach($kes_hdr as $kes_hdr)
									{?>
										<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
										}
										
										$panelis1=$panelis2;
										if($kes_hdr->parameter=='base')
										{
											$parameter="Base";
										}
										else if($kes_hdr->parameter=='rasa_aroma')
										{
											$parameter="Rasa Aroma";
										}
										else if($kes_hdr->parameter=='total_rasa')
										{
											$parameter="Total Rasa";
										}
										$var2=$parameter;
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>			
									
									
										
									<td>Kesimpulan</td>
							<?php
										foreach($rok as $hd)
										{
											$kesimpulan="";
							?>
											<td><?php echo $kesimpulan?></td>
							<?php
										}
										foreach($ro as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$kesimpulan=$kes->kesimpulan;
											}
											else
											{
												$kesimpulan="";
											}
							?>
									<td><?php echo $kesimpulan?></td>
							<?php
										}
									?>
									</tr>
									<tr>
									<?php
										$panelis2=$kes_hdr->panelis;
										if($panelis2!=$panelis1)
										{?>
											<td rowspan="6"><?php echo $kes_hdr->panelis;?></td>		
									<?php
										}
										
										$panelis1=$panelis2;
										
										if($kes_hdr->parameter=='base')
										{
											$parameter="Base";
										}
										else if($kes_hdr->parameter=='rasa_aroma')
										{
											$parameter="Rasa Aroma";
										}
										else if($kes_hdr->parameter=='total_rasa')
										{
											$parameter="Total Rasa";
										}
										$var2=$parameter;
										if($var2!=$var1)
										{?>
											<td rowspan="2"><?php echo $parameter?></td>		
									<?php
										}
										
										$var1=$var2;
									?>		
									
									<td>Action Plan</td>
							<?php
										foreach($rok as $hd)
										{
											$saran="";
							?>
											<td><?php echo $saran?></td>
							<?php
										}
										foreach($ro as $hd)
										{
											$numk2=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->num_rows();
											if($numk2>0)
											{
												$kes=$this->Transaksi_model->kesimpulan_ts_dtl($hd->id_formula,$kes_hdr->panelis,$kes_hdr->parameter)->row();
												$saran=$kes->saran;
											}
											else
											{
												$saran="";
											}
							?>
											<td><?php echo $saran?></td>
							<?php
										}
							?>
										</tr>
										
							<?php
											
											
									}
							?>
									<tr>
										<td colspan="3">Sumber Masalah</td>
							<?php 
										foreach($rok as $hd)
										{
											$sumber_masalah="";
							?>
											<td><?php echo $sumber_masalah?></td>
							<?php
										}
										foreach($ro as $hd)
										{
											$numsm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->num_rows();
											if($numsm>0)
											{
												$sm=$this->Transaksi_model->get_penilaian_masalah($hd->id_formula,$ke)->result();
												$sumber_masalah="";

												foreach($sm as $sm)
												{
													$sumber_masalah.=$sm->masalah.',';
												}
												$sumber_masalah=rtrim($sumber_masalah,',');
												
											}
											else
											{
												$sumber_masalah="";
											}?>
											<td><?php echo $sumber_masalah;?></td>
										<?php
										}
							?>
									</tr>
									<tr>
										<td colspan="3">Deskripsi Masalah</td>
							<?php 
										foreach($rok as $hd)
										{
											$deskripsi="";
							?>
											<td><?php echo $deskripsi?></td>
							<?php
										}
										foreach($ro as $hd)
										{
											$num_desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->num_rows();
											if($num_desc_sm>0)
											{
												$desc_sm=$this->Transaksi_model->get_kesimpulan($hd->id_formula,$ke)->row();
												$desc=nl2br(htmlspecialchars($desc_sm->deskripsi));
											}
											else
											{
												$desc="";
											}
											?>
											<td><?php echo $desc;?></td>
										<?php
										}
							?>
									</tr>
									<tr>
										<td colspan="3">Action Plan</td>
							<?php 
										foreach($rok as $hd)
										{
							?>
											<td><?php echo $hd->keterangan?></td>
							<?php
										}
										foreach($ro as $hd)
										{
											$numac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->num_rows();
											if($numac>0)
											{
												$ac=$this->Transaksi_model->kesimpulan($hd->id_formula,$ke)->row();
												$action=$ac->action_plan;
											}
											else
											{
												$action="";
											}?>
											<td><?php echo nl2br(htmlspecialchars($action));?></td>
										<?php
										}
							?>
									</tr>
									<tr>
										<td colspan="3">Sarana</td>
							<?php 
										foreach($rok as $hd)
										{
							?>
											<td></td>
							<?php
										}
										foreach($ro as $hd)
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
							<?php
									
									
								}
								
							?>
							</table>
							</div>
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
							<table  id="tabel3" class=" table table-striped table-bordered"></table>
						</div>
					</div>
				</div>
				<br>
			</div>
		
		
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#line').select2();
			$('#item').select2();
			});
$thingToCollapse.collapse({ 'toggle': false }).collapse('hide');


		</script>
	
		</body>
</html>