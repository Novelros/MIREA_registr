<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        // Простая статистика
        $stats = [
            'consultations' => Consultation::count(),
            'registrations' => Registration::count(),
            'users' => User::count(),
            'active_consultations' => Consultation::where('is_active', true)->count(),
        ];
        
        $recentRegistrations = Registration::with('consultation')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $consultations = Consultation::withCount('registrations')
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.simple', compact('stats', 'recentRegistrations', 'consultations'));
    }
    
    public function consultations()
    {
        $consultations = Consultation::withCount('registrations')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
        
        return view('admin.consultations_list', compact('consultations'));
    }
    
    public function showConsultation($id)
    {
        $consultation = Consultation::with('registrations')->findOrFail($id);
        return view('admin.consultation_detail', compact('consultation'));
    }
    
    public function registrations()
    {
        $registrations = Registration::with('consultation')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.registrations_list', compact('registrations'));
    }
    
    // Форма создания консультации
    public function createConsultation()
    {
        return view('admin.consultation_create');
    }
    
    // Сохранение консультации
    public function storeConsultation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:individual,group',
            'format' => 'required|in:online,offline',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'max_slots' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $consultation = Consultation::create([
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
    }
    
    // Форма редактирования консультации
    public function editConsultation($id)
    {
        $consultation = Consultation::findOrFail($id);
        return view('admin.consultation_edit', compact('consultation'));
    }
    
    // Обновление консультации
    public function updateConsultation(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:individual,group',
            'format' => 'required|in:online,offline',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'max_slots' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $consultation = Consultation::findOrFail($id);
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
    }
    
    // Удаление консультации
    public function deleteConsultation($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        // Проверяем, есть ли записи на эту консультацию
        if ($consultation->registrations()->count() > 0) {
            return back()->with('error', 'Нельзя удалить консультацию, на которую есть записи. Сначала удалите все записи.');
        }
        
        $consultation->delete();
        
        return redirect()->route('admin.consultations')->with('success', 'Консультация успешно удалена!');
    }
    
    // Переключение статуса консультации
    public function toggleConsultation($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->is_active = !$consultation->is_active;
        $consultation->save();
        
        $status = $consultation->is_active ? 'открыта' : 'закрыта';
        return back()->with('success', "Консультация {$status}");
    }
    
    // Удаление записи
    public function deleteRegistration($id)
    {
        $registration = Registration::findOrFail($id);
        $consultation = $registration->consultation;
        
        $registration->delete();
        
        // Обновляем счетчик записей
        if ($consultation->registered_count > 0) {
            $consultation->decrement('registered_count');
        }
        
        return back()->with('success', 'Запись успешно удалена');
    }
    
    // Просмотр пользователей
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users_list', compact('users'));
    }
}