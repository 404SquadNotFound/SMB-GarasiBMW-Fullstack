<?php

use Illuminate\Support\Facades\Route;

// Route Login
Route::get('/', function () {
    return view('login');
})->name('login');

// Route Master Data -> Pelanggan
Route::get('/pelanggan', function () {
    return view('pages.pelanggan.pelanggan');
})->name('pelanggan.index');
Route::get('/pelanggan/tambah', function () {
    return view('pages.pelanggan.tambahPelanggan');
})->name('pelanggan.create');
Route::get('/pelanggan/detail/{id}', function ($id) {
    return view('pages.pelanggan.detailPelanggan');
})->name('pelanggan.show');
Route::get('/pelanggan/edit/{id}', function ($id) {
    return view('pages.pelanggan.editPelanggan');
})->name('pelanggan.edit');
Route::get('/pelanggan/delete/{id}', function ($id) {
    return view('pages.pelanggan.pelanggan');
})->name('pelanggan.delete');

//Route Master Data -> Jenis Mesin
Route::get('/jenis-mesin', function () {
    return view('pages.master_data.jenis_mesin.jenisMesin');
})->name('jenis-mesin.index');
Route::get('/jenis-mesin/tambah', function () {
    return view('pages.master_data.jenis_mesin.tambahJenisMesin');
})->name('jenis-mesin.create');
Route::get('/master-data/jenis-mesin/detail/{id}', function ($id) {
    return view('pages.master_data.jenis_mesin.detailJenisMesin', compact('id'));
})->name('jenis-mesin.show');
Route::get('/master-data/jenis-mesin/edit/{id}', function ($id) {
    return view('pages.master_data.jenis_mesin.editJenisMesin');
})->name('jenis-mesin.edit');
Route::get('/master-data/jenis-mesin/delete/{id}', function ($id) {
    return view('pages.master_data.jenis_mesin.jenisMesin');
})->name('jenis-mesin.delete');

// Route Master Data -> Jenis Mobil
Route::get('/jenis-mobil', function () {
    return view('pages.master_data.jenis_mobil.jenisMobil');
})->name('jenis-mobil.index');
Route::get('/jenis-mobil/tambah', function () {
    return view('pages.master_data.jenis_mobil.tambahJenisMobil');
})->name('jenis-mobil.create');
Route::get('/jenis-mobil/detail/{id}', function ($id) {
    return view('pages.master_data.jenis_mobil.detailJenisMobil');
})->name('jenis-mobil.show');
Route::get('/jenis-mobil/edit/{id}', function ($id) {
    return view('pages.master_data.jenis_mobil.editJenisMobil');
})->name('jenis-mobil.edit');
Route::get('/jenis-mobil/delete/{id}', function ($id) {
    return view('pages.master_data.jenis_mobil.jenisMobil');
})->name('jenis-mobil.delete');

//Route Master Data -> Kategori Sparepart
Route::get('/kategori-sparepart', function () {
    return view('pages.master_data.kategori_sparepart.kategoriSparepart');
})->name('kategori-sparepart.index');
Route::get('/kategori-sparepart/tambah', function () {
    return view('pages.master_data.kategori_sparepart.tambahKategoriSparepart');
})->name('kategori-sparepart.create');
Route::get('/kategori-sparepart/detail/{id}', function ($id) {
    return view('pages.master_data.kategori_sparepart.detailKategoriSparepart');
})->name('kategori-sparepart.show');
Route::get('/kategori-sparepart/edit/{id}', function ($id) {
    return view('pages.master_data.kategori_sparepart.editKategoriSparepart');
})->name('kategori-sparepart.edit');
Route::get('/kategori-sparepart/delete/{id}', function ($id) {
    return view('pages.master_data.kategori_sparepart.kategoriSparepart');
})->name('kategori-sparepart.delete');

//Route Master Data -> Supplier
Route::get('/supplier', function () {
    return view('pages.master_data.supplier.supplier');
})->name('supplier.index');
Route::get('/supplier/tambah', function () {
    return view('pages.master_data.supplier.tambahSupplier');
})->name('supplier.create');
Route::get('/supplier/detail/{id}', function ($id) {
    return view('pages.master_data.supplier.detailSupplier');
})->name('supplier.show');
Route::get('/supplier/edit/{id}', function ($id) {
    return view('pages.master_data.supplier.editSupplier');
})->name('supplier.edit');
Route::get('/supplier/delete/{id}', function ($id) {
    return view('pages.master_data.supplier.supplier');
})->name('supplier.delete');

