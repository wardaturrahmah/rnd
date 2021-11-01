<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Bootstrap -->
		<link href="<?php echo base_url();?>assets/vendors/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="<?php echo base_url();?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- NProgress -->
		<link href="<?php echo base_url();?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
		<!-- iCheck -->
		<link href="<?php echo base_url();?>assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">		
		<!-- bootstrap-progressbar -->
		<link href="<?php echo base_url();?>assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
		<!-- JQVMap -->
		<link href="<?php echo base_url();?>assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
		<!-- bootstrap-daterangepicker -->
		<link href="<?php echo base_url();?>assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
		<!-- bootstrap-datetimepicker -->
		<link href="<?php echo base_url();?>assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
		<!-- bootstrap-wysiwyg -->
		<link href="<?php echo base_url();?>assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
		<!-- Select2 -->
		<link href="<?php echo base_url();?>assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
		<!-- Switchery -->
		<link href="<?php echo base_url();?>assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
		<!-- starrr -->
		<link href="<?php echo base_url();?>assets/vendors/starrr/dist/starrr.css" rel="stylesheet">
		<!-- Datatables -->
		<link href="<?php echo base_url();?>assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">	 
		<!--Excel Datatables-->
		<link href="<?php echo base_url();?>assets/vendors/excel-bootstrap/dist/excel-bootstrap-table-filter-style.css" rel="stylesheet">	 
		<!-- BOOTGRID-->
		<link href="<?php echo base_url();?>assets/vendors/jquery.bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
		<!--Multiselect-->
		<link href="<?php echo base_url();?>assets/vendors/bootstrap-multiselect/dist/css/bootstrap-multiselect.css"  rel="stylesheet">
		
		<!-- Custom Theme Style -->
		<link href="<?php echo base_url();?>assets/build/css/custom.min.css" rel="stylesheet">
		
		
		<!--Highchart-->
		<script src="<?php echo base_url();?>assets/highcharts/code/highcharts.js"></script>
		<script src="<?php echo base_url();?>assets/js/jquery-2.1.1.min.js" type="text/javascript"></script>
		
		<script src="<?php echo base_url();?>assets/vendors/excel-bootstrap/dist/excel-bootstrap-table-filter-bundle.js" type="text/javascript"></script>
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<div class="left_col scroll-view">
						<div class="navbar nav_title" style="border: 0;">
							<a class="site_title"> <span></span></a>
						</div>

						<div class="clearfix"></div>
						<br />	
						<?php
						$this->load->model('Transaksi_model', '', TRUE);
						$auth = $this->Transaksi_model->akses_menu($this->session->userdata('group_menu_seas'))->result();//ganti
						
						foreach ($auth as $aut)
						{
							$at[$aut->id]=$aut->R;
						}
						?>
						
						<!-- sidebar menu -->
						<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
							<div class="menu_section">
								<ul class="nav side-menu">
									<li><a><i class="fa fa-clone"></i> Master <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
										<?php
												if($at[1]==1)
												{
													echo '<li><a href="'.base_url().'lineproduk">Master Line Produk</a></li>';
												}
												if($at[2]==1)
												{
													echo '<li><a href="'.base_url().'kategori">Master Kategori Bahan</a></li>';
												}
												if($at[3]==1)
												{
													echo '<li><a href="'.base_url().'bahan">Master Bahan</a></li>';
												}
												if($at[4]==1)
												{
													echo '<li><a href="'.base_url().'kategori_sarana">Master Kategori Sarana</a></li>';
												}
												if($at[5]==1)
												{
													echo '<li><a href="'.base_url().'sarana">Master Sarana</a></li>';
												}
												if($at[6]==1)
												{
													echo '<li><a href="'.base_url().'risetman">Master Risetman</a></li>';
												}
												if($at[7]==1)
												{
													echo '<li><a href="'.base_url().'panelis">Master Panelis</a></li>';
												}
												if($at[8]==1)
												{
													echo '<li><a href="'.base_url().'masalah">Master  Sumber masalah</a></li>';
												}
										?>
										</ul>
									</li>
									<li><a><i class="fa fa-edit"></i> Transaksi <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<?php
												if($at[9]==1)
												{
													echo '<li><a href="'.base_url().'list_produk">Input data</a></li>';
												}
											?>											
										</ul>
									</li>
									<li><a><i class="fa fa-table"></i> Laporan Marketing<span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<!--<li><a href="<?php echo base_url();?>tabel_avg">Laporan Penilaian Rata2</a></li>-->
											<?php
											
												if($at[19]==1)
												{
													echo '<li><a href="'.base_url().'tabel">Laporan Penilaian ALL (LPA)</a></li>';
												}
												if($at[20]==1)
												{
													echo '<li><a href="'.base_url().'tabel_dtl_produk">Laporan Penilaian Date to Date (LPD)</a></li>';
												}
												if($at[21]==1)
												{
													echo '<li><a href="'.base_url().'tabel/range_panelis">Laporan Range Panelis (LRP)</a></li>';
												}
												if($at[22]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_formula_terbaik">Laporan Formula Terbaik (LFT)</a></li>';
												}
												if($at[23]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_terbaik_nilai">Laporan Nilai Terbaik (LNT)</a></li>';
												}
												if($at[24]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_resume_terbaik">Laporan Resume Formula Terbaik (LRFT)</a></li>';
												}
												if($at[25]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_list_kompetitor">Laporan All Kompetitor (LAK)</a></li>';
												}
												if($at[26]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_produk_bulanan2">Laporan Bulanan Produk (LBP)</a></li>';
												}
												
											
											?>								
										</ul>
									</li>
									<li><a><i class="fa fa-table"></i> Laporan RND<span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
										<?php
										
												
												if($at[27]==1)
												{
													echo '<li><a href="'.base_url().'tabel_penilaian_risetman">Laporan Penilaian Risetman(LPR)</a></li>';
												}
												if($at[28]==1)
												{
													echo '<li><a href="'.base_url().'tabel_waktu2">Laporan Lama Waktu Riset (LWR)</a></li>';
												}
												if($at[29]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_masalah">Laporan Sumber Masalah (LSM)</a></li>';
												}
												if($at[30]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_risetman_bulanan2">Laporan Bulanan Risetman (LBR)</a></li>';
												}
												if($at[31]==1)
												{
													echo '<li><a href="'.base_url().'tabel_kategori">Laporan Kategori Bahan (LKB)</a></li>';
												}
												if($at[32]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_vs">Laporan VS (LV)</a></li>';
												}
												if($at[33]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_vs_kompetitor2">Laporan VS Kompetitor (LVK)</a></li>';
												}
												if($at[34]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_vs_rekap">Laporan VS rekap (LVR)</a></li>';
												}
												if($at[35]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_resume_formula">Laporan Resume Formula (LRF)</a></li>';
												}
												if($at[36]==1)
												{
													echo '<li><a href="'.base_url().'tabel/tabel_aktivitas">Laporan Log Aktivitas (LLA)</a></li>';
												}
											
											
										?>
										</ul>
									</li>
								</ul>
						
								
								
								
							</div>
						</div>
						<!-- /sidebar menu -->
					</div>
				</div>
				<!-- top navigation -->
				<div class="top_nav">
					<div class="nav_menu">
						<nav>
							<div class="nav toggle">
								<a id="menu_toggle"><i class="fa fa-bars"></i></a>
							</div>
							
							<ul class="nav navbar-nav navbar-right">
								<li class="">
									<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<?php 
											echo $this->session->userdata('nama_seas').'-'.$this->session->userdata('realname_seas');//ganti
										?>
										<span class=" fa fa-angle-down"></span>
									</a>
									<ul class="dropdown-menu dropdown-usermenu pull-right">
											<?php
											
												if($at[37]==1)
												{
													echo '<li><a href="'.base_url().'c_user/edit_form">Ganti Password</a></li>';
												}
												if($at[38]==1)
												{
													echo '<li><a href="'.base_url().'c_user">Buat user dan Hak akses</a></li>';
												}
												if($at[40]==1)
												{
													echo '<li><a href="'.base_url().'c_user/add_group">Buat Group dan Hak akses Menu</a></li>';
												}
												if($at[39]==1)
												{
													echo '<li><a href="'.base_url().'mkt/awal_akses">Hak Akses Item</a></li>';
												}
												if($at[39]==1 && $this->session->userdata('group_menu_seas')==1)//ganti
												{
													echo '<li><a href="'.base_url().'mkt/awal_akses_lp">Hak Akses Line Produk</a></li>';
												}
											?>
										
										<li><a href="<?echo base_url();?>/index.php/login/process_logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
									</ul>
								</li>
							<li class="">
								  <a  class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">PT.SIANTAR TOP TBK</a>
								</li>								
							</ul>
						</nav>
					</div>
				</div>
				<!-- /top navigation -->
				<!-- page content -->
				<?php echo $this->load->view($main_view); ?>
				<!-- /page content -->

       
			</div>
		</div>
		<!-- jQuery -->
		<script src="<?php echo base_url();?>assets/vendors/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap -->
		<script src="<?php echo base_url();?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- FastClick -->
		<script src="<?php echo base_url();?>assets/vendors/fastclick/lib/fastclick.js"></script>
		<!-- NProgress -->
		<script src="<?php echo base_url();?>assets/vendors/nprogress/nprogress.js"></script>
		<!-- bootstrap-progressbar -->
		<script src="<?php echo base_url();?>assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
		<!-- iCheck -->
		<script src="<?php echo base_url();?>assets/vendors/iCheck/icheck.min.js"></script>
		<!-- Skycons -->
		<script src="<?php echo base_url();?>assets/vendors/skycons/skycons.js"></script>
		<!-- Flot -->
		<script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.pie.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.time.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.stack.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.resize.js"></script>
		<!-- Flot plugins -->
		<script src="<?php echo base_url();?>assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/flot.curvedlines/curvedLines.js"></script>
		<!-- DateJS -->
		<script src="<?php echo base_url();?>assets/vendors/DateJS/build/date.js"></script>
		<!-- JQVMap -->
		<script src="<?php echo base_url();?>assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
		<!-- bootstrap-daterangepicker -->
		<script src="<?php echo base_url();?>assets/vendors/moment/min/moment.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- bootstrap-datetimepicker -->    
		<script src="<?php echo base_url();?>assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
		<!-- bootstrap-wysiwyg -->
		<script src="<?php echo base_url();?>assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/google-code-prettify/src/prettify.js"></script>
		<!-- jQuery Tags Input -->
		<script src="<?php echo base_url();?>assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
		<!-- Switchery -->
		<script src="<?php echo base_url();?>assets/vendors/switchery/dist/switchery.min.js"></script>
		<!-- Select2 -->
		<script src="<?php echo base_url();?>assets/vendors/select2/dist/js/select2.full.min.js"></script>
		<!-- Parsley -->
		<script src="<?php echo base_url();?>assets/vendors/parsleyjs/dist/parsley.min.js"></script>
		<!-- Autosize -->
		<script src="<?php echo base_url();?>assets/vendors/autosize/dist/autosize.min.js"></script>
		<!-- jQuery autocomplete -->
		<script src="<?php echo base_url();?>assets/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
		<!-- starrr -->
		<script src="<?php echo base_url();?>assets/vendors/starrr/dist/starrr.js"></script>
		<!-- validator -->
		<script src="<?php echo base_url();?>assets/vendors/validator/validator.js"></script>
		<!-- Datatables -->
		<script src="<?php echo base_url();?>assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-fixedheader/js/dataTables.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/jszip/dist/jszip.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/pdfmake/build/pdfmake.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendors/pdfmake/build/vfs_fonts.js"></script>
		<!--Bootgrid-->
		<script src="<?php echo base_url();?>assets/vendors/jquery.bootgrid/jquery.bootgrid.min.js"></script>
		
		<script src="<?php echo base_url();?>assets/vendors/bootstrap-multiselect/dist/js/bootstrap-multiselect.js" type="text/javascript"></script>
		<!-- Custom Theme Scripts -->
		<script src="<?php echo base_url();?>assets/build/js/custom.min.js"></script>
		<script>


/* $("#single_cal2").on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD'));
}); */
$('#myDatepicker').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#myDatepicker2').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#myDatepicker3').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    
    $('#myDatepicker4').datetimepicker({
        format: 'DD-MM-YYYY'
    });

    $('#datetimepicker6').datetimepicker();
    
    $('#datetimepicker7').datetimepicker({
        useCurrent: false
    });
    
    $("#datetimepicker6").on("dp.change", function(e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    
    $("#datetimepicker7").on("dp.change", function(e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
</script>
		</body>
	<!-- footer content -->
	<footer>
		<div class="pull-right">
			Developed By IT 2019
        </div>
		<div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
      
</html>
