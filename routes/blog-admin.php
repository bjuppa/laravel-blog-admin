<?php

use Illuminate\Support\Facades\Route;

Route::namespace ('Bjuppa\LaravelBlogAdmin\Http\Controllers')->name('blog-admin.')->group(function () {
    Route::get('/blogs/{blog}', 'BlogController@show')->name('blogs.show');
    Route::get('/blogs/{blog}/entries/create', 'BlogEntryController@create')->name('entries.create');
    Route::post('/blog-entries', 'BlogEntryController@store')->name('entries.store');
    Route::get('/blogs/{blog}/entries/{entry}/edit', 'BlogEntryController@edit')->name('entries.edit');
    Route::patch('/blog-entries/{entry}', 'BlogEntryController@update')->name('entries.update');
    Route::delete('/blogs/{blog}/entries/{entry}', 'BlogEntryController@destroy')->name('entries.destroy');
});
