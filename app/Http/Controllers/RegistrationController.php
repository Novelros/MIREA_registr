<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    // Главная страница со списком консультаций
    public function index()
    {
        $consultations = Consultation::where('is_active', true)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();
        
        return view('consultations.index', compact('consultations'));
    }

    // Форма записи
    public function create($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        if (!$consultation->hasAvailableSlots()) {
            return redirect()->route('consultations.index')
                ->with('error', 'На эту консультацию нет свободных мест');
        }
        
        // Получаем данные текущего пользователя если он авторизован
        $userData = [];
        if (Auth::check()) {
            $user = Auth::user();
            // Разделяем имя на части
            $nameParts = explode(' ', $user->name);
            $userData = [
                'first_name' => $nameParts[0] ?? '',
                'last_name' => $nameParts[1] ?? (count($nameParts) > 1 ? $nameParts[1] : ''),
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];
        }
        
        return view('registration.form', compact('consultation', 'userData'));
    }

    // Обработка записи 
    public function store(Request $request)
    {
        // Простая валидация
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
        ]);
        
        // Дополнительная проверка уникальности email на консультацию
        $exists = Registration::where('email', $request->email)
            ->where('consultation_id', $request->consultation_id)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Этот email уже зарегистрирован на данную консультацию')->withInput();
        }

        $consultation = Consultation::findOrFail($request->consultation_id);
        
        if (!$consultation->hasAvailableSlots()) {
            return back()->with('error', 'Нет свободных мест')->withInput();
        }
        
        try {
            // Создаем запись
            $registration = Registration::create([
                'consultation_id' => $request->consultation_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            
            // Обновляем счетчик
            $consultation->increment('registered_count');
            
            return view('registration.success', compact('registration'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при создании записи: ' . $e->getMessage())->withInput();
        }
    }

    // Мои записи (для студентов)
    public function myRegistrations()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        $user = Auth::user();
        $registrations = Registration::where('email', $user->email)
            ->with('consultation')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('registration.my', compact('registrations', 'user'));
    }

    // API: получить все консультации
    public function apiIndex(Request $request)
    {
        $consultations = Consultation::where('is_active', true)
            ->where('start_time', '>', now())
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $consultations
        ]);
    }

    // API: записаться
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consultation_id' => 'required|exists:consultations,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Registration::where('email', $value)
                        ->where('consultation_id', $request->consultation_id)
                        ->exists();
                    if ($exists) {
                        $fail('Этот email уже зарегистрирован на данную консультацию');
                    }
                }
            ],
            'phone' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $consultation = Consultation::findOrFail($request->consultation_id);
        
        if (!$consultation->hasAvailableSlots()) {
            return response()->json([
                'success' => false,
                'message' => 'Нет свободных мест'
            ], 400);
        }
        
        $registration = Registration::create([
            'consultation_id' => $request->consultation_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        $consultation->increment('registered_count');
        
        return response()->json([
            'success' => true,
            'message' => 'Запись успешно создана',
            'data' => $registration
        ], 201);
    }
}