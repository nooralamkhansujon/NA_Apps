<?php

/*
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
|
*/

Route::get('/', function () {
    return view('home');
});

Route::prefix('frontend')->name('frontend.')->group(function(){

    Route::get('/home', 'Frontend\HomeController@index')->name('home');

    //load ajax request
    Route::get('/load_users','Frontend\HomeController@load_users')->name('loadUsers');
    Route::get('/load_posts','Frontend\HomeController@load_posts')->name('loadPosts');
    Route::get('/loadlike','Frontend\HomeController@loadlike')->name('loadlike');
    Route::get('/loadfollowing','Frontend\HomeController@loadfollowing')->name('loadfollowing');
    Route::get('/notification_update','Frontend\HomeController@notification_update')->name('notification_update');

    Route::get('/likes','Frontend\HomeController@likes')->name('likes');
    Route::get('/follow','Frontend\HomeController@following')->name('post.follow');
    Route::get('/notification','Frontend\HomeController@notifications')->name('notification');

    // profile Routes
    Route::get('/profile','Frontend\ProfileController@index')->name('profile');
    Route::post('/profile/store','Frontend\ProfileController@store')->name('profile.store');

    //post routes 
    Route::post('/post/store','Frontend\PostController@store')->name('post.store');
    Route::get('/post/{id}','Frontend\PostController@show')->name('post.show');

    Route::get('/post/{id}/edit','Frontend\PostController@edit')->name('post.edit');
    Route::put('/post/{id}/update','Frontend\PostController@update')->name('post.update');

    Route::get('/post/{id}/delete','Frontend\PostController@destroy')->name('post.delete');
   

   


    

});






Route::post('userlogout','Auth\LoginController@userlogout')->name('userlogout');

Auth::routes();

// Route::get('users/logout','Auth\LoginController@userLogout')->name('user.logout');


// Route::prefix('admin')->group(function(){
// 	Route::get("/login",'Auth\AdminLoginController@showLoginForm')->name('admin.login');
// 	Route::post('/login','Auth\AdminLoginController@login')->name("admin.login.submit");
// 	Route::get('/', 'AdminController@index')->name('admin.dashboard');
// 	Route::get('/logout', 'Auth\AdminLoginController@adminLogout')->name('admin.logout');


// 	//password reset routes 
// 	Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetlinkEmail')->name('admin.password.email');
// 	Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');

// 	Route::post('/password/reset','Auth\AdminResetPasswordController@reset');
// 	Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');

// });


