<?php

use App\Http\Controllers\Api\V1\Auth\CustomerAuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api\V1'], function () {

    //currency
    Route::get('currency', [CustomerController::class, 'getCurrency']);
    // fcm
    Route::get('send-fcm', [CustomerController::class, 'sendFCM']);

    
    /// PRODUCTS
       Route::group(['prefix' => 'products'], function () {
        /**
         * 'popular' is the endpoint url
         * 'get_popular_products' is the method definded in ProductController
         * 
         */
        // i.e. http://127.0.0.1:8000/api/v1/products/popular
        Route::get('popular', [ProductController::class, 'get_popular_products']);
        
        Route::get('recommended', 'ProductController@get_recommended_products');
        Route::get('test', 'ProductController@test_get_recommended_products');

        /**
         * 'drinks' is the endpoint url
         * 'get_drinks' is the method definded in ProductController
         * 
         */
        // i.e. http://127.0.0.1:8000/api/v2/products/drinks
        Route::get('drinks', [ProductController::class, 'get_drinks']);
        }); 

        /// REGISTRATION AND LOGIN
        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            /**
             * '/register' is the endpoint url
             * 'register' is the method definded in CustomerAuthController
             * i.e. http://127.0.0.1:8000/api/v1/auth/register
             */
            Route::post('/register', [CustomerAuthController::class,'register']);
            
            /**
             * 'login' is the endpoint url
             * 'login' is the method definded in CustomerAuthController
             * i.e http://127.0.0.1:8000/api/v1/auth/login
             */
            Route::post('/login', [CustomerAuthController::class,'login']);
        });
   
        
        // CUSTOMER INFOR,... GUARDED BY A MIDDLEWARE
        ///  'middleware' => 'auth:api' means that only logged in users can access these apis
        /// to access these apis in postman, one must provide a token
        Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
            Route::get('notifications', 'NotificationController@get_notifications');
           
            /**
             * '/info' is the endpoint url
             * 'info' is the method definded in CustomerController
             * i.e http://127.0.0.1:8000/api/v1/customer/info
             */
            Route::get('/info', [CustomerController::class, 'info']);
            
            Route::post('update-profile', 'CustomerController@update_profile');
            Route::post('update-interest', 'CustomerController@update_interest');
            Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');
            Route::get('suggested-foods', 'CustomerController@get_suggested_food');

        Route::group(['prefix' => 'address'], function () {
            Route::get('list', 'CustomerController@address_list');
            Route::post('add', 'CustomerController@add_new_address');
            Route::put('update/{id}', 'CustomerController@update_address');
            Route::delete('delete', 'CustomerController@delete_address');
        });
                Route::group(['prefix' => 'order'], function () {
            Route::get('list', 'OrderController@get_order_list');
            Route::get('running-orders', 'OrderController@get_running_orders');
            Route::get('details', 'OrderController@get_order_details');
            Route::post('place', 'OrderController@place_order');
            Route::put('cancel', 'OrderController@cancel_order');
            Route::put('refund-request', 'OrderController@refund_request');
            Route::get('track', 'OrderController@track_order');
            Route::put('payment-method', 'OrderController@update_payment_method');
        });
            });
            
        Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
        Route::get('/get-zone-id', 'ConfigController@get_zone');
        Route::get('place-api-autocomplete', 'ConfigController@place_api_autocomplete');
        Route::get('distance-api', 'ConfigController@distance_api');
        Route::get('place-api-details', 'ConfigController@place_api_details');
        Route::get('geocode-api', 'ConfigController@geocode_api');
    });
});
