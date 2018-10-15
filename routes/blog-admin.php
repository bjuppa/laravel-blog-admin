<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Bjuppa\LaravelBlogAdmin\Http\Controllers')->group(function () {
    Route::get('/blogs/{id}', 'BlogController@show')->name('blog-admin.blog.show');
});
