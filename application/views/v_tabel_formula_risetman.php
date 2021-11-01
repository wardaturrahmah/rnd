<html lang="en">
	<head>
		<title>Tabel</title>
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
							 html='<thead ><th class="text-center">Panelis</th><th class="text-center">Subvar</th><th class="text-center">Nilai</th><th class="text-center">Keterangan</th></thead>';							//mengisi kolom anggota keluarga
							 for(i=0; i<obj.length; i++){
								 var score=Math.round(obj[i].nilai*10)/10;
								html += '<tr align=left><td>'+obj[i].panelis+'</td><td>'+obj[i].subvar+'</td><td>'+score+'</td><td>'+obj[i].keterangan+'</td></tr>';
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
										<select name="item" id="item" readonly class="kode form-control">
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
												<input type='text' readonly class="form-control" name="tgl_awal" value="<?php echo  isset($tgl_awal) ? $tgl_awal : ''; ?>"/>
												
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Akhir</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<div class='input-group date' id='myDatepicker2'>
												<input type='text' readonly class="form-control" name="tgl_akhir" value="<?php echo  isset($tgl_akhir) ? $tgl_akhir : ''; ?>"/>
												
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Risetman</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" readonly style="width: 200px" name="risetman" class="form-control" value="<?php echo ! empty($risetman) ? $risetman : '' ;?>"/>
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-danger">Back</button>
										
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
							
							$jdl=$this->Transaksi_model->hdr_penilaian_risetman2($tgl1,$tgl2,$item_selected,$risetman,$ke)->row();?>
							
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
							<td width="30%">Kompetitor</td>
							<td width="70%"><?php echo $jdl->kompetitor ?></td>
							</tr>
							<tr>
							<td width="30%">Risetman</td>
							<td width="70%"><?php echo $jdl->risetman_hdr ?></td>
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
							$num0=$this->Transaksi_model->hdr_date3($tgl1,$tgl2,$item_selected,$risetman,0)->num_rows();
							echo $num0 ?></td>
							</tr>
							<tr>
							<td width="30%">Total Panelis Risetman</td>
							<td width="70%"><?php 
							$num1=$this->Transaksi_model->jum_panelis_date($tgl1,$tgl2,$item_selected,$risetman,1)->num_rows();
							echo $num1.'( '.round(($num1/$num0*100),2).' % )' ?></td>
							</tr>
							<tr>
							<td width="30%">Total Panelis Internal</td>
							<td width="70%"><?php 
							$num2=$this->Transaksi_model->jum_panelis_date($tgl1,$tgl2,$item_selected,$risetman,2)->num_rows();
							echo $num2 .'( '.round(($num2/$num0*100),2).' % )' ?></td>
							</tr>
							<tr>
							<td width="30%">Total Panelis Taste Specialist</td>
							<td width="70%"><?php 
							$num3=$this->Transaksi_model->jum_panelis_date($tgl1,$tgl2,$item_selected,$risetman,3)->num_rows();
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
							$num=$this->Transaksi_model->hdr_date3($tgl1,$tgl2,$item_selected,$risetman,$ke)->num_rows();
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
								$rok=$this->Transaksi_model->tabel_kode_kompetitor($item_selected)->result();
								$ro=$this->Transaksi_model->hdr_date3($tgl1,$tgl2,$item_selected,$risetman,$ke)->result();
								foreach($rok as $dt)
								{
									
							?>
								<th width="10%"><center><?php echo $dt->kode.'<br><br>'.date('d-m-Y',strtotime($dt->tanggal))?></center></th>
							<?php	
								}
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
								/* $bahan=$this->Transaksi_model->rekap_bahan($item_selected)->result();
								foreach($bahan as $bahan)
								{ */?>
									<!--<tr>
									<td><?php //echo $bahan->kode_bahan;?></td>
									<td><?php //echo $bahan->kategori;?></td>-->
							<?php	
									/* $k=0;
									$kadar1=0;
									foreach($ro as $dt)
									{
										$k++;
										$numk=$this->Transaksi_model->kadar_bahan($dt->id_formula,$bahan->kode_bahan)->num_rows();
										if($numk>0)
										{
										$kadar=$this->Transaksi_model->kadar_bahan($dt->id_formula,$bahan->kode_bahan)->row();
										$kadar2=round($kadar->kadar,3);
										}
										else
										{
											$kadar2=0;
										}
										
										if($kadar1==$kadar2)
										{
											$kadar="<font color='black'>".$kadar2."</font>";
											$bgcolor="white ";
										}
										else
										{
											$kadar="<font color='white'>".$kadar2."</font>";
											$bgcolor="#bf00ff";
										}
										if($k==1)
										{
											$kadar="<font color='black'>".$kadar2."</font>";
											$bgcolor="white ";
										}
										$kadar1=$kadar2; */
										?>
									<!--<td bgcolor="<?php echo $bgcolor;?>"><center><?php echo $kadar;?></center></td>-->
								<?php
									
									//}?>
									<!--</tr>-->
								<?php
									//}
								$list=$this->Transaksi_model->penilaian_all($item_selected)->result();
								foreach($list as $list)
								{
							?>
									<tr>
									<td><?php echo $list->varr;?></td>
									<td><?php echo $list->subvar;?></td>
							<?php
								$nilai1=0;
								$nilai2=0;
								$k=0;
								foreach($rok as $dt)
								{
									
									$num2=$this->Transaksi_model->tabel_avg_param_kompetitor($item_selected,$list->subvar,$dt->kode,$dt->tanggal)->num_rows();
									if($num2>0)
									{
										$a=$this->Transaksi_model->tabel_avg_param_kompetitor($item_selected,$list->subvar,$dt->kode,$dt->tanggal)->row();
										$nilai2=round($a->nilai,2);
										
									}
									else
									{
										$nilai2=0;

									}
										 ?>
											<td><center><a onclick="get_dtl_kompetitor('<?php echo $dt->id_formula.'_'.$dt->tanggal;?>');"><u><?php echo $nilai2;?></u></a></center></td>

										

										<?php
										
									
								}
								foreach($ro as $dt)
								{
									$k++;
									if($dt->ke!='')
									{
										$ke=$dt->ke;
									}
									else
									{
										$ke=0;
									}
									$num2=$this->Transaksi_model->tabel_avg_param($item_selected,$ke,$list->subvar,$dt->kode,$dt->tanggal)->num_rows();
									if($num2>0)
									{
										$a=$this->Transaksi_model->tabel_avg_param($item_selected,$ke,$list->subvar,$dt->kode,$dt->tanggal)->row();
										$nilai2=round($a->nilai,2);
										
									}
									else
									{
										$nilai2=0;

									}
										if($nilai2>$nilai1)
										{
											$nilai="<font color='green'>".$nilai2."</font>";
											$bgcolor="#00ff00";
										}
										else if($nilai2<$nilai1)
										{
											$nilai="<font color='red'>".$nilai2."</font>";
											$bgcolor="#ffc0cb ";
										}
										else if($nilai2==0)
										{
											$nilai="<font color='red'>".$nilai2."</font>";
											$bgcolor="#ffc0cb ";
										}
										else
										{
											$nilai="<font color='black'>".$nilai2."</font>";
											$bgcolor="yellow ";
										}
										
										if($k==1)
										{
											$nilai="<font color='black'>".$nilai2."</font>";
											$bgcolor="white ";
										}
										$nilai1=$nilai2;
										 ?>
											<td bgcolor="<?php echo $bgcolor;?>"><center><a onclick="get_dtl('<?php echo $dt->kode.'_'.$ke.'_'.$dt->id_item.'_'.$dt->tanggal;?>');"><u><?php echo $nilai;?></u></a></center></td>

										

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

		</script>
	
		</body>
</html>