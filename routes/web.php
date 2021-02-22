<?php

use App\Models\Task;
use Illuminate\Support\Facades\Route;
use \App\Events\TaskCreated;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', function () {
    return Task::latest()->pluck('body');
});

Route::post('/tasks', function () {
    $task = Task::create([
        'body' => request()->body,
    ]);

//    event((new TaskCreated($task))->dontBroadcastToCurrentUser());
//    broadcast((new TaskCreated($task)))->toOthers();
    event(new TaskCreated($task));
});

