<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
return 'Mi primera aplicación Laravel';
});
Route::get('/hola', function () {
return 'Hola mundo desde Laravel';
});
