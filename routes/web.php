<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BsuController;
use App\Http\Controllers\CategorySampahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\KategoriSampahController;
use App\Http\Controllers\Frontend\PenukaranPoinFeController;
use App\Http\Controllers\Frontend\PeringkatController;
use App\Http\Controllers\Frontend\SetorSampahController;
use App\Http\Controllers\Frontend\TransactionFrontendController;
use App\Http\Controllers\Frontend\WitdhrawFeController;

use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\PenukaranPoinController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RewardsController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\SampahController;

use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\WebsiteSettingController;
use App\Http\Controllers\WithdrawController;

Route::middleware(['auth', 'verified', 'role:nasabah'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/rewards', [HomeController::class, 'listRewards'])->name('listRewards');
    Route::get('/rewards/detail/{id}', [PenukaranPoinFeController::class, 'detailReward'])->name('detailReward');
    Route::get('/rewards/waiting/{id}', [PenukaranPoinFeController::class, 'waitingReward'])->name('waitingReward');
    Route::post('/rewards', [PenukaranPoinFeController::class, 'rewardStore'])->name('rewardStore');

    Route::get('/blog', [HomeController::class, 'listBlog'])->name('listBlog');
    Route::get('/blog/{slug}', [HomeController::class, 'detailBlog'])->name('detailBlog');

    Route::get('/transaksi', [TransactionFrontendController::class, 'index'])->name('transaksiFrontend.index');
    Route::get('/transaksi/filter', [TransactionFrontendController::class, 'filter'])->name('transaksiFrontend.filter');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  
    Route::get('/setor-sampah', [TransactionFrontendController::class, 'setorSampah'])->name('setor-sampah');
    Route::post('/setor-sampah/store', [TransactionFrontendController::class, 'store']);
    Route::get('/setor-sampah/waiting/{id}', [TransactionFrontendController::class, 'waiting'])->name('setor-sampah.waiting');

    Route::get('/transaksi/tarik-tunai/{id}', [WitdhrawFeController::class, 'withdrawDetail'])->name('transaction.withdraw');
    Route::get('/transaksi/setor-sampah/{id}', [TransactionFrontendController::class, 'transactionDetails'])->name('transaction-details');
    Route::get('/transaksi/tukar-points/{id}', [TransactionFrontendController::class, 'tukarPoints'])->name('transaction.exchange');

    Route::get('/list-sampah', [KategoriSampahController::class, 'listSampah'])->name('sampahlist');
    Route::get('/detail/{kategori}', [KategoriSampahController::class, 'detailKategori'])->name('detailKategori');

    Route::get('/tarik-tunai', [WitdhrawFeController::class, 'withdraw'])->name('tarik-tunai');
    Route::post('/tarik-tunai', [WitdhrawFeController::class, 'withdrawStore']);
    Route::get('/withdraw/waiting/{id}', [WitdhrawFeController::class, 'waitingWithdraw'])->name('waiting-withdraw');

    Route::get('/leaderboard', [PeringkatController::class, 'index'])->name('leaderboard');
});



