<html lang="en">
	<head>
		<title>Tabel</title>
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
										<button type="submit" class="btn btn-danger">BACK</button>
										
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
							$num=$this->Transaksi_model->tabel_kode($items,$ke)->num_rows();

							if($num>0)
							{
							?>
							<table class="table table-bordered">	
							<thead>
							<th>Panelis</th>
							<th>Var</th>
							<th>Subvar</th>
							
							
							<?php
							$numm=$this->Transaksi_model->tabel_kode($items,$ke)->num_rows();
							$hd=$this->Transaksi_model->tabel_kode($items,$ke)->result();
							
							foreach($hd as $hd)
							{
							?>
							<th><?php echo $hd->kode;?></th>
							<?php
							}?>
							</thead>
							<?php
							$list=$this->Transaksi_model->tabel_dtl($items,$ke,$subvar)->result();
							foreach($list as $list)
							{
							?>
							<tr>
							<td><?php echo $list->panelis;?></td>
							<td><?php echo $list->varr;?></td>
							<td><?php echo $list->subvar;?></td>
							<?php
							$nilai1=0;
							$nilai2=0;
							$hd=$this->Transaksi_model->tabel_kode($items,$ke)->result();
							$k=0;
							foreach($hd as $hd)
							{
								$k++;
								$num=$this->Transaksi_model->tabel_temp3($hd->kode,$list->panelis,$list->subvar,$items,$ke)->num_rows();
								if($num>0)
								{
									$a=$this->Transaksi_model->tabel_temp3($hd->kode,$list->panelis,$list->subvar,$items,$ke)->row();
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
								<td bgcolor="<?php echo $bgcolor;?>"><?php echo $nilai;?></td>
							

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