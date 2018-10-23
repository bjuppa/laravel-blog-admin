<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Bjuppa\LaravelBlogAdmin\Http\Controllers')->name('blog-admin.')->group(function () {
    Route::get('/blogs/{id}', 'BlogController@show')->name('blogs.show');
    Route::get('/blog-entries/{id}/edit', 'EntryController@edit')->name('entries.edit');
});
