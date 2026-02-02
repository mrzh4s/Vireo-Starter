<?php
namespace Features\Auth\Shared\Routes;
use Vireo\Framework\Http\Router;



// ============== AUTHENTICATION PAGE ROUTES ==============
Router::get('/auth/signin', 'Features\\Auth\\Login\\LoginController@show', ['guest'])->name('auth.signin');
Router::get('/auth/signup', 'Features\\Auth\\Register\\RegisterController@show', ['guest'])->name('auth.signup');
Router::get('/auth/reset-password', 'Features\\Auth\\ForgotPassword\\ForgotPasswordController@show', ['guest'])->name('auth.reset-password');
Router::get('/auth/change-password', 'Features\\Auth\\ResetPassword\\ResetPasswordController@show', ['guest'])->name('auth.change-password');

// ============== AUTHENTICATION ACTION ROUTES ==============
Router::post('/login', 'Features\\Auth\\Login\\LoginController@login', ['guest'])->name('auth.login');
Router::post('/register', 'Features\\Auth\\Register\\RegisterController@register', ['guest'])->name('auth.register');
Router::post('/logout', 'Features\\Auth\\Login\\LoginController@logout', ['auth'])->name('auth.logout');
Router::post('/forgot-password', 'Features\\Auth\\ForgotPassword\\ForgotPasswordController@sendResetLink', ['guest'])->name('auth.forgot-password');
Router::post('/reset-password', 'Features\\Auth\\ResetPassword\\ResetPasswordController@reset', ['guest'])->name('auth.reset-password');

// ============== LEGACY API ROUTES (for backwards compatibility) ==============
Router::post('/api/auth/login', 'Features\\Auth\\Login\\LoginController@login', ['guest']);
Router::post('/api/auth/register', 'Features\\Auth\\Register\\RegisterController@register', ['guest']);
Router::post('/api/auth/logout', 'Features\\Auth\\Login\\LoginController@logout', ['auth']);
Router::post('/api/auth/forgot-password', 'Features\\Auth\\ForgotPassword\\ForgotPasswordController@sendResetLink', ['guest']);
Router::post('/api/auth/reset-password', 'Features\\Auth\\ResetPassword\\ResetPasswordController@reset', ['guest']);