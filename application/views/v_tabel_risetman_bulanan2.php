<html lang="en">
	<head>
		<title>Laporan Risetman Bulanan</title>
		<script>
		function get_awal(event)
		{
			//onchange="get_awal(event)"
			
			var tgl1=new Date(event.target.value);
			
			if(tgl1.getDate()!=1)
			{
				alert("Tanggal Yang dimasukkan harus tanggal 1");
				var first=new Date(tgl1.getFullYear(), tgl1.getMonth(), 1);
				var str1 = first.toString("yyyy-MM-dd");
				document.getElementById("tgl_awal").value =  str1;

			}
		}
		function get_akhir(event)
		{
			
			var tgl2=new Date(event.target.value);
			var last=new Date(tgl2.getFullYear(), tgl2.getMonth() +1, 0);
			var lastdate=last.getDate();
			
			
			if(tgl2.getDate()!=lastdate)
			{
				//alert(lastdate);
				alert("Tanggal Yang dimasukkan harus tanggal "+lastdate);
				var str2 = last.toString("yyyy-MM-dd");
				document.getElementById("tgl_akhir").value =str2;

			}
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
							<h2>Laporan Bulanan Risetman : Laporan yang menunjukkan banyak formula oleh risetman per bulan</h2>
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
											  <input type="date" id="tgl_awal" name="tgl_awal" value="<?php echo  isset($tgl_awal) ? $tgl_awal : ''; ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Akhir</label>
									<div class='col-sm-4'>
										<div class="form-group">
											<input type="date" id="tgl_akhir" name="tgl_akhir" value="<?php echo  isset($tgl_akhir) ? $tgl_akhir : ''; ?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Risetman</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="risetman[]" id="risetman"  multiple="multiple" class="ui fluid dropdown">
										<?php
											
											foreach ($risetman as $risetmans) {
												$str_flag="";
												if(in_array($risetmans->risetman,$risetmana))
												{	
													$str_flag = "selected";
												}
												else 
												{
													$str_flag="";
												}
												?>
												<option <?php echo $str_flag ?>  value="<?php echo $risetmans->risetman ?>">
													<?php echo $risetmans->risetman ?>
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
							if($risetman_selected!='')
							{

							?>
								<h4><b>Formula dibuat</b></h4>
								<div style="overflow-x:auto;overflow-y:auto;height:250px;">
								<table class="table table-bordered">
								<thead>
								<th><center>Risetman</center></th>
								<th><center>Item</center></th>
								<?php
								
								$hdr=$this->Transaksi_model->hdr_bulanan_risetman2($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
								foreach($hdr as $hd)
								{
									$mnt=date("n",strtotime($hd->tgl));
									$yr=date("Y",strtotime($hd->tgl));
									$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
									$tgl=$hd->tgl;
										$tgl2=date("Y-m-t", strtotime($hd->tgl));
										$id=$hd->tgl;
										$form="'#".$id."'";
										echo '<form action="tabel_risetman_mingguan2" method="post" id="'.$id.'" target="_blank">';
										echo ' <input type="hidden" id="tgl_awal" name="tgl_awal" value="'.$hd->tgl.'">';
										echo ' <input type="hidden" id="tgl_akhir" name="tgl_akhir" value="'.$tgl2.'">';
										echo ' <select name="risetman[]" id="risetman"  multiple="multiple" hidden>';
										
										foreach ($risetman as $risetmans) {
										$str_flag="";
										
										if(in_array($risetmans->risetman,$risetmana))
										{	
											$str_flag = "selected";
										}
										else 
										{
											$str_flag="";
										}
										/* if($produks->id==$dt->id_item)
										{
											$str_flag = "selected";
										}
										else 
										{
											$str_flag="";
										}
										 */
										
										echo '<option '.$str_flag.' value="'.$risetmans->risetman.'"></option>';
										}
										
										echo '</select>';
										echo '</form>';
									
									echo '<th><u><a href="javascript:$('.$form.').submit();">'.$bulan[$mnt].' '.$yr.'</a></u></th>';
									
								}
								?>
								</thead>
								<?php
								
								$dt=$this->Transaksi_model->rekap_bulanan_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
								foreach($dt as $dt)
								{
								if(!empty($dt->risetman_hdr))
								{
								?>
								<tr>
									<td><?php echo $dt->risetman_hdr;?></td>
									<td><?php echo $dt->nama_item;?></td>
								<?php
									foreach($hdr as $hd)
									{
										$tgl=$hd->tgl;
										$jum=$dt->$tgl;
										
										if(empty($jum))
										{
											$jum=0;
										}
										echo '<td>'.$jum.'</td>';
									
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
								<div style="overflow-x:auto;overflow-y:auto;height:250px;">
								<table class="table table-bordered">
								<thead>
								<th><center>Risetman</center></th>
								<th><center>Item</center></th>
								<?php
								$hdr2=$this->Transaksi_model->hdr_bulanan_kontribusi_risetman2($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
								foreach($hdr2 as $hd)
								{
									$mnt=date("n",strtotime($hd->tgl));
									$yr=date("Y",strtotime($hd->tgl));
									$bulan = array("","Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
									
									echo '<th>'.$bulan[$mnt].' '.$yr.'</th>';
									
								}
								?>
								</thead>
								<?php
								
								$dt=$this->Transaksi_model->rekap_kontribusi_bulanan_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
								foreach($dt as $dt)
								{
								if(!empty($dt->risetman))
								{
								?>
								<tr>
									<td><?php echo $dt->risetman;?></td>
									<td><?php echo $dt->nama_item;?></td>
								<?php
									foreach($hdr2 as $hd)
									{
										$tgl=$hd->tgl;
										$jum=$dt->$tgl;
										
										if(empty($jum))
										{
											$jum=0;
										}
										echo '<td>'.$jum.'</td>';
									
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
			$(function() {
				$('#risetman').multiselect({
					includeSelectAllOption: true,
						maxHeight: 150      
				});
			});

		</script>
	
		</body>
</html>