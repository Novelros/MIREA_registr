<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ConsultationSeeder extends Seeder
{
    public function run()
    {
        Consultation::create([
            'title' => 'Консультация по программированию',
            'type' => 'group',
            'format' => 'online',
            'start_time' => Carbon::now()->addDays(1)->setTime(10, 0, 0),
            'max_slots' => 3,
        ]);

        Consultation::create([
            'title' => 'Индивидуальная консультация по математике',
            'type' => 'individual',
            'format' => 'offline',
            'start_time' => Carbon::now()->addDays(2)->setTime(14, 0, 0),
            'max_slots' => 1,
        ]);

        Consultation::create([
            'title' => 'Групповая консультация по физике',
            'type' => 'group',
            'format' => 'online',
            'start_time' => Carbon::now()->addDays(3)->setTime(16, 0, 0),
            'max_slots' => 5,
        ]);
    }
}