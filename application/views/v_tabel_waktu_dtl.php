<html lang="en">
	<head>
		<title>Tabel</title>
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
							judul='Penilaian '+panelis_+' '+kode;
							//menaruh variabel html pada tabel
							 $('#tabel').html(html);
							 $('#myModalLabel').html(judul);
						 

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
							judul=obj[0].kode+' '+obj[0].tgl_riset;
							var deskripsi=obj[0].kode+'<\br>'+obj[0].tgl_riset;
							//menaruh variabel html pada tabel
							 $('#tabel2').html(html);
							 $('#myModalLabel2').html(judul);
							 $('#deskripsi_kode').html('Kode Formula :'+obj[0].kode);
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
								<input name="line" type="hidden" class="form-control" value="<?php echo  isset($line) ? $line : ''; ?>">

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
								<br />
							<?php
							$num=$this->Transaksi_model-> penilaian_all($items)->num_rows();

							if($num>0)
							{
							?>
							<table class="table table-bordered">	
							<thead>
							<th><center>Var</center></th>
							<th><center>Subvar</center></th>
							
							
							<?php
							$hd=$this->Transaksi_model->tabel_kode($items,$ke)->result();
							foreach($hd as $hd)
							{
							?>
							<th><center><a onclick="get_formula('<?php echo $hd->id_formula?>');"><u><?php echo $hd->kode.'<br>'.$hd->tanggal;?></u></a></center></th>
							<?php
							}?>
							</thead>
							<?php
							$list=$this->Transaksi_model->penilaian_all($items)->result();
							foreach($list as $list)
							{
							?>
							<tr>
							<td><?php echo $list->varr;?></td>
							<td><?php echo $list->subvar;?></td>
							<?php
							$nilai1=0;
							$nilai2=0;
							
							$numm=$this->Transaksi_model->tabel_kode($items,$ke)->num_rows();
							$hd=$this->Transaksi_model->tabel_kode($items,$ke)->result();
							$k=0;
							foreach($hd as $hd)
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
									$nilai="<font color='green'>".$nilai2."</font>";
									$bgcolor="#00ff00";
								}
								else if($nilai2<$nilai1)
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
									$bgcolor="white";
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