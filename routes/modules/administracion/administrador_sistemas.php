<?php
/* AUDITORIAS */

use Illuminate\Support\Facades\Route;

Route::post('admin/sessionAudita', 'HomeController@sessionAudita');
Route::group(['middleware' => ['auth'], 'middleware' => ['role:administrator'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('logs', 'Admin\AuditoriaController@logs');
    Route::get('getDatatableRegistroRolesServerSide', 'Admin\AuditoriaController@getDatatableRegistroRolesServerSide');
    Route::get('getDatatableLogsServerSide/{fecha_inicio}/{fecha_fin}/', 'Admin\AuditoriaController@getDatatableLogsServerSide');
    Route::get('logs/modulos', 'Admin\AuditoriaController@logsModulos');
    Route::get('getDatatableLogsModulosServerSide/{fecha_inicio}/{fecha_fin}/', 'Admin\AuditoriaController@getDatatableLogsModulosServerSide');
});

/* AUDITORIAS */

/* PERSONALIZACION */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/impersonate/{user_id}', 'HomeController@impersonate')->name('impersonate');
    Route::get('/impersonate_leave', 'HomeController@impersonate_leave')->name('impersonate_leave');
});


/* PERSONALIZACION */

/*ADMINISTRACION*/

Route::group([
    'middleware' => ['auth'],
    'middleware' => ['role:administrator'],
    'prefix' => 'admin', 'as' => 'admin.'
], function () {
    //-----------Parametros-------------------------------------------------------------------------------------------------------------------
    Route::get('ParametroIndex', 'Admin\ParametroController@index')->name('parametro.create');
    Route::post('SaveParameter', 'Admin\ParametroController@SaveParameter');
    Route::post('ParameterEliminar', 'Admin\ParametroController@ParameterEliminar');
    Route::get('datatable-parameter/', 'Admin\ParametroController@getDatatable');

    //----------Opciones--------------------------------------------------------------------------------------------------------------------
    Route::post('SaveOpcion', 'Admin\MenuController@StoreOpcion');
    Route::post('MenuEliminar', 'Admin\MenuController@MenuEliminar');
    Route::get('datatable-menu/', 'Admin\MenuController@getDatatable');
    Route::get('datatable-option/', 'Admin\MenuController@getDatatableoption');
    //-------------Roles_Opciones-----------------------------------------------------------------------------------------------------------------

    Route::get('MenuCreate', 'Admin\MenuController@index')->name('menu.create');
    Route::post('MenuRoleEliminar', 'Admin\MenuController@MenuRoleEliminar');
    Route::post('/PermissionRole/', 'Admin\MenuController@PermissionRole');
    Route::post('/UpdateRoleP/', 'Admin\RolesController@UpdateRoleP');
    //------------------------------------------------------------------------------------------------------------------------------
    Route::resource('roles', 'Admin\RolesController');
    Route::get('roles/trash/{id}', 'Admin\RolesController@trash')->name('roles.trash');

    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);

    //---------Administrador de Base-------------------------------------------------------------------------------------------------

    Route::get('AdminBaseIndex', 'Admin\AdministradorBaseController@AdminBaseIndex');
    Route::post('AdminBaseStore', 'Admin\AdministradorBaseController@AdminBaseStore');
});
/*ADMINISTRACION*/

/*RESTRICIONES ADMINISTRADOR DE IP Y ASIGNACION DE EQUIPO*/
Route::group(
    [
        'middleware' => ['auth'], //verifica que este logeado
        'middleware' => ['role:administrator'],
        'as' => 'ips.', // url
        'prefix' => 'ips'
    ], // prefijo de como va en el navegador
    function () {
        Route::get('restringir', 'Admin\IpController@restringir');
        Route::get('getDatatableRestringirIpServerSide', 'Admin\IpController@getDatatableRestringirIpServerSide');
        Route::post('eliminarRegistro', 'Admin\IpController@eliminarRegistro');
        Route::post('restringirIp', 'Admin\IpController@restringirIp');

        Route::get('administrar', 'Admin\IpController@administrar');
        Route::get('getDatatableAdministrarIpServerSide', 'Admin\IpController@getDatatableAdministrarIpServerSide');
        Route::post('agregarIp', 'Admin\IpController@agregarIp');
        Route::post('editarIp', 'Admin\IpController@editarIp');
        Route::post('eliminarIp', 'Admin\IpController@eliminarIp');
    }
);
/*RESTRICIONES ADMINISTRADOR DE IP Y ASIGNACION DE EQUIPO*/


/* SEGURIDAD DE ARCHIVOS */
Route::get('/storage/{carpeta}/{archivo}', 'PrivateController@privadoCarpeta');
Route::get('/storage/{carpeta1}/{carpeta2}/{carpeta3}/{archivo}', 'PrivateController@privadoCarpeta_tres_niveles');
Route::get('/storage/{carpeta1}/{carpeta2}/{archivo}', 'PrivateController@privadoCarpeta_dos_niveles');
Route::get('/storage/{archivo}', 'PrivateController@privadoArchivo');
Route::get('/private/{carpeta1}/{carpeta2}/{archivo}', 'PrivateController@privadoCarpeta_dos_niveles');
/* SEGURIDAD DE ARCHIVOS */
