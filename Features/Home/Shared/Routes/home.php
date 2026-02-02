<?php

use Vireo\Framework\Http\Router;


// ============== HOME ROUTES ==============
Router::get('/dashboard', 'HomeController@index', ['auth'])->name('home');