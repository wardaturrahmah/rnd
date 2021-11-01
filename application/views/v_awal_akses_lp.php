<html lang="en">
	<head>
		<title>Akses Line Produk</title>
		
	</head>
	<body class="nav-md">            
		<div class="right_col" role="main">
			<div class="page-title">
				<div class="title_left">
					<h3>
						Akses Line Produk
					</h3>

				</div>
		
			</div>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>List Formula</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						
							<div class="x_content">
								<br />
								<?php
								if(count($list)>0)
								{
								?>
								<table id="datatable" class="table table-striped table-bordered">
									<thead>
										<th>Line Produk</th>
										<th>Action</th>
									</thead>
									<?php 
									foreach($list as $list)
									{
									?>
									<tr>
										<td><?php echo $list->lineproduk; ?></td>
										<td><a href="<?php echo base_url('mkt/akses_lp').'/'.$list->id_lp;?>">
										<button type="button" class="btn"><i class="fa fa-cog blue"
										style="color:#000;"></i></button></a></td>
									</tr>
									<?php 
									}
									?>
								</table>
								<?php
								}
								?>
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