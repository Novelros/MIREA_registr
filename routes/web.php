<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

// Главная страница
Route::get('/', function () {
    return view('welcome');
});

// Перенаправление /home на главную
Route::get('/home', function () {
    return redirect('/consultations');
})->name('home');

// Аутентификация (стандартные маршруты Laravel UI)
Auth::routes();

// Защищенные маршруты (только для аутентифицированных)
Route::middleware(['auth'])->group(function () {
    // Общие маршруты для всех авторизованных пользователей
    Route::get('/consultations', [RegistrationController::class, 'index'])->name('consultations.index');
    Route::get('/registration/{id}', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('/registration', [RegistrationController::class, 'store'])->name('registration.store');
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('my.registrations');
    
    // Простая админка с проверкой роли прямо в контроллере
    Route::prefix('admin')->group(function () {
        Route::get('/', function () {
            // Проверяем роль админа
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен. Требуются права администратора.');
            }
            
            // Простая статистика
            $stats = [
                'consultations' => \App\Models\Consultation::count(),
                'registrations' => \App\Models\Registration::count(),
                'users' => \App\Models\User::count(),
                'active_consultations' => \App\Models\Consultation::where('is_active', true)->count(),
            ];
            
            $recentRegistrations = \App\Models\Registration::with('consultation')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
                
            $consultations = \App\Models\Consultation::withCount('registrations')
                ->orderBy('start_time', 'desc')
                ->take(5)
                ->get();
            
            return view('admin.simple', compact('stats', 'recentRegistrations', 'consultations'));
        })->name('admin.index');
        
        Route::get('/consultations', function () {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $consultations = \App\Models\Consultation::withCount('registrations')
                ->orderBy('start_time', 'desc')
                ->paginate(15);
            
            return view('admin.consultations_list', compact('consultations'));
        })->name('admin.consultations');
        
        Route::get('/consultation/{id}', function ($id) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $consultation = \App\Models\Consultation::with('registrations')->findOrFail($id);
            return view('admin.consultation_detail', compact('consultation'));
        })->name('admin.consultation.show');
        
        Route::get('/registrations', function () {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $registrations = \App\Models\Registration::with('consultation')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            return view('admin.registrations_list', compact('registrations'));
        })->name('admin.registrations');
        
        // Управление консультациями
        Route::post('/consultation/toggle/{id}', function ($id) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $consultation = \App\Models\Consultation::findOrFail($id);
            $consultation->is_active = !$consultation->is_active;
            $consultation->save();
            
            $status = $consultation->is_active ? 'открыта' : 'закрыта';
            return back()->with('success', "Консультация {$status}");
        })->name('admin.consultation.toggle');
        
        // Удаление записи
        Route::delete('/registration/delete/{id}', function ($id) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $registration = \App\Models\Registration::findOrFail($id);
            $consultation = $registration->consultation;
            
            // Уменьшаем счетчик
            if ($consultation->registered_count > 0) {
                $consultation->decrement('registered_count');
            }
            
            $registration->delete();
            
            return back()->with('success', 'Запись удалена');
        })->name('admin.registration.delete');
        
        // Просмотр пользователей
        Route::get('/users', function () {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $users = \App\Models\User::orderBy('created_at', 'desc')->paginate(20);
            return view('admin.users_list', compact('users'));
        })->name('admin.users');
        
        // Создание консультации
        Route::get('/consultations/create', function () {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            return view('admin.consultation_create');
        })->name('admin.consultations.create');
        
        Route::post('/consultations', function (\Illuminate\Http\Request $request) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:individual,group',
                'format' => 'required|in:online,offline',
                'start_time' => 'required|date',
                'end_time' => 'nullable|date|after:start_time',
                'max_slots' => 'required|integer|min:1',
                'is_active' => 'boolean',
            ]);
            
            \App\Models\Consultation::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'format' => $request->format,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_slots' => $request->max_slots,
                'is_active' => $request->boolean('is_active'),
            ]);
            
            return redirect()->route('admin.consultations')->with('success', 'Консультация успешно создана!');
        })->name('admin.consultations.store');
        
        // Редактирование консультации
        Route::get('/consultation/edit/{id}', function ($id) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $consultation = \App\Models\Consultation::findOrFail($id);
            return view('admin.consultation_edit', compact('consultation'));
        })->name('admin.consultation.edit');
        
        Route::post('/consultation/update/{id}', function (\Illuminate\Http\Request $request, $id) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:individual,group',
                'format' => 'required|in:online,offline',
                'start_time' => 'required|date',
                'end_time' => 'nullable|date|after:start_time',
                'max_slots' => 'required|integer|min:1',
                'is_active' => 'boolean',
            ]);
            
            $consultation = \App\Models\Consultation::findOrFail($id);
            $consultation->update([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'format' => $request->format,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_slots' => $request->max_slots,
                'is_active' => $request->boolean('is_active'),
            ]);
            
            return redirect()->route('admin.consultations')->with('success', 'Консультация успешно обновлена!');
        })->name('admin.consultation.update');
        
        // Удаление консультации
        Route::delete('/consultation/delete/{id}', function ($id) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect('/consultations')->with('error', 'Доступ запрещен.');
            }
            
            $consultation = \App\Models\Consultation::findOrFail($id);
            
            // Проверяем, есть ли записи на эту консультацию
            if ($consultation->registrations()->count() > 0) {
                return back()->with('error', 'Нельзя удалить консультацию, на которую есть записи. Сначала удалите все записи.');
            }
            
            $consultation->delete();
            
            return redirect()->route('admin.consultations')->with('success', 'Консультация успешно удалена!');
        })->name('admin.consultation.delete');
    });
});

// API (публичное) - по заданию должно быть доступно внешним сервисам
Route::get('/api/consultations', [RegistrationController::class, 'apiIndex']);
Route::post('/api/register', [RegistrationController::class, 'apiStore']);

// Тестовый маршрут для проверки работы
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API работает',
        'data' => [
            'app' => 'РТУ МИРЭА Консультации',
            'version' => '1.0',
            'timestamp' => now()
        ]
    ]);
});