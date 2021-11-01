<html lang="en">
	<head>
		<title>Laporan Harian Risetman </title>

		
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
							<h2>Laporan Harian Risetman : Laporan yang menunjukkan banyak formula oleh risetman per hari</h2>
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Risetman</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="risetman[]" id="risetman"  multiple="multiple" class="ui fluid dropdown">
										<?php
											
											foreach ($risetman as $risetman) {
												$str_flag="";
												if(in_array($risetman->risetman,$risetmana))
												{	
													$str_flag = "selected";
												}
												else 
												{
													$str_flag="";
												}
												?>
												<option <?php echo $str_flag ?>  value="<?php echo $risetman->risetman ?>">
													<?php echo $risetman->risetman ?>
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
								
								$dt=$this->Transaksi_model->rekap_harian_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
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
									$id=$dt->line.'_'.$dt->id_item.'_'.$tgl.'_'.$tgl.'_'.$dt->risetman_hdr;
								?>
									
										
									<td><a href="../tabel_dtl_risetman/<?php echo $id?>" target="_blank"><u><?php echo $jum;?></u></a></td>
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
								<div style="overflow-x:auto;overflow-y:auto;height:250px;">
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
								
								$dt=$this->Transaksi_model->rekap_kontribusi_harian_risetman3($risetman_selected,date("Y-m-d",strtotime($tgl_awal)),date("Y-m-d",strtotime($tgl_akhir)))->result();
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
			/* $(document).ready(function() {
			$('#risetman').select2();
			$('#item').select2();
			});
$thingToCollapse.collapse({ 'toggle': false }).collapse('hide'); */
$(function() {
				$('#risetman').multiselect({
					includeSelectAllOption: true,
						maxHeight: 150      
				});
			});


		</script>
	
		</body>
</html>