<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['consultation_id', 'first_name', 'last_name', 'email', 'phone'];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    // // Автоматически заполняем email текущего пользователя
    // public static function create(array $attributes = [])
    // {
    //     // Если пользователь авторизован, берем его email
    //     if (Auth::check() && empty($attributes['email'])) {
    //         $attributes['email'] = Auth::user()->email;
    //     }
        
    //     return parent::create($attributes);
    // }
}