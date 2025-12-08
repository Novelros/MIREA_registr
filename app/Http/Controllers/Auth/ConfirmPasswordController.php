<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    /* 
    * Контроллер подтверждения пароля
    */
    
    use ConfirmsPasswords;
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('auth');
    }
}
