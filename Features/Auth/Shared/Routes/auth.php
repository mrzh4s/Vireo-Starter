<?php
namespace Features\Auth\Shared\Routes;
use Framework\Http\Router;


// ============== AUTHENTICATION ROUTES ==============
Router::get('/auth/signin', 'LoginController@show', ['guest'])->name('auth.signin');
Router::get('/auth/register', 'RegisterController@show', ['guest'])->name('auth.register');
Router::get('/auth/forgot-password', 'ForgotPasswordController@show', ['guest'])->name('auth.forgot-password');
Router::get('/auth/reset-password', 'ResetPasswordController@show', ['guest'])->name('auth.reset-password');

// ============== AUTHENTICATION API ==============
Router::post('/api/auth/login', 'Features\\Auth\\Login\\LoginController@login', ['guest']);
Router::post('/api/auth/register', 'Features\\Auth\\Register\\RegisterController@register', ['guest']);
Router::post('/api/auth/logout', 'Features\\Auth\\Login\\LoginController@logout', ['auth']);
Router::post('/api/auth/forgot-password', 'Features\\Auth\\ForgotPassword\\ForgotPasswordController@sendResetLink', ['guest']);
Router::post('/api/auth/reset-password', 'Features\\Auth\\ResetPassword\\ResetPasswordController@reset', ['guest']);