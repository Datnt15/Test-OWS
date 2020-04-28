<?php
use Library\Route;


Route::post('user','UserApi','addUser');
Route::put('user','UserApi','editUser');
Route::put('user/lock','UserApi','lockUser');
Route::get('user/detail/{id}','UserApi','getDetailUser');
Route::get('users','UserApi','showListUser');
Route::get('login','UserApi','login');