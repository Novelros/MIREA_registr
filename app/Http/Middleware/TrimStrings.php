<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    // Middleware для автоматического обрезания пробелов в строковых полях запроса
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}