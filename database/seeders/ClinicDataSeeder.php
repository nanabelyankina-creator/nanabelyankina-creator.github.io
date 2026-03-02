<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClinicDataSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = Specialization::all();

        $doctorNames = [
            ['last' => 'Иванов',     'first' => 'Алексей',   'middle' => 'Петрович'],
            ['last' => 'Петров',     'first' => 'Дмитрий',   'middle' => 'Сергеевич'],
            ['last' => 'Сидорова',   'first' => 'Мария',     'middle' => 'Игоревна'],
            ['last' => 'Кузнецов',   'first' => 'Николай',   'middle' => 'Андреевич'],
            ['last' => 'Смирнова',   'first' => 'Елена',     'middle' => 'Владимировна'],
            ['last' => 'Воробьёв',   'first' => 'Игорь',     'middle' => 'Олегович'],
            ['last' => 'Осипова',    'first' => 'Анна',      'middle' => 'Романовна'],
            ['last' => 'Морозов',    'first' => 'Сергей',    'middle' => 'Алексеевич'],
            ['last' => 'Громова',    'first' => 'Ольга',     'middle' => 'Андреевна'],
            ['last' => 'Федорова',   'first' => 'Татьяна',   'middle' => 'Викторовна'],
            ['last' => 'Егоров',     'first' => 'Виктор',    'middle' => 'Николаевич'],
            ['last' => 'Новикова',   'first' => 'Юлия',      'middle' => 'Александровна'],
            ['last' => 'Куликов',    'first' => 'Павел',     'middle' => 'Михайлович'],
            ['last' => 'Соколова',   'first' => 'Ирина',     'middle' => 'Георгиевна'],
            ['last' => 'Никитин',    'first' => 'Роман',     'middle' => 'Денисович'],
            ['last' => 'Беляева',    'first' => 'Светлана',  'middle' => 'Евгеньевна'],
            ['last' => 'Данилов',    'first' => 'Максим',    'middle' => 'Леонидович'],
            ['last' => 'Мельникова', 'first' => 'Надежда',   'middle' => 'Васильевна'],
            ['last' => 'Жуков',      'first' => 'Артур',     'middle' => 'Игоревич'],
            ['last' => 'Павлова',    'first' => 'Алёна',     'middle' => 'Сергеевна'],
            ['last' => 'Трофимов',   'first' => 'Константин','middle' => 'Петрович'],
            ['last' => 'Киселева',   'first' => 'Людмила',   'middle' => 'Олеговна'],
            ['last' => 'Романов',    'first' => 'Григорий',  'middle' => 'Степанович'],
        ];

        $doctorUsers = [];
        $doctors = [];

        $i = 0;
        foreach ($specializations as $spec) {
            $nameData = $doctorNames[$i % count($doctorNames)];
            $i++;

            $user = User::updateOrCreate(
                ['email' => 'doctor'.$spec->id.'@clinic.local'],
                [
                    'name'       => $nameData['first'].' '.$nameData['last'],
                    'phone'      => '+7900'.str_pad((string)$spec->id, 7, '0', STR_PAD_LEFT),
                    'role'       => 'doctor',
                    'is_blocked' => false,
                    'password'   => Hash::make('Doctor123!'),
                ]
            );

            $doctor = Doctor::updateOrCreate(
                [
                    'user_id'           => $user->id,
                    'specialization_id' => $spec->id,
                ],
                [
                    'last_name'        => $nameData['last'],
                    'first_name'       => $nameData['first'],
                    'middle_name'      => $nameData['middle'],
                    'experience_years' => rand(3, 20),
                    'category'         => 'Врач первой категории',
                    'base_price'       => rand(1200, 2500),
                    'about'            => 'Опытный специалист по направлению "'.$spec->name.'". В работе делает акцент на внимательном отношении к пациенту, подробном объяснении диагноза и выборе оптимального плана лечения.',
                ]
            );

            $doctorUsers[] = $user;
            $doctors[] = $doctor;
        }

        $clientData = [
            ['last' => 'Кузнецова', 'first' => 'Светлана', 'middle' => 'Леонидовна'],
            ['last' => 'Смирнов',   'first' => 'Игорь',    'middle' => 'Александрович'],
            ['last' => 'Нефедова',  'first' => 'Мария',    'middle' => 'Ивановна'],
            ['last' => 'Волков',    'first' => 'Алексей',  'middle' => 'Харитонович'],
            ['last' => 'Щербакова', 'first' => 'Елена',    'middle' => 'Петровна'],
            ['last' => 'Громов',    'first' => 'Даниил',   'middle' => 'Иванович'],
            ['last' => 'Мелодина',  'first' => 'Ольга',    'middle' => 'Павловна'],
            ['last' => 'Смирнова',  'first' => 'Виктория', 'middle' => 'Андреевна'],
            ['last' => 'Борисова',  'first' => 'Марина',   'middle' => 'Сергеевна'],
            ['last' => 'Кузьмин',   'first' => 'Фёдор',    'middle' => 'Иванович'],
        ];

        $patients = [];

        foreach ($clientData as $index => $c) {
            $email = 'client'.($index+1).'@mail.local';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'       => $c['first'].' '.$c['last'],
                    'phone'      => '+7910'.str_pad((string)($index+1), 7, '0', STR_PAD_LEFT),
                    'role'       => 'patient',
                    'is_blocked' => false,
                    'password'   => Hash::make('Client123!'),
                ]
            );

            $snils = str_pad((string)($index+1), 3, '0', STR_PAD_LEFT)
                   .'-'
                   .str_pad((string)($index+20), 3, '0', STR_PAD_LEFT)
                   .'-'
                   .str_pad((string)($index+300), 3, '0', STR_PAD_LEFT)
                   .' '
                   .str_pad((string)($index+10), 2, '0', STR_PAD_LEFT);

            $patient = Patient::updateOrCreate(
                ['snils' => $snils],
                [
                    'user_id'    => $user->id,
                    'last_name'  => $c['last'],
                    'first_name' => $c['first'],
                    'middle_name'=> $c['middle'],
                    'phone'      => $user->phone,
                ]
            );

            $patients[] = $patient;
        }

        if (count($patients) > 0 && count($doctors) > 0) {
            $reviewTexts = [
                'Очень внимательный и тактичный врач. Подробно расспросил о жалобах, объяснил результаты анализов простым и понятным языком, предложил несколько вариантов лечения с учётом моего образа жизни. После приёма осталось ощущение, что я в надёжных руках.',
                'Понравилось, что врач не торопился и уделил достаточное время на консультацию. Ответил на все вопросы, разъяснил возможные причины моего состояния и расписал по шагам, как мы будем действовать дальше. Чувствуется профессионализм и большой опыт работы.',
                'Очень деликатный подход: врач сумел успокоить, объяснил, почему не стоит паниковать, но при этом ничего не преуменьшал. Назначенные им обследования оказались действительно нужными, а лечение дало результат уже через пару недель.',
                'Уже не первый раз обращаюсь к этому специалисту. Всегда чётко, по делу и без лишних назначений. Врач внимательно изучает историю болезни, учитывает сопутствующие заболевания и даёт понятные рекомендации по образу жизни и профилактике.',
                'Боялась идти на приём, но врач сразу расположил к себе. Подробно объяснил, что будет делать, аккуратно провёл осмотр, рассказал о плюсах и минусах разных методов лечения. После консультации стало гораздо спокойнее и яснее, как действовать дальше.',
            ];

            foreach ($patients as $pIndex => $patient) {
                $doctor1 = $doctors[$pIndex % count($doctors)];
                $doctor2 = $doctors[($pIndex + 5) % count($doctors)];

                $appointment1 = Appointment::create([
                    'patient_id'        => $patient->id,
                    'doctor_id'         => $doctor1->id,
                    'specialization_id' => $doctor1->specialization_id,
                    'scheduled_at'      => now()->subDays(rand(5, 30))->setTime(10, 0),
                    'status'            => 'completed',
                    'price'             => $doctor1->base_price,
                    'created_by_type'   => 'patient',
                    'created_by_user_id'=> $patient->user_id,
                ]);

                Review::create([
                    'appointment_id' => $appointment1->id,
                    'patient_id'     => $patient->id,
                    'doctor_id'      => $doctor1->id,
                    'rating'         => rand(4, 5),
                    'text'           => $reviewTexts[$pIndex % count($reviewTexts)],
                ]);

                if ($pIndex < 5) {
                    $appointment2 = Appointment::create([
                        'patient_id'        => $patient->id,
                        'doctor_id'         => $doctor2->id,
                        'specialization_id' => $doctor2->specialization_id,
                        'scheduled_at'      => now()->subDays(rand(1, 10))->setTime(14, 0),
                        'status'            => 'completed',
                        'price'             => $doctor2->base_price,
                        'created_by_type'   => 'patient',
                        'created_by_user_id'=> $patient->user_id,
                    ]);

                    Review::create([
                        'appointment_id' => $appointment2->id,
                        'patient_id'     => $patient->id,
                        'doctor_id'      => $doctor2->id,
                        'rating'         => rand(4, 5),
                        'text'           => $reviewTexts[($pIndex + 2) % count($reviewTexts)],
                    ]);
                }
            }
        }
    }
}