<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Bjuppa\LaravelBlogAdmin\Http\Controllers')->name('blog-admin.')->group(function () {
    Route::get('/blogs/{id}', 'BlogController@show')->name('blogs.show');
    Route::get('/blogs/{id}/entries/create', 'EntryController@create')->name('entries.create');
    Route::post('/blog-entries', 'EntryController@store')->name('entries.store');
    Route::get('/blog-entries/{id}/edit', 'EntryController@edit')->name('entries.edit');
    Route::put('/blog-entries/{id}', 'EntryController@update')->name('entries.update');
});