// Route Kepegawaian -> Data Karyawan
Route::get('/manajemen-pegawai', function () {
    return view('pages.manajemen_pegawai.data_manajemenPegawai');
})->name('manajemen-pegawai.index');
Route::get('/manajemen-pegawai/tambah', function () {
    return view('pages.manajemen_pegawai.tambahdata_manajemenPegawai');
})->name('manajemen-pegawai.create');
Route::get('/manajemen-pegawai/detail/{id}', function ($id) {
    return view('pages.manajemen_pegawai.detaildata_manajemenPegawai');
})->name('manajemen-pegawai.show');
Route::get('/manajemen-pegawai/edit/{id}', function ($id) {
    return view('pages.manajemen_pegawai.editdata_manajemenPagawai');
})->name('manajemen-pegawai.edit');
Route::get('/manajemen-pegawai/delete/{id}', function ($id) {
    return view('pages.manajemen_pegawai.data_manajemenPegawai');
})->name('manajemen-pegawai.delete');

// Route Kepegawaian -> Laporan Absensi
Route::get('/laporan-absensi', function () {
    return view('pages.laporan_absensi.laporanAbsensi');
})->name('laporan-absensi.index');

// Route Kepegawaian -> Pendataan Izin
Route::get('/izin-terlambat', function () {
    return view('pages.izin_keterlambatan.manajemenIzinKeterlambatan');
})->name('izin-terlambat.index');
Route::get('/izin-terlambat/tambah', function () {
    return view('pages.izin_keterlambatan.tambahIzinKeterlambatan');
})->name('izin-terlambat.create');
Route::get('/izin-terlambat/detail/{id}', function ($id) {
    return view('pages.izin_keterlambatan.detailIzinKeterlambatan');
})->name('izin-terlambat.show');
Route::get('/izin-terlambat/edit/{id}', function ($id) {
    return view('pages.izin_keterlambatan.editIzinKeterlambatan');
})->name('izin-terlambat.edit');
Route::get('/izin-terlambat/delete/{id}', function ($id) {
    return view('pages.izin_keterlambatan.manajemenIzinKeterlambatan');
})->name('izin-terlambat.delete');

// Route Kepegawaian -> Penggajian
Route::get('/payroll', function () {
    return view('pages.payroll.payroll');
})->name('payroll.index');
Route::get('/payroll/tambah', function () {
    return view('pages.payroll.tambahPayroll');
})->name('payroll.create');
Route::get('/payroll/detail/{id}', function ($id) {
    return view('pages.payroll.detailPayroll');
})->name('payroll.show');
Route::get('/payroll/edit/{id}', function ($id) {
    return view('pages.payroll.editPayroll');
})->name('payroll.edit');
Route::get('/payroll/delete/{id}', function ($id) {
    return view('pages.payroll.payroll');
})->name('payroll.delete');


// Route Layanan Servis -> Penerimaan Servis
// Route Layanan Servis -> Antrian Pengerjaan 
Route::get('/manajemen-servis', function () {
    return view('pages.manajemen_servis_mobil.manajemenServisMobil');
})->name('manajemen-servis.index');
Route::get('/manajemen-servis/tambah', function () {
    return view('pages.manajemen_servis_mobil.tambahManajemenServisMobil');
})->name('manajemen-servis.create');
Route::get('/manajemen-servis/detail/{id}', function ($id) {
    return view('pages.manajemen_servis_mobil.detailManajemenServisMobil');
})->name('manajemen-servis.show');
Route::get('/manajemen-servis/edit/{id}', function ($id) {
    return view('pages.manajemen_servis_mobil.editManajemenServisMobil');
})->name('manajemen-servis.edit');
Route::get('/manajemen-servis/delete/{id}', function ($id) {
    return view('pages.manajemen_servis_mobil.manajemenServisMobil');
})->name('manajemen-servis.delete');

// Route Layanan Servis -> Riwayat Transaksi
// Masih belum ada

// Route Manajemen Stok -> Data Suku Cadang
Route::get('/suku-cadang', function () {
    return view('pages.suku_cadang.sukuCadang');
})->name('suku-cadang.index');
Route::get('/suku-cadang/tambah', function () {
    return view('pages.suku_cadang.tambahSukuCadang');
})->name('suku-cadang.create');
Route::get('/suku-cadang/detail/{id}', function ($id) {
    return view('pages.suku_cadang.detailSukuCadang');
})->name('suku-cadang.show');
Route::get('/suku-cadang/edit/{id}', function ($id) {
    return view('pages.suku_cadang.editSukuCadang');
})->name('suku-cadang.edit');
Route::get('/suku-cadang/delete/{id}', function ($id) {
    return view('pages.suku_cadang.sukuCadang');
})->name('suku-cadang.delete');
