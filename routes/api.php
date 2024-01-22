<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BrandSController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CloudinaryController;
use App\Http\Controllers\Api\ConcentrationController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\FavoriteProductController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthUserController::class, 'register']);
Route::post('/login', [AuthUserController::class, 'login']);
Route::post('forgot-password', [AuthUserController::class, 'fogetPassword']);
Route::post('reset-password', [AuthUserController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'can:isUser'])->group(function () {
    Route::get('user', function () {
        return auth()->user();
    });

    Route::post('logout', [AuthUserController::class, 'logout']);

    Route::prefix('users')->group(function () {
        Route::get('/profile', [AuthUserController::class, 'profile']);
        Route::patch('/edit-profile', [AuthUserController::class, 'editProfile']);
        Route::put('/change-password', [AuthUserController::class, 'changePassword']);
    });

    Route::prefix('cloudinaries')->group(function () {
        Route::post('/upload', [CloudinaryController::class, 'upload']);
        Route::get('/generate-urls', [CloudinaryController::class, 'generateImageUrls']);
        Route::get('/images-folder', [CloudinaryController::class, 'getImagesAssetsFolder']);
        Route::delete('/delete-images', [CloudinaryController::class, 'deleteAssetsImage']);
    });

    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'createCategory']);
        Route::patch('/{id}', [CategoryController::class, 'updateCategory']);
        Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
    });

    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'list']);
        Route::get('/{id}', [AddressController::class, 'show']);
        Route::post('/', [AddressController::class, 'store']);
        Route::patch('/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);
    });

    Route::prefix('sizes')->group(function () {
        Route::get('/{id}', [SizeController::class, 'show']);
        Route::post('/', [SizeController::class, 'store']);
        Route::patch('/{id}', [SizeController::class, 'update']);
        Route::delete('/{id}', [SizeController::class, 'destroy']);
    });

    Route::prefix('discounts')->group(function () {
        Route::get('/{id}', [DiscountController::class, 'show']);
        Route::post('/', [DiscountController::class, 'store']);
        Route::patch('/{id}', [DiscountController::class, 'update']);
        Route::delete('/{id}', [DiscountController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::patch('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('favorite-products')->group(function () {
        Route::get('/', [FavoriteProductController::class, 'list']);
        Route::put('/add-remove', [FavoriteProductController::class, 'addOrRemoveFavoriteProduct']);
    });

    Route::prefix('carts')->group(function () {
        Route::post('/add-to-cart', [CartController::class, 'addToCart']);
        Route::get('/', [CartController::class, 'list']);
        Route::patch('/{id}', [CartController::class, 'updateCart']);
        Route::delete('/{id}', [CartController::class, 'removeFromCart']);
        Route::delete('/', [CartController::class, 'removeCart']);
    });

    Route::prefix('payment-methods')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index']);
        Route::get('/{id}', [PaymentMethodController::class, 'show']);
        Route::post('/', [PaymentMethodController::class, 'store']);
        Route::patch('/{id}', [PaymentMethodController::class, 'update']);
        Route::delete('/{id}', [PaymentMethodController::class, 'destroy']);
    });
    Route::prefix('concentrations')->group(function () {
        Route::get('/{id}', [ConcentrationController::class, 'show']);
        Route::post('/', [ConcentrationController::class, 'store']);
        Route::patch('/{id}', [ConcentrationController::class, 'update']);
        Route::delete('/{id}', [ConcentrationController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'checkout']);
        Route::get('/', [OrderController::class, 'listOrderOfUser']);
        Route::get('/{orderCode}', [OrderController::class, 'orderDetailUser']);
        Route::post('/{orderCode}/payment', [OrderController::class, 'paymentOrder']);
        Route::post('/product/{productId}', [OrderController::class, 'checkPurchased']);
    });

    Route::prefix('reviews')->group(function () {
        Route::post('/product', [ReviewController::class, 'storeProductReview']);
    });

    Route::prefix('posts')->group(function () {
        Route::post('/comment', [PostController::class, 'comment']);
    });
});

Route::prefix('brands')->group(function () {
    Route::get('/', [BrandSController::class, 'list']);
    Route::get('/{id}', [BrandSController::class, 'show']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/{id}/relate', [ProductController::class, 'relateProduct']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'listCategories']);
    Route::get('/{id}', [CategoryController::class, 'detailCategory']);
});

Route::prefix('images')->group(function () {
    Route::post('/uploadImage', [CloudinaryController::class, 'uploadImage']);
});

Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'list']);
});

Route::prefix('sizes')->group(function () {
    Route::get('/', [SizeController::class, 'list']);
});

Route::prefix('discounts')->group(function () {
    Route::get('/', [DiscountController::class, 'list']);
});

Route::prefix('concentrations')->group(function () {
    Route::get('/', [ConcentrationController::class, 'list']);
});

Route::prefix('coupon')->group(function () {
    Route::get('/{sku}', [CouponController::class, 'show']);
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/{slug}', [ArticleController::class, 'showArticle']);
    Route::get('/{slug}/posts', [ArticleController::class, 'showPost']);
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{slug}', [PostController::class, 'show']);
    Route::get('/search/{keyword}', [PostController::class, 'search']);
});

Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index']);
    Route::get('/{slug}', [PageController::class, 'show']);
});

Route::get('posts-popular', [PostController::class, 'popular']);

Route::prefix('subscribers')->group(function () {
    Route::post('/', [SubscriberController::class, 'subscriber']);
});
Route::prefix('feedbacks')->group(function () {
    Route::post('/', [FeedbackController::class, 'store']);
});
Route::prefix('questions')->group(function () {
    Route::get('/', [QuestionController::class, 'index']);
});
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index']);
});

