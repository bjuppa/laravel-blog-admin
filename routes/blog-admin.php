<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Bjuppa\LaravelBlogAdmin\Http\Controllers')->name('blog-admin.')->group(function () {
    Route::get('/blogs/{id}', 'BlogController@show')->name('blogs.show');
    Route::get('/blogs/{id}/entries/create', 'BlogEntryController@create')->name('entries.create');
    Route::post('/blog-entries', 'BlogEntryController@store')->name('entries.store');
    Route::get('/blogs/{blog}/entries/{id}/edit', 'BlogEntryController@edit')->name('entries.edit');
    Route::patch('/blog-entries/{id}', 'BlogEntryController@update')->name('entries.update');
    Route::delete('/blog-entries/{id}', 'BlogEntryController@destroy')->name('entries.destroy');
});
