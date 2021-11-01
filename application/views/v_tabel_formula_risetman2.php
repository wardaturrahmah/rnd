<html lang="en">
	<head>
		<title>Tabel Detail Risetman</title>
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
							get_risetman();
					}
				});	
				
			}
			else//jika unit kosong, pilihan kembali kosong
			{
				 html = '<option> - </option>';
				 $('#item').html(html);	
			}
			
		} 
		
		function get_risetman(){
			var item=$( "#item option:selected" ).val();
			var b='<?php echo $risetman_selected;?>';
			
			if(item!='')
			{
				$.ajax({
					url:'<?php echo base_url();?>tabel/get_risetman',
					method:'post',
					data:{
							id_item:item,
						},
					success:function(data)
					{
							
							var html = '';
							var i=0;
							var obj = jQuery.parseJSON(data);//memisahkan data, yang berisi array koding
							//loop opsi
							for(i=0; i<obj.length; i++){
								html += '<option value="'+obj[i].risetman+'">'+obj[i].risetman+'</option>';
							}
							$('#risetman').html(html);//mengembalikan nilai option pada id kode			
							if(b!='')
							{
								$('#risetman').val(b);
							}
							

					}
					
				});	
			}
			else//jika unit kosong, pilihan kembali kosong
			{
				 html = '<option> - </option>';
				 $('#risetman').html(html);	
			}
		}  
		 
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
							else
							{
								var panelis_='';
							}
							judul='Penilaian '+panelis_+' '+kode;
							//menaruh variabel html pada tabel
							 $('#tabel').html(html);
							 $('#myModalLabel').html(judul);
						 

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
							 html='<thead ><th class="text-center">Kode Bahan</th><th class="text-center">Kadar</th></thead>';							//mengisi kolom anggota keluarga
							 for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].kadar*10)/10;
								html += '<tr align=left><td>'+obj[i].kode_bahan+'</td><td>'+score+'</td></tr>';
							}
							judul='Formula '+obj[0].kode+' Tanggal '+obj[0].tgl_riset;
							var deskripsi=obj[0].kode+'<\br>'+obj[0].tgl_riset;
							//menaruh variabel html pada tabel
							 $('#tabel2').html(html);
							 $('#myModalLabel2').html(judul);
							 $('#deskripsi_kode').html('Seri Formula :'+obj[0].kode);
							 $('#deskripsi_tgl').html('Tanggal Riset :'+obj[0].tgl_riset);
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
							<h2>Laporan detail risetman (Laporan ada di link LPR) : Berisi semua penilaian produk pada range tanggal</h2>
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
										<select class="form-control"  readonly name="line" id="line" onchange="get_produk();">
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
										<select name="item" id="item" required class="kode form-control" onchange="get_risetman()";>
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
												<input type='text' class="form-control" name="tgl_awal" value="<?php echo  isset($tgl_awal) ? $tgl_awal : ''; ?>"/>
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
												<input type='text' class="form-control" name="tgl_akhir" value="<?php echo  isset($tgl_akhir) ? $tgl_akhir : ''; ?>"/>
												<span class="input-group-addon">
												   <span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Risetman</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="risetman" id="risetman" required class="form-control">
											<option> - </option>
											<?php
												echo ! empty($option) ? $option : '';
											?>
										</select>			
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<input type="submit" class="btn btn-success" value="Submit" onclick="javascript: form.action='<?php echo $form4?>';"/>
										<input type="submit" class="btn btn-danger" value="Back" onclick="javascript: form.action='<?php echo $form?>';"/>
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
							
							$tgl1=date('Y-m-d',strtotime($tgl_awal));
							$tgl2=date('Y-m-d',strtotime($tgl_akhir));
							$num=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,$ke)->num_rows();
							if($num>0)
							{
								
							
							$jdl=$this->Transaksi_model->hdr_penilaian_risetmans2($tgl1,$tgl2,$item_selected,$risetman,$ke)->row();?>
							
							<table width="100%">
							<tr>
							<td width="30%">Produk Line</td>
							<td width="70%"><?php echo $jdl->lineproduk ?></td>
							</tr>
							<tr>
							<td width="30%">Nama Produk</td>
							<td width="70%"><?php echo $jdl->nama_item ?></td>
							</tr>
							<tr>
							<td valign="top" width="30%">Target Riset</td>
							<td width="70%"><?php echo nl2br(htmlspecialchars($jdl->kompetitor)); ?></td>
							</tr>
							<tr>
							<td width="30%">Risetman</td>
							<td width="70%"><?php echo $jdl->risetman ?></td>
							</tr>
							<tr>
							<td width="30%">Awal Riset Produk</td>
							<td width="70%"><?php echo date('d-m-Y',strtotime($jdl->awal_riset)) ?></td>
							</tr>
							<tr>
							<td width="30%">Awal Riset by Risetman</td>
							<td width="70%"><?php echo date('d-m-Y',strtotime($jdl->tgl_awal)) ?></td>
							</tr>
							<tr>
							<td width="30%">Terakhir Panelis</td>
							<td width="70%"><?php echo date('d-m-Y',strtotime($jdl->tgl_panelis)) ?></td>
							</tr>
							<tr>
							<td width="30%">Total Formula</td>
							<td width="70%"><?php 
							$num0=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,0)->num_rows();
							echo $num0 ?></td>
							</tr>
							<tr>
							<td width="30%">Total Panelis Risetman</td>
							<td width="70%"><?php 
							$num1=$this->Transaksi_model->jum_panelis_dates($tgl1,$tgl2,$item_selected,$risetman,1)->num_rows();
							echo $num1.'( '.round(($num1/$num0*100),2).' % )' ?></td>
							</tr>
							<tr>
							<td width="30%">Total Panelis Internal</td>
							<td width="70%"><?php 
							$num2=$this->Transaksi_model->jum_panelis_dates($tgl1,$tgl2,$item_selected,$risetman,2)->num_rows();
							echo $num2 .'( '.round(($num2/$num0*100),2).' % )' ?></td>
							</tr>
							<tr>
							<td width="30%">Total Panelis Taste Specialist</td>
							<td width="70%"><?php 
							$num3=$this->Transaksi_model->jum_panelis_dates($tgl1,$tgl2,$item_selected,$risetman,3)->num_rows();
							echo $num3 .'( '.round(($num3/$num0*100),2).' % )' ?></td>
							</tr>
							</table>
							<br/>
							<form class="form-horizontal form-label-left">
							<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Panelis</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="panelis" id="panelis" class="kode form-control">
												<option <?php echo $ke == 0 ? 'selected="selected"' : '' ?> value="0">ALL</option>
												<option <?php echo $ke == 1 ? 'selected="selected"' : '' ?> value="1">Risetman</option>
												<option <?php echo $ke == 2 ? 'selected="selected"' : '' ?> value="2">Internal</option>
												<option <?php echo $ke == 3 ? 'selected="selected"' : '' ?> value="3">Taste Specialist</option>
										</select>			
									</div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										<input type="submit" class="btn btn-success" value="Submit" onclick="javascript: form.action='<?php echo $form3?>';"/>
										<input type="submit" class="btn btn-info" value="Excel" onclick="javascript: form.action='<?php echo $form2?>';"/>
								
									</div>
									
							</div>
							</form>
							<?php
							$num=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,$ke)->num_rows();
							if($num>0)
							{
								
							?>
							
							<br/>
							
							<br/>
							<div style="overflow-x:auto;overflow-y:auto;height:350px;">
							<table class="table table-bordered" width="100%">
								<thead>
								<th width="25%"><center>Var</center></th>
								<th width="25%"><center>Subvar</center></th>
								
							<?php
								$ro=$this->Transaksi_model->hdr_dates3($tgl1,$tgl2,$item_selected,$risetman,$ke)->result();
								foreach($ro as $dt)
								{
									if($dt->ke==1)
									{
										$state="Risetman";
										$tgl=date('d-m-Y',strtotime($dt->tanggal));
									}
									else if($dt->ke==2)
									{
										$state="Internal";
										$tgl=date('d-m-Y',strtotime($dt->tanggal));
									}
									else if($dt->ke==3)
									{
										$state="Taste Specialist";
										$tgl=date('d-m-Y',strtotime($dt->tanggal));
									}
									else
									{
										$state="Belum Panelis";
										$tgl='';
									}
							?>
								<th width="10%"><u><center><a onclick="get_formula('<?php echo $dt->id_formula?>');"><?php echo $dt->kode.'<br>'.$state.'<br>'.$tgl?></a></center></u></th>
							<?php	
								}
							?>
								</thead>
								<?php 
									$listed=$this->Transaksi_model->nilai_ldr($item_selected,$ke,date('Y-m-d',strtotime($tgl_awal)),date('Y-m-d',strtotime($tgl_akhir)),$risetman_selected)->result();
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
										
										echo '<tr>';
										$var2=$list->varr;
										if($var2!=$var1)
										{
											echo '<td rowspan="'.$mer[$var2].'">'.$list->varr.'</td>';	
										}
										$var1=$var2;
										echo '<td>'.$list->subvar.'</td>';
										$nilai1=0;
										$nilai2=0;
										$k=0;
										foreach($ro as $hd)
										{

											$k++;
											if(!empty($hd->ke))
											{
											$vnilai='nilai '.$hd->id_formula.' '.$hd->tanggal;
											$nilai2=round($list->$vnilai,2);
											}
											else
											{$nilai2=0;}
											$bgcolor="#00ff00";
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
											
										?>
											<td bgcolor="<?php echo $bgcolor;?>"><center><a onclick="get_dtl('<?php echo $hd->kode.'_'.$hd->ke.'_'.$hd->id_item.'_'.$hd->tanggal;?>');"><u><?php echo $nilai;?></u></a></center></td>
										<?php
											$nilai1=$nilai2;
										}
										
										echo '</tr>';
									}
									
								?>
								<tr>
										<td colspan="2">Sarana</td>
							<?php 
										
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
						<p id="saran"></p>
							<p id="deskripsi_produk"></p>
							<p id="deskripsi_kode"></p>
							<p id="deskripsi_tujuan"></p>						
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
			$('#risetman').select2();
			});

		</script>
	
		</body>
</html>