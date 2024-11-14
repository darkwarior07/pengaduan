<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/localization/{language}',
    [\App\Http\Controllers\LocalizationController::class, 'switch']
)->name('localization.switch');


Route::get('/',[\App\Http\Controllers\BlogController::class, 'home'])->name('blog.home');
Route::get('/post/{slug}',[\App\Http\Controllers\BlogController::class, 'showPostDetail'])->name('blog.post.detail');
Route::get('/categories',[\App\Http\Controllers\BlogController::class, 'showCategories'])
->name('blog.categories');

Route::get('/categories/{slug}',[\App\Http\Controllers\BlogController::class, 'showPostsByCategory'])
->name('blog.posts.category');

Route::get('/tags',[\App\Http\Controllers\BlogController::class, 'showTags'])
->name('blog.tags');

Route::get('/tags/{slug}',[\App\Http\Controllers\BlogController::class, 'showPostByTag'])
->name('blog.posts.tag');

Route::get('/search',[\App\Http\Controllers\BlogController::class, 'searchPosts'])
->name('blog.search');

Auth::routes([
    'register' => false
]);

Route::group(['prefix' => 'dashboard', 'middleware' => ['web', 'auth']], function () {
    //dashboard
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    //categories
    Route::get('/categories/select', [\App\Http\Controllers\CategoryController::class, 'select'])->name('categories.select');
    Route::resource('/categories', \App\Http\Controllers\CategoryController::class);
    //Tag
    Route::resource('/tags', \App\Http\Controllers\TagController::class)->except(['show']);
    Route::get('/tags/select', [\App\Http\Controllers\TagController::class, 'select'])->name('tags.select');
    //Post
    Route::resource('/posts', \App\Http\Controllers\PostController::class);
    //file manager
    Route::group(['prefix' => 'filemanager'], function () {
        Route::get(
            '/index',
        [\App\Http\Controllers\FileManagerController::class,'index'])->name('filemanager.index');
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    //Role
    Route::get('/roles/select', [\App\Http\Controllers\RoleController::class, 'select'])->name('roles.select');
    Route::resource('/roles', \App\Http\Controllers\RoleController::class);
    //User
    Route::resource('/users', \App\Http\Controllers\UserController::class);

});
