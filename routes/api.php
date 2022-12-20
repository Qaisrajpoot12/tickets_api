<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Dashboard\Organiser\EventController;
use App\Http\Controllers\Dashboard\Organiser\TicketController;
use App\Http\Controllers\Dashboard\Organiser\OrganiserUser;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route:: get('/home',[UserController::class,'home'])->name('home');

Route:: post('/signup/attende', [UserController::class, 'SignupAsAttende'])->name('SignupAsAttende');

Route:: post('/signup/orgnizer', [UserController::class, 'SignupAsOrgnizer'])->name('SignupAsOrgnizer')->middleware('EnsureTokenIsValid');

route::group(['middleware'=>'EnsureTokenIsValid'],function(){
    Route:: post('/login', [UserController::class, 'login'])->name('login');
    Route:: post('/check',[UserController::class,'index'])->name('check')->middleware('authCheck');
    Route:: post('/update_user_profile/{id}',[UserController::class,'update_profile'])->name('update_profile')->middleware('authCheck');
    
    
    route::group(['middleware'=>'authCheck'],function(){
        Route:: post('/OrganizerAllEvents',[EventController::class,'OrganizerAllEvents']);
        Route:: post('/eventDetails',[EventController::class,'eventDetails']);
        Route:: post('/upComingEvents',[EventController::class,'upComingEvents']);
        Route:: post('/eventsByLocation',[EventController::class,'findEventsByLocation']);
        Route:: post('/pastEvents',[EventController::class,'pastEvents']);
        Route:: post('/searchEvent',[EventController::class,'searchEvent']);
        Route:: post('/eventsByCategroy',[EventController::class,'searchEventsByCategory']);
        Route:: post('/eventCategories',[EventController::class,'allEventCategories']);
        
        Route:: post('/allTickets',[TicketController::class,'getAllTickets']);
        Route:: post('/ticketDetails',[TicketController::class,'ticketDetails']);
        Route:: post('/searchTicket',[TicketController::class,'searchTicket']);
    });
});


