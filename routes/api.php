<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Api'], function () {

    Route::get('books/export', 'BooksController@export');
    Route::apiResource('books', 'BooksController');
    Route::apiResource('authors', 'AuthorsController');
    Route::post('upload-file', 'UploadController@upload');
    Route::get('uploads/{filename}', 'UploadController@download');
});