Route::prefix('admin')->middleware(['auth', 'verified', 'role:super admin|bsu'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'total'])->name('dashboard');
    Route::get('/get-nasabah-data', [DashboardController::class, 'getNasabahData'])->name('nasabah.data');


    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    Route::get('/bsu', [BsuController::class, 'index'])->name('bsu.index');
    Route::get('/bsu/create', [BsuController::class, 'create'])->name('bsu.create');
    Route::post('/bsu/store', [BsuController::class, 'store'])->name('bsu.store');
    Route::get('/bsu/edit/{id}', [BsuController::class, 'edit'])->name('bsu.edit');
    Route::put('/bsu/update/{id}', [BsuController::class, 'update'])->name('bsu.update');
    Route::delete('/bsu/delete/{id}', [BsuController::class, 'destroy'])->name('bsu.destroy');
    Route::get('/bsu/view/{id}', [BsuController::class, 'show'])->name('bsu.show');

    Route::get('/nasabah', [NasabahController::class, 'index'])->name('nasabah.index');
    Route::get('/nasabah/create', [NasabahController::class, 'create'])->name('nasabah.create');
    Route::post('/nasabah/store', [NasabahController::class, 'store'])->name('nasabah.store');
    Route::get('/nasabah/edit/{id}', [NasabahController::class, 'edit'])->name('nasabah.edit');
    Route::put('/nasabah/update/{id}', [NasabahController::class, 'update'])->name('nasabah.update');
    Route::delete('/nasabah/delete/{id}', [NasabahController::class, 'destroy'])->name('nasabah.destroy');
    Route::get('/nasabah/view/{id}', [NasabahController::class, 'show'])->name('nasabah.show');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware(['permission:create role']);
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware(['permission:update role']);
    Route::put('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware(['permission:delete role']);
    Route::get('/roles/getDataRole', [RoleController::class, 'getDataRole']); //get data for multiple select 

    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
    Route::get('/permission/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::put('/permission/update/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permission/delete/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');

    Route::get('/website-settings', [WebsiteSettingController::class, 'index'])->name('website-settings.index');
    Route::post('/website-settings/store', [WebsiteSettingController::class, 'store'])->name('website-settings.store');

    Route::get('/list-sampah', [SampahController::class, 'index'])->name('sampah.index');
    Route::get('/sampah/create', [SampahController::class, 'create'])->name('sampah.create');
    Route::post('/sampah/store', [SampahController::class, 'store'])->name('sampah.store');
    Route::get('/sampah/edit/{id}', [SampahController::class, 'edit'])->name('sampah.edit');
    Route::put('/sampah/update/{id}', [SampahController::class, 'update'])->name('sampah.update');
    Route::delete('/sampah/delete/{id}', [SampahController::class, 'destroy'])->name('sampah.destroy');
    Route::get('/sampah/{id}', [SampahController::class, 'show'])->name('sampah.show');

    Route::get('/kategori-sampah', [CategorySampahController::class, 'index'])->name('kategori-sampah.index');
    Route::get('/kategori-sampah/create', [CategorySampahController::class, 'create'])->name('kategori-sampah.create');
    Route::post('/kategori-sampah/store', [CategorySampahController::class, 'store'])->name('kategori-sampah.store');
    Route::get('/kategori-sampah/edit/{id}', [CategorySampahController::class, 'edit'])->name('kategori-sampah.edit');
    Route::put('/kategori-sampah/update/{id}', [CategorySampahController::class, 'update'])->name('kategori-sampah.update');
    Route::delete('/kategori-sampah/delete/{id}', [CategorySampahController::class, 'destroy'])->name('kategori-sampah.destroy');
    Route::get('/kategori-sampah/{id}', [CategorySampahController::class, 'show'])->name('kategori-sampah.show');

    Route::get('/transaction', [TransactionsController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/create', [TransactionsController::class, 'create'])->name('transaction.create');
    Route::post('/transaction/store', [TransactionsController::class, 'store'])->name('transaction.store');
    Route::get('/transaction/edit/{id}', [TransactionsController::class, 'edit'])->name('transaction.edit');
    Route::put('/transaction/update/{id}', [TransactionsController::class, 'update'])->name('transaction.update');
    Route::delete('/transaction/delete/{id}', [TransactionsController::class, 'destroy'])->name('transaction.destroy');
    Route::get('/transaction/show/{id}', [TransactionsController::class, 'show'])->name('transaction.show');
    Route::delete('/delete-detail/{id}', [TransactionsController::class, 'deleteTransactionDetail'])->name('transaction.deleteTransactionDetail');

    Route::get('/history-transaction', [TransactionsController::class, 'getTransactionDetail'])->name('history-transaction.index');

    Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo.index');
    Route::get('/saldo/create', [SaldoController::class, 'create'])->name('saldo.create');
    Route::post('/saldo/store', [SaldoController::class, 'store'])->name('saldo.store');
    Route::get('/saldo/edit/{id}', [SaldoController::class, 'edit'])->name('saldo.edit');
    Route::put('/saldo/update/{id}', [SaldoController::class, 'update'])->name('saldo.update');
    Route::delete('/saldo/delete/{id}', [SaldoController::class, 'destroy'])->name('saldo.destroy');
    Route::get('/saldo/show/{id}', [SaldoController::class, 'show'])->name('saldo.show');

    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::get('/withdraw/create', [WithdrawController::class, 'create'])->name('withdraw.create');
    Route::post('/withdraw/store', [WithdrawController::class, 'store'])->name('withdraw.store');
    Route::get('/withdraw/edit/{id}', [WithdrawController::class, 'edit'])->name('withdraw.edit');
    Route::put('/withdraw/update/{id}', [WithdrawController::class, 'approveWithdraw'])->name('withdraw.approveWithdraw');
    Route::delete('/withdraw/delete/{id}', [WithdrawController::class, 'destroy'])->name('withdraw.destroy');
    Route::get('/withdraw/show/{id}', [WithdrawController::class, 'show'])->name('withdraw.show');

    Route::get('/rewards', [RewardsController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/create', [RewardsController::class, 'create'])->name('rewards.create');
    Route::post('/rewards/store', [RewardsController::class, 'store'])->name('rewards.store');
    Route::get('/rewards/edit/{id}', [RewardsController::class, 'edit'])->name('rewards.edit');
    Route::put('/rewards/update/{id}', [RewardsController::class, 'update'])->name('rewards.update');
    Route::delete('/rewards/delete/{id}', [RewardsController::class, 'destroy'])->name('rewards.destroy');
    Route::get('/rewards/show/{id}', [RewardsController::class, 'show'])->name('rewards.show');

    Route::get('/penukaran-points', [PenukaranPoinController::class, 'index'])->name('penukaran-points.index');
    Route::get('/penukaran-points/create', [PenukaranPoinController::class, 'create'])->name('penukaran-points.create');
    Route::post('/penukaran-points/store', [PenukaranPoinController::class, 'store'])->name('penukaran-points.store');
    Route::get('/penukaran-points/edit/{id}', [PenukaranPoinController::class, 'edit'])->name('penukaran-points.edit');
    Route::put('/penukaran-points/update/{id}', [PenukaranPoinController::class, 'update'])->name('penukaran-points.update');
    Route::delete('/penukaran-points/delete/{id}', [PenukaranPoinController::class, 'destroy'])->name('penukaran-points.destroy');
    Route::get('/penukaran-points/show/{id}', [PenukaranPoinController::class, 'show'])->name('penukaran-points.show');

    Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
    Route::get('/article/create', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/article/store', [ArticleController::class, 'store'])->name('article.store');
    Route::get('/article/edit/{id}', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/article/update/{id}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('/article/delete/{id}', [ArticleController::class, 'destroy'])->name('article.destroy');

    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
    Route::get('/laporan-keuangan/report', [LaporanKeuanganController::class, 'getReport'])->name('laporan-keuangan.getReport');
    Route::get('/laporan-keuangan/export', [LaporanKeuanganController::class, 'exportReportToPDF'])->name('laporan-keuangan.exportReport');
    Route::get('/filter-charts', [DashboardController::class,'filterCharts'])->name('filter.charts');
});




require __DIR__ . '/auth.php';
