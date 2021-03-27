<?php

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
// route to login and logout

Route::prefix('user')->group(function() {
     //Route::get('/upload_anss', 'studentcontroller@upload_quesss')->name('student-saveanswer');
     Route::get('login', 'Auth\StudentLoginController@showLoginForm')->name('student-form');
     Route::post('login', 'Auth\StudentLoginController@attemptlogin')->name('student-login');
     Route::post('logout', 'Auth\StudentLoginController@logout')->name('student-logout');
     Route::get('/dashboard', 'studentcontroller@index')->name('student-dashboard');
     Route::get('/', 'HomeController@indexa');
     Route::get('/profile', 'studentcontroller@index')->name('student-profile');
     Route::POST('/task_status_update', 'studentcontroller@task_status_update')->name('student-task_status_update');
     Route::POST('/task_progress_update', 'studentcontroller@task_progress_update')->name('student-task_progress_update');
  });

  Route::prefix('/admin')->group(function() {
      Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin-form');
      Route::post('login', 'Auth\AdminLoginController@attemptlogin')->name('admin-login');
      Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin-logout');
      Route::get('/dashboard', 'AdminController@index')->name('admin-dashboard');
      Route::get('/profile', 'AdminController@profile')->name('admin-profile');
      Route::get('/', 'HomeController@indexb');

      //-----------------------------------------Task Board------------------------------------
      Route::get('/task_board', 'AdminTaskController@task_board')->name('admin-task_board');
      Route::POST('/add_task', 'AdminTaskController@add_task')->name('admin-add_task');
      Route::POST('/ckeditor_upload', 'AdminTaskController@ckeditor_upload')->name('admin-ckeditor_upload');
     /* Route::POST('/upload_to_task', 'AdminTaskController@upload_to_task')->name('admin-upload_to_task');*/
      Route::POST('/edit_task', 'AdminTaskController@edit_task')->name('admin-edit_task');
      Route::POST('/delete_task', 'AdminTaskController@delete_task')->name('admin-delete_task');

  
  });
