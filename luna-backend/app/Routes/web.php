<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect('/up');
  // return ['Laravel' => app()->version()];   
});

require __DIR__.'/auth.php';
