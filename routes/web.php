<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'ExpenseClaimController@index')->name('home');

Auth::routes(['register' => false]);

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('expense-claims/active', 'ExpenseClaimController@active')
    ->name('expense-claims.active');
Route::get('expense-claims/completed', 'ExpenseClaimController@completed')
    ->name('expense-claims.completed');
    
Route::resource('expense-claims', 'ExpenseClaimController');
Route::post('expense-claims/{id}/approve', 'ExpenseClaimController@approve')
    ->name('expense-claims.approve');
Route::post('expense-claims/{id}/reject', 'ExpenseClaimController@reject')
    ->name('expense-claims.reject');

Route::get('/{foldername}/{filename}', 'FileController')->where(['filename' => '.*']);