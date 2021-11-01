<html lang="en">
	<head>
		<title>Laporan Lama Waktu Riset</title>
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
							<h2>Laporan Lama Waktu Riset: Laporan lama waktu riset produk, lama waktu konsep, list konsep, waktu panelis terakhir</h2>
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
								<br />
								<?php
							if($lines!='')
							{
								//echo $lines;
								$num=$this->Transaksi_model->lama_waktu_akses($lines)->num_rows();
								if($num>0)
								{
							?>
								<table class="table table-bordered">
									<thead>
										<th><center>No</center></th>
										<th><center>Produk Line</center></th>
										<th><center>Nama Produk</center></th>
										<th><center>Risetman</center></th>
										<th><center>Tanggal Awal Riset</center></th>
										<th><center>Lama Waktu Riset</center></th>
										<th><center>Lama Waktu Efektif Riset</center></th>
										<th><center>Tanggal Awal Konsep</center></th>
										<th><center>Lama Waktu Konsep</center></th>
										<th><center>Terakhir Panelis</center></th>
										<th><center>Lama Waktu Terakhir Panelis</center></th>
										<th><center>Terakhir Panelis Real</center></th>
										<th><center>Lama Waktu Terakhir Panelis Real</center></th>
										<th><center>Status</center></th>
										<th><center>Tanggal Status</center></th>
									</thead>
							<?php
									$no=0;
									$ro=$this->Transaksi_model->lama_waktu_akses($lines)->result();
									foreach($ro as $dt)
									{
										$no++;
							?>
									<tr>
										<td><?php echo $no;?></td>
										<td><?php echo $dt->lineproduk;?></td>
										<td><?php echo $dt->nama_item;?></td>
										<td><?php echo $dt->risetman;?></td>
										<td><?php echo date('d-m-Y',strtotime($dt->awal_riset));?></td>
										<td><?php 
										$hari_ini = date("Y-m-d");
										if($dt->status==0)
										{
											$status="Progress";
											$akhir=date("Y-m-d");
										}
										else if($dt->status==-1)
										{
											$status="Terminate";
											$akhir=date("Y-m-d",strtotime($dt->tgl_status));
										}
										else if($dt->status==1)
										{
											$status="Launching";
											$akhir=date("Y-m-d",strtotime($dt->tgl_status));
										}
										else if($dt->status==2)
										{
											$status="Bank Produk-ACC";
											$akhir=date("Y-m-d",strtotime($dt->tgl_status));
										}
										$cek=$this->Transaksi_model->pending_now($dt->id)->row();
										if(count($cek)>0)
										{
											$status="Pending";
											$akhir=date('d-m-Y',strtotime($cek->tgl_awal));
										}
										$selisih=(strtotime($akhir)-strtotime($dt->awal_riset))/3600/24;
										if($selisih>365)
										{
											$font="red";
										}
										else
										{
											$font="";
										}
										$tahun=floor($selisih/365);
										$sisa=$selisih-($tahun*365);
										$bulan=floor($sisa/30);
										$tgl_awal=date('d',strtotime($dt->awal_riset));
										$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
										$tgl_terakhir = date('Y-m-d', strtotime($akhir));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										$hari=$sisa-($bulan*30)-1;
										?><font color="<?php echo $font?>">
										<?php
										echo $tahun.' tahun '.$bulan.' bulan '.$hari.' hari';
										?></font></td>
										<td>
										<?php 
										$pnd=$this->Transaksi_model->hari_pending($dt->id)->row();
										if(count($pnd)>0)
										{
											$pend=$pnd->totalp;
										}
										else
										{
											$pend=0;
										}
										$tanggal  = strtotime($dt->awal_riset);
										$sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
										$total   = $sekarang - $tanggal;
										$total_hari=($total/(3600*24))-$pend;
										if($total_hari>365)
										{
											$font="red";
										}
										else
										{
											$font="";
										}
										$tahun=floor($total_hari/365);
										$sisa=$total_hari-($tahun* 365);
										$bulan=floor($sisa/(30));
										$sisa=$sisa-($bulan*30);
										$hari=$sisa-1;
										//echo ($total/3600/24).'-'.$pnd->totalp;
										echo '<font color="'.$font.'">'.$tahun.' Tahun '.$bulan.' Bulan '.$hari.' Hari';?></td>
										<td><u>
										<a onclick="get_dtl(<?php echo $dt->id?>)"><?php 
										$ak=$this->Transaksi_model->awal_konsep($dt->id)->row();
										echo date('d-m-Y',strtotime($ak->awal_riset));
										$konsep_awal=$ak->awal_riset;
										$selisih=(strtotime($akhir)-strtotime($konsep_awal))/3600/24;
										if($selisih>365)
										{
											$font="red";
										}
										else
										{
											$font="";
										}
										$tahun=floor($selisih/365);
										$sisa=$selisih-($tahun*365);
										$bulan=floor($sisa/30);
										$hari_ini = date("Y-m-d");
										$tgl_awal=date('d',strtotime($dt->awal_riset));
										$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
										$tgl_terakhir = date('Y-m-d', strtotime($akhir));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										$hari=$sisa-($bulan*30)-1;
										?></a></u>
										</td>
										
										<td><font color="<?php echo $font?>">
										<?php
										echo $tahun.' tahun '.$bulan.' bulan '.$hari.' hari';
										?></font></td>
										<?php
										if($dt->tgl_panelis!='')
										{
											$tgl_panelis=date('d-m-Y',strtotime($dt->tgl_panelis));
										}
										else
										{
											$tgl_panelis='';
										}
										if($dt->tgl_real!='')
										{
											$tgl_real=date('d-m-Y',strtotime($dt->tgl_real));
										}
										else
										{
											$tgl_real='';
										}
										?>
										<td><a href="tabel_waktu_dtl2/<?php echo $dt->id?>"><u><?php echo $tgl_panelis;
										$selisih=(strtotime($akhir)-strtotime($tgl_panelis))/3600/24;
										if($selisih>365)
										{
											$font="red";
										}
										else
										{
											$font="";
										}
										$tahun=floor($selisih/365);
										$sisa=$selisih-($tahun*365);
										$bulan=floor($sisa/30);
										$tgl_awal=date('d',strtotime($tgl_panelis));
										$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
										$tgl_terakhir = date('Y-m-d', strtotime($akhir));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										$hari=$sisa-($bulan*30)-1;
										?></u></a></td>
										<td><?php 
										if($dt->tgl_panelis!='')
										{
											echo $tahun.' tahun '.$bulan.' bulan '.$hari.' hari';
										}
										;?></td>
										<td><a href="tabel_waktu_dtl2/<?php echo $dt->id?>"><u><?php echo $tgl_real;
										$hari_ini = date("Y-m-d");
										$selisih=(strtotime($akhir)-strtotime($tgl_real))/3600/24;
										if($selisih>365)
										{
											$font="red";
										}
										else
										{
											$font="";
										}
										$tahun=floor($selisih/365);
										$sisa=$selisih-($tahun*365);
										$bulan=floor($sisa/30);
										$hari_ini = date("Y-m-d");
										$tgl_awal=date('d',strtotime($tgl_real));
										$tgl_pertama = date('Y-m-'.$tgl_awal, strtotime($akhir));
										$tgl_terakhir = date('Y-m-d', strtotime($akhir));
										$hari=date('d',strtotime($tgl_terakhir)-strtotime($tgl_pertama));
										$hari=$hari-1;
										$hari=$sisa-($bulan*30)-1;
										?></u></a></td>
										<td><?php 
										if($dt->tgl_real!='')
										{
											echo $tahun.' tahun '.$bulan.' bulan '.$hari.' hari';
										}?></td>
										<td><?php
										
										echo $status;?></td>
										<td><?php echo date("d-m-Y",strtotime($akhir));?></td>
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