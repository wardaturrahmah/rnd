<html lang="en">
	<head>
		<title>Produk Kompetitor</title>
			<style type="text/css">
			.alert {
			  padding: 20px;
			  background-color: #f44336;
			  color: white;
			}
			.btn-file {
				position: relative;
				overflow: hidden;
			}
			.btn-file input[type=file] {
				position: absolute;
				top: 0;
				right: 0;
				min-width: 100%;
				min-height: 100%;
				font-size: 100px;
				text-align: right;
				filter: alpha(opacity=0);
				opacity: 0;
				outline: none;
				background: white;
				cursor: inherit;
				display: block;
			}

			#img-upload{
				width: 100px;
			}
		</style>
		<script type="text/javascript">  		
		//fungsi untuk upload foto
		$(document).ready( function() {
    	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		}); 	
	});
		// sampek sini fungsi untuk upload foto
		function check_length(my_text)
		{
			var ket=document.getElementsByName(my_text)[0];
			 maxLen = 98; // max number of characters input
			if (ket.value.length > maxLen) {
			// Alert message if maximum limit is reached. 
			// If required Alert can be removed. 
			var msg = "Teks anda terlalu panjang. Teks akan terpotong";
			alert(msg);
			// Reached the Maximum length so trim the textarea
				ket.value = ket.value.substring(0, maxLen);
			 }
			else{ // Maximum length not reached so update the value of my_text counter
				
			} 
		}
				
		</script>  
	
	</head>
	<body class="nav-md" onload="get_op();">            
		<div class="right_col" role="main">
			<div class="page-title">
				<div class="title_left">
					<h3>Kompetitor</h3>
				</div>
			</div>
			<?php
			if($auth_menu_kompetitor->C==1)
			{
			?>
			<div class="row">	  
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Produk Kompetitor <?php echo $judul;?></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="<?php echo $form?>" enctype="multipart/form-data" method="post">
								<input name="id_produk" type="hidden" class="form-control" value="<?php echo  isset($default['id_produk']) ? $default['id_produk'] : ''; ?>">
								<input name="id" type="hidden" class="form-control" value="<?php echo  isset($default['id']) ? $default['id'] : ''; ?>">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Produk Kompetitor</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input name="produk" type="text" class="form-control" value="<?php echo  isset($default['produk']) ? $default['produk'] : ''; ?>" onKeyUp="check_length('produk')">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Image</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="input-group">
											<span class="input-group-btn">
												<span class="btn btn-default btn-file">Browse<input type="file" id="imgInp" name="foto"></span>
											</span>
											<input type="text" class="form-control" name="nama_foto" value="<?php echo  isset($default['foto']) ? $default['foto'] : ''; ?>" readonly>
										</div>
									</div>
							
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
									<div class="col-md-3 col-sm-3 col-xs-12">
										<img id='img-upload' src="<?php echo  isset($default['foto']) ? base_url('').'uploads/kompetitor/'.$default['foto'] : ''; ?>"/>			
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>
			<?php
			}
			?>
			<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Tabel Produk Kompetitor</h2>
								<ul class="nav navbar-right panel_toolbox">
								  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								  </li>
								</ul>
									<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<?php echo ! empty($table) ? $table : '';
									 echo ! empty($note) ? $note : '';
								?>		   
							</div>
						</div>
					</div>
			</div>
		</div>
		
	</body>
</html>