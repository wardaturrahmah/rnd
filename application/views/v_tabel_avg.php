<html lang="en">
	<head>
		<title>Tabel</title>
		<script>
		
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
										<input id="item2" type="text"></input>
										<button type="submit" class="btn btn-success">Submit</button>
										
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
							<th><center><a href="<?php echo base_url('tabel_dtl_kode').'/'.$items.'_'.$ke.'_'.$hd->kode.'_'.$hd->tanggal;?>"><u><?php echo $hd->kode.'<br>'.$hd->tanggal;?></u></a></center></th>
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
							<!--<td><a href="<?php //echo base_url('tabel_dtl').'/'.$items.'_'.$ke.'_'.$list->subvar;?>"><u><?php //echo $list->subvar;?></u></a></td>-->
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
								<td bgcolor="<?php echo $bgcolor;?>"><center><?php echo $nilai;?></center></td>
							

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
			
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
			$('#item').select2();
			});

		</script>
	
		</body>
</html>