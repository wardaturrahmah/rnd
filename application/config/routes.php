<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login";
$route['list_produk'] = "mkt";
$route['tambah_produk/(:any)'] = "mkt/add_form/$1";
$route['edit_produk/(:any)'] = "mkt/edit_form/$1";
$route['produk_kompetitor/(:any)'] = "mkt/produk_kompetitor/$1";
$route['panelis_kompetitor/(:any)'] = "mkt/panelis_kompetitor/$1";
$route['edit_panelis_kompetitor/(:any)'] = "mkt/edit_panelis_kompetitor/$1";
$route['resume_produk/(:any)'] = "mkt/resume_produk/$1";
$route['tambah_formula/(:any)'] = "mkt/formula_form/$1";
$route['edit_formula/(:any)'] = "mkt/edit_formula_form/$1";
$route['panelis_risetman/(:any)'] = "mkt/panelis_form/$1";
$route['edit_panelis_risetman/(:any)'] = "mkt/edit_panelis_form/$1";
$route['panelis_internal/(:any)'] = "mkt/panelis2_form/$1";
$route['edit_panelis_internal/(:any)'] = "mkt/edit_panelis2_form/$1";
$route['panelis_ts/(:any)'] = "mkt/panelis3_form/$1";
$route['edit_panelis_ts/(:any)'] = "mkt/edit_panelis3_form/$1";
$route['kesimpulan/(:any)'] = "mkt/kesimpulan_form/$1";
$route['edit_kesimpulan/(:any)'] = "mkt/edit_kesimpulan_form/$1";

$route['approve/(:any)'] = "mkt/approve_/$1";
$route['tabel_avg'] = "tabel/tabel_avg";
$route['tabel_dtl'] = "tabel/tabel_dtl";
$route['tabel_dtl_produk'] = "tabel/tabel_dtl_produk";
$route['tabel_dtl_produk2'] = "tabel/tabel_dtl_produk2";
$route['tabel_dtl_risetman/(:any)'] = "tabel/tabel_dtl_risetman/$1";
$route['tabel_penilaian_risetman'] = "tabel/tabel_penilaian_risetman";
$route['tabel_waktu'] = "tabel/tabel_waktu";
$route['tabel_waktu2'] = "tabel/tabel_waktu2";
$route['tabel_progress'] = "tabel/tabel_progress";
$route['tabel_kategori'] = "tabel/tabel_kategori";
$route['tabel_dtl/(:any)'] = 'tabel/tabel_dtl/$1';
$route['tabel_dtl_kode/(:any)'] = 'tabel/tabel_dtl_kode/$1';
$route['tabel_waktu_dtl/(:any)'] = 'tabel/tabel_waktu_dtl/$1';
$route['tabel_waktu_dtl2/(:any)'] = 'tabel/tabel_waktu_dtl2/$1';
$route['print_formula/(:any)'] = 'tabel/print_formula/$1';
$route['print_nilai/(:any)'] = 'tabel/print_nilai/$1';
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */