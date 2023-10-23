<?php
Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::post('login', [
  'as' => 'login',
  'uses' => 'Auth\LoginController_PRO@login'
]);

Route::post('/login-two-factor/{user}', 'Auth\LoginController_PRO@login2FA')->name('login.2fa');
Route::get('/', function () { 
  return redirect('/login');
})->name('administracion');

Route::get('logout', function () { 
 return redirect('/login');
});
// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::group([
  'middleware' => ['auth'], 
  'middleware' => ['role:administrator']], function () {
  Route::get('UserState/{id}', 'Admin\UsersController@userstate')->name('admin.userstate');
  Route::get('restaurar2FA/{id}', 'Admin\UsersController@restaurar2FA')->name('admin.restaurar.2fa');
  Route::get('tokenExpira/{id}', 'Admin\UsersController@tokenExpira')->name('admin.token_expire.2fa');
  Route::get('admin/habilitar_token', 'Admin\UsersController@habilitar_token');
  
});