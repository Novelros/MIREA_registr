<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
    
    /**
     * Экспорт записей на консультацию в CSV
     */
    public function exportConsultationRegistrations($id)
    {
        $consultation = Consultation::with('registrations')->findOrFail($id);
        
        $fileName = 'consultation_' . $consultation->id . '_participants_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        return new StreamedResponse(function() use ($consultation) {
            $handle = fopen('php://output', 'w');
            
            // Добавляем BOM для корректного отображения
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Заголовки CSV
            $headers = [
                'ID записи',
                'Имя',
                'Фамилия',
                'Email',
                'Телефон',
                'Дата записи',
                'ID консультации',
                'Консультация',
                'Тип',
                'Формат',
                'Дата и время консультации',
                'Максимум мест',
                'Записано'
            ];
            
            fputcsv($handle, $headers, ';');
            
            // Данные
            foreach ($consultation->registrations as $registration) {
                $row = [
                    $registration->id,
                    $registration->first_name,
                    $registration->last_name,
                    $registration->email,
                    $registration->phone,
                    $registration->created_at->format('d.m.Y H:i'),
                    $consultation->id,
                    $consultation->title,
                    $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая',
                    $consultation->format == 'online' ? 'Онлайн' : 'Очно',
                    $consultation->start_time->format('d.m.Y H:i'),
                    $consultation->max_slots,
                    $consultation->registrations_count
                ];
                
                fputcsv($handle, $row, ';');
            }
            
            fclose($handle);
        }, 200, $headers);
    }
    
    /**
     * Альтернативный упрощенный метод для экспорта
     */
    public function exportConsultationSimple($id)
    {
        $consultation = Consultation::with('registrations')->findOrFail($id);
        
        $csvData = [];
        
        // Заголовки
        $csvData[] = ['ID', 'Имя', 'Фамилия', 'Email', 'Телефон', 'Дата записи'];
        
        // Данные
        foreach ($consultation->registrations as $registration) {
            $csvData[] = [
                $registration->id,
                $registration->first_name,
                $registration->last_name,
                $registration->email,
                $registration->phone,
                $registration->created_at->format('d.m.Y H:i')
            ];
        }
        
        $fileName = 'consultation_' . $consultation->id . '_participants_' . date('Y-m-d') . '.csv';
        
        return response()->streamDownload(function() use ($csvData) {
            $handle = fopen('php://output', 'w');
            
            fwrite($handle, "\xEF\xBB\xBF");
            
            foreach ($csvData as $row) {
                fputcsv($handle, $row, ';');
            }
            
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }
}