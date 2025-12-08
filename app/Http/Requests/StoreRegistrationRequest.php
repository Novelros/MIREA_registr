<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'consultation_id' => 'required|exists:consultations,id',
            'first_name' => 'required|string|max:100|regex:/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u',
            'last_name' => 'required|string|max:100|regex:/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u',
            'email' => [
                'required',
                'email',
                Rule::unique('registrations', 'email')->where(function ($query) {
                    return $query->where('consultation_id', $this->consultation_id);
                })
            ],
            // Принимаем и форматированный, и сырой номер
            'phone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Очищаем от всего, кроме цифр
                    $cleanPhone = preg_replace('/[^0-9]/', '', $value);
                    
                    // Проверяем длину (11 цифр для России)
                    if (strlen($cleanPhone) !== 11) {
                        $fail('Номер телефона должен содержать 11 цифр');
                    }
                    
                    // Проверяем код страны (7 или 8)
                    if (!in_array($cleanPhone[0], ['7', '8'])) {
                        $fail('Номер телефона должен начинаться с 7 или 8');
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Этот email уже зарегистрирован на данную консультацию',
            'first_name.regex' => 'Имя должно содержать только буквы, пробелы и дефисы',
            'last_name.regex' => 'Фамилия должна содержать только буквы, пробелы и дефисы',
        ];
    }
    
    // Приводим телефон к единому формату перед валидацией
    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $phone = $this->input('phone');
            // Оставляем только цифры
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            // Если 11 цифр и начинается с 8, меняем на 7
            if (strlen($cleanPhone) === 11 && $cleanPhone[0] === '8') {
                $cleanPhone = '7' . substr($cleanPhone, 1);
            }
            
            // Форматируем в нужный вид
            $formattedPhone = $cleanPhone;
            if (strlen($cleanPhone) === 11) {
                $formattedPhone = '+7(' . substr($cleanPhone, 1, 3) . ')' . 
                                  substr($cleanPhone, 4, 3) . '-' .
                                  substr($cleanPhone, 7, 2) . '-' .
                                  substr($cleanPhone, 9, 2);
            }
            
            $this->merge([
                'phone' => $formattedPhone,
                'phone_raw' => $cleanPhone // сохраняем и сырой вариант
            ]);
        }
    }
}