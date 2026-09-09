<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['login'] = 'welcome/login';
$route['auth'] = 'welcome/login';
$route['register'] = 'welcome/register';
$route['auth/register'] = 'welcome/register';
$route['auth/daftar'] = 'welcome/register';
$route['logout'] = 'welcome/logout';
// [Saran 4] Route verifikasi email
$route['verify-email/(:any)'] = 'welcome/verify_email/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['pemohon-dashboard'] = 'pemohon_dashboard';
$route['pemohon-dashboard/(:any)/(:any)'] = 'pemohon_dashboard/$1/$2';
$route['pemohon-dashboard/(:any)'] = 'pemohon_dashboard/$1';

$route['petugas-dashboard'] = 'petugas_dashboard';
$route['petugas-dashboard/(:any)/(:any)'] = 'petugas_dashboard/$1/$2';
$route['petugas-dashboard/(:any)'] = 'petugas_dashboard/$1';

// Admin Dashboard Routes
$route['surat-tugas'] = 'surat_tugas';
$route['surat-tugas/(:any)/(:any)'] = 'surat_tugas/$1/$2';
$route['surat-tugas/(:any)'] = 'surat_tugas/$1';

$route['kelola-skhp'] = 'kelola_skhp';
$route['kelola-skhp/cetak/(:num)'] = 'kelola_skhp/cetak/$1';
$route['kelola-skhp/get-petugas/(:num)'] = 'kelola_skhp/get_petugas_json/$1';
$route['kelola-skhp/(:any)/(:any)'] = 'kelola_skhp/$1/$2';
$route['kelola-skhp/(:any)'] = 'kelola_skhp/$1';

$route['kelola-pemohon'] = 'kelola_pemohon';
$route['kelola-pemohon/(:any)/(:any)'] = 'kelola_pemohon/$1/$2';
$route['kelola-pemohon/(:any)'] = 'kelola_pemohon/$1';

$route['logout'] = 'welcome/logout';

// Kepala UPT Routes
$route['validasi-skhp'] = 'validasi_skhp';
$route['validasi-skhp/(:any)/(:any)'] = 'validasi_skhp/$1/$2';
$route['validasi-skhp/(:any)'] = 'validasi_skhp/$1';

$route['laporan'] = 'laporan';
$route['laporan/(:any)/(:any)'] = 'laporan/$1/$2';
$route['laporan/(:any)'] = 'laporan/$1';

$route['log-aktivitas'] = 'log_aktivitas';
$route['log-aktivitas/(:any)'] = 'log_aktivitas/$1';

$route['users/new'] = 'users/tambah';
$route['users/simpan'] = 'users/simpan';

