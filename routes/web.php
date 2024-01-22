<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ConcentrationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FeedBackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::prefix('admin')->middleware(['adminLogin', 'can:isAdmin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/chart-data', [App\Http\Controllers\HomeController::class, 'dataChart'])->name('chart.data');

    Route::prefix('categories')->group(function () {
        Route::get('list', [CategoryController::class, 'list'])->name('category.list');
        Route::get('create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::patch('update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'list'])->name('admin.product.list');
        Route::get('create', [ProductController::class, 'create'])->name('admin.product.create');
        Route::post('store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('detail/{id}', [ProductController::class, 'detail'])->name('admin.product.detail');
        Route::patch('update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::get('delete/{id}', [ProductController::class, 'delete'])->name('admin.product.delete');
    });

    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'list'])->name('brand.list');
        Route::get('/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('store', [BrandController::class, 'store'])->name('brand.store');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::patch('update/{id}', [BrandController::class, 'update'])->name('brand.update');
        Route::get('delete/{id}', [BrandController::class, 'delete'])->name('brand.delete');
    });

    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'list'])->name('banner.list');
        Route::get('/create', [BannerController::class, 'create'])->name('banner.create');
        Route::post('store', [BannerController::class, 'store'])->name('banner.store');
        Route::get('edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
        Route::patch('update/{id}', [BannerController::class, 'update'])->name('banner.update');
        Route::get('delete/{id}', [BannerController::class, 'delete'])->name('banner.delete');
    });

    Route::prefix('sizes')->group(function () {
        Route::get('/', [SizeController::class, 'viewList'])->name('size.index');
        Route::get('list', [SizeController::class, 'list'])->name('size.list');
        Route::post('store', [SizeController::class, 'store'])->name('size.store');
        Route::get('show', [SizeController::class, 'show'])->name('size.show');
        Route::post('update', [SizeController::class, 'update'])->name('size.update');
        Route::post('delete', [SizeController::class, 'delete'])->name('size.delete');
    });

    Route::prefix('concentrations')->group(function () {
        Route::get('/', [ConcentrationController::class, 'viewList'])->name('concentration.index');
        Route::get('list', [ConcentrationController::class, 'list'])->name('concentration.list');
        Route::post('store', [ConcentrationController::class, 'store'])->name('concentration.store');
        Route::get('show', [ConcentrationController::class, 'show'])->name('concentration.show');
        Route::post('update', [ConcentrationController::class, 'update'])->name('concentration.update');
        Route::post('delete', [ConcentrationController::class, 'delete'])->name('concentration.delete');
    });

    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'viewList'])->name('discount.index');
        Route::get('list', [DiscountController::class, 'list'])->name('discount.list');
        Route::post('store', [DiscountController::class, 'store'])->name('discount.store');
        Route::get('show', [DiscountController::class, 'show'])->name('discount.show');
        Route::post('update', [DiscountController::class, 'update'])->name('discount.update');
        Route::post('delete', [DiscountController::class, 'delete'])->name('discount.delete');
    });

    Route::prefix('coupons')->group(function () {
        Route::get('/', [CouponController::class, 'viewList'])->name('coupon.index');
        Route::get('list', [CouponController::class, 'list'])->name('coupon.list');
        Route::post('store', [CouponController::class, 'store'])->name('coupon.store');
        Route::get('show', [CouponController::class, 'show'])->name('coupon.show');
        Route::post('update', [CouponController::class, 'update'])->name('coupon.update');
        Route::post('delete', [CouponController::class, 'delete'])->name('coupon.delete');
        Route::get('sendcoupon', [CouponController::class, 'sendCoupon'])->name('coupon.send');
        // Route::get('mailcoupon', [CouponController::class, 'mailCoupon'])->name('coupon.mail');
    });

    Route::prefix('profile')->group(function () {
        Route::get('edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('update', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('admin.order.list');
        Route::get('/{orderCode}', [OrderController::class, 'detail'])->name('admin.order.detail');
        Route::get('/{orderCode}/approve', [OrderController::class, 'approveOrder'])->name('admin.order.approved');
        Route::get('/{orderCode}/confirm-paid', [OrderController::class, 'confirmPaid'])->name('admin.order.paided');
    });
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'viewList'])->name('article.index');
        Route::get('list', [ArticleController::class, 'list'])->name('article.list');
        Route::post('store', [ArticleController::class, 'store'])->name('article.store');
        Route::get('show', [ArticleController::class, 'show'])->name('article.show');
        Route::post('update', [ArticleController::class, 'update'])->name('article.update');
        Route::post('delete', [ArticleController::class, 'delete'])->name('article.delete');
    });
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'list'])->name('post.list');
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('store', [PostController::class, 'store'])->name('post.store');
        Route::get('edit/{id}', [PostController::class, 'edit'])->name('post.edit');
        Route::put('update/{id}', [PostController::class, 'update'])->name('post.update');
        Route::get('delete/{id}', [PostController::class, 'delete'])->name('post.delete');
    });
    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'list'])->name('page.list');
        Route::get('/create', [PageController::class, 'create'])->name('page.create');
        Route::post('store', [PageController::class, 'store'])->name('page.store');
        Route::get('edit/{id}', [PageController::class, 'edit'])->name('page.edit');
        Route::put('update/{id}', [PageController::class, 'update'])->name('page.update');
        Route::get('delete/{id}', [PageController::class, 'delete'])->name('page.delete');
    });
    Route::prefix('features')->group(function () {
        Route::get('/', [FeatureController::class, 'viewList'])->name('feature.index');
        Route::get('list', [FeatureController::class, 'list'])->name('feature.list');
        Route::post('store', [FeatureController::class, 'store'])->name('feature.store');
        Route::get('show', [FeatureController::class, 'show'])->name('feature.show');
        Route::post('update', [FeatureController::class, 'update'])->name('feature.update');
        Route::post('delete', [FeatureController::class, 'delete'])->name('feature.delete');
    });
    Route::prefix('contacts')->group(function () {
        Route::prefix('feedbacks')->group(function () {
            Route::get('/', [FeedBackController::class, 'viewList'])->name('feedback.index');
            Route::get('list', [FeedBackController::class, 'list'])->name('feedback.list');
            Route::get('show', [FeedBackController::class, 'show'])->name('feedback.show');
            Route::post('update', [FeedBackController::class, 'update'])->name('feedback.update');
            Route::post('delete', [FeedBackController::class, 'delete'])->name('feedback.delete');
        });
        Route::prefix('questions')->group(function () {
            Route::get('/', [QuestionController::class, 'viewList'])->name('question.index');
            Route::get('list', [QuestionController::class, 'list'])->name('question.list');
            Route::post('store', [QuestionController::class, 'store'])->name('question.store');
            Route::get('show', [QuestionController::class, 'show'])->name('question.show');
            Route::post('update', [QuestionController::class, 'update'])->name('question.update');
            Route::post('delete', [QuestionController::class, 'delete'])->name('question.delete');
        });
        Route::prefix('informations')->group(function () {
            Route::get('/', [ContactController::class, 'viewList'])->name('information.index');
            Route::get('list', [ContactController::class, 'list'])->name('information.list');
            Route::post('store', [ContactController::class, 'store'])->name('information.store');
            Route::get('show', [ContactController::class, 'show'])->name('information.show');
            Route::post('update', [ContactController::class, 'update'])->name('information.update');
            Route::post('delete', [ContactController::class, 'delete'])->name('information.delete');
        });
    });
});

Route::get('forget-password', [ForgetPasswordController::class, 'forgetPassword']);
Route::post('send-forget-password', [ForgetPasswordController::class, 'sendForgetPassword'])->name('forgot.send');
Route::get('reset-password/{email}', [ResetPasswordController::class, 'resetPassword']);
Route::post('send-reset-password', [ResetPasswordController::class, 'sendResetPassword'])->name('reset.send');
Route::post('send-change-password', [ChangePasswordController::class, 'sendChangePassword'])->name('changepassword.send');
