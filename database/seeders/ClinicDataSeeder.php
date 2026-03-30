<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\Appointment;
use App\Models\Review;
use App\Models\Promotion;
use App\Models\Analysis;
use App\Models\Faq;
use App\Models\DoctorEducation;
use App\Models\Page;
use App\Models\ChatThread;
use App\Models\ChatMessage;
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

        // Seed doctor education (образование)
        foreach ($doctors as $dIndex => $doctor) {
            $specialtyName = $doctor->specialization?->name ?? null;

            $educationSeed = [
                [
                    'type' => DoctorEducation::TYPE_UNIVERSITY,
                    'institution' => 'Медицинский университет',
                    'year' => 2008 + ($dIndex % 3),
                    'specialty' => $specialtyName,
                    'sort_order' => 0,
                ],
                [
                    'type' => DoctorEducation::TYPE_RESIDENCY,
                    'institution' => 'Клиническая ординатура',
                    'year' => 2012 + ($dIndex % 3),
                    'specialty' => $specialtyName,
                    'sort_order' => 1,
                ],
                [
                    'type' => DoctorEducation::TYPE_COURSES,
                    'institution' => 'Курсы повышения квалификации',
                    'year' => 2017 + ($dIndex % 4),
                    'specialty' => $specialtyName,
                    'sort_order' => 2,
                ],
            ];

            foreach ($educationSeed as $edu) {
                DoctorEducation::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'type' => $edu['type'],
                        'institution' => $edu['institution'],
                        'year' => $edu['year'],
                        'specialty' => $edu['specialty'],
                    ],
                    [
                        'sort_order' => $edu['sort_order'],
                    ]
                );
            }
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

            // В БД храним СНИЛС как 11 цифр (без разделителей), чтобы вход работал стабильно.
            $snils = \App\Services\SnilsValidator::normalize($snils);

            // Чтобы не плодить дубликаты при повторном сидировании — обновляем по `user_id`.
            $patient = Patient::where('user_id', $user->id)->first();
            if ($patient) {
                $patient->update([
                    'snils' => $snils,
                    'last_name'  => $c['last'],
                    'first_name' => $c['first'],
                    'middle_name'=> $c['middle'],
                    'phone'      => $user->phone,
                ]);
            } else {
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
            }

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

        // Seed promotions (акции) + привязка пациентов к акциям
        if (count($patients) > 0 && count($doctors) > 0) {
            $promotionSeed = [
                [
                    'title' => 'Скидка 20% на консультации',
                    'short_description' => 'Спецпредложение для наших пациентов: -20% на приемы.',
                    'content' => 'На период акции действуют скидки на приемы в клинике. Подробности уточняйте у администраторов.',
                    'starts_at' => now()->subDays(10),
                    'ends_at' => now()->addDays(30),
                    'is_active' => true,
                    'discount_percent' => 20,
                    'patient_indexes' => [0, 1, 2, 3],
                ],
                [
                    'title' => 'Диагностика со скидкой 15%',
                    'short_description' => 'Успейте записаться и получите -15% на обследования.',
                    'content' => 'Скидка распространяется на выбранные приемы и обследования в период действия акции.',
                    'starts_at' => now()->subDays(5),
                    'ends_at' => now()->addDays(20),
                    'is_active' => true,
                    'discount_percent' => 15,
                    'patient_indexes' => [4, 5, 6],
                ],
                [
                    'title' => 'Летняя акция: -10% на повторные визиты',
                    'short_description' => 'Для пациентов, которые уже проходили лечение ранее.',
                    'content' => 'Если у вас есть история обращений в клинику, вы можете воспользоваться скидкой на повторный визит.',
                    'starts_at' => now()->subDays(2),
                    'ends_at' => now()->addDays(40),
                    'is_active' => true,
                    'discount_percent' => 10,
                    'patient_indexes' => [0, 7, 8],
                ],
            ];

            foreach ($promotionSeed as $seed) {
                $promotion = Promotion::updateOrCreate(
                    ['title' => $seed['title']],
                    [
                        'short_description' => $seed['short_description'],
                        'content' => $seed['content'],
                        'starts_at' => $seed['starts_at'],
                        'ends_at' => $seed['ends_at'],
                        'is_active' => $seed['is_active'],
                        'discount_percent' => $seed['discount_percent'],
                    ]
                );

                $patientIds = [];
                foreach (($seed['patient_indexes'] ?? []) as $idx) {
                    $patientIds[] = $patients[$idx % count($patients)]->id;
                }

                if (!empty($patientIds)) {
                    $promotion->patients()->syncWithoutDetaching(array_values(array_unique($patientIds)));
                }
            }

            // Seed analyses (анализы)
            $analysisTypes = [
                'Общий анализ крови',
                'Биохимический анализ крови',
                'Анализ мочи',
                'Липидограмма',
                'Тиреотропный гормон (ТТГ)',
                'HbA1c (гликированный гемоглобин)',
                'Глюкоза натощак',
            ];

            $analysisResults = [
                'Показатели в целом соответствуют референсным значениям. Рекомендуется контроль в динамике.',
                'Обнаружены отклонения по отдельным параметрам. Назначены дополнительные обследования.',
                'Данные требуют клинической интерпретации с учетом симптомов. Показана консультация специалиста.',
                'Результаты согласуются с предполагаемым диагнозом. Продолжайте лечение и соблюдайте рекомендации.',
            ];

            $analysisCount = min(12, count($patients) * 2);
            for ($i = 0; $i < $analysisCount; $i++) {
                $patient = $patients[$i % count($patients)];
                $doctor = $doctors[$i % count($doctors)];
                $type = $analysisTypes[$i % count($analysisTypes)];
                $takenAt = now()->subDays(25 + $i);

                Analysis::updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'type' => $type,
                    ],
                    [
                        'taken_at' => $takenAt->toDateString(),
                        'file_path' => null,
                        'result_text' => $analysisResults[$i % count($analysisResults)],
                    ]
                );
            }
        }

        // Seed FAQ
        $faqSeed = [
            [
                'question' => 'Как записаться на прием?',
                'answer' => 'Выберите раздел “Запись” на сайте, затем специализацию, врача и удобную дату/время. Подтвердите заявку — администратор свяжется с вами.',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'question' => 'Можно ли прикрепить результаты анализов?',
                'answer' => 'Да. В личном кабинете (раздел “Мои анализы” или через врача) вы можете загрузить результаты и файлы, чтобы специалист быстрее сориентировался.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'Как узнать стоимость приема?',
                'answer' => 'Стоимость зависит от врача и специализации. В карточке врача указана базовая цена, а по активным акциям возможна скидка.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'Я забыл(а) пароль. Что делать?',
                'answer' => 'Используйте функцию восстановления в форме входа или обратитесь в поддержку. При необходимости администратор поможет восстановить доступ.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($faqSeed as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'is_active' => $faq['is_active'],
                    'sort_order' => $faq['sort_order'],
                ]
            );
        }

        // Seed static pages (страницы сайта)
        $pageSeed = [
            [
                'slug' => 'about',
                'title' => 'О нас',
                'content' => 'Наша клиника заботится о здоровье пациентов и использует современные методы диагностики и лечения. Мы стараемся, чтобы каждое обращение было понятным и комфортным.',
            ],
            [
                'slug' => 'contacts',
                'title' => 'Контакты',
                'content' => 'Телефон: +7 (999) 123-45-67<br>Адрес: г. Москва, ул. Примерная, 1<br>График работы: ежедневно 09:00–20:00',
            ],
            [
                'slug' => 'services',
                'title' => 'Услуги',
                'content' => 'Прием врачей по направлениям, лабораторные анализы, диагностика и сопровождение пациентов. Запись доступна на сайте и через администраторов.',
            ],
        ];

        foreach ($pageSeed as $p) {
            Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'content' => $p['content'],
                ]
            );
        }

        // Seed chat threads + messages (чат)
        $admin = User::where('email', 'admin@clinic.local')->first();
        if ($admin && count($patients) > 0) {
            $threadPatientIndexes = [0, 3];
            foreach ($threadPatientIndexes as $idx) {
                $patient = $patients[$idx % count($patients)];

                $thread = ChatThread::firstOrCreate(
                    ['patient_id' => $patient->id],
                    ['admin_id' => $admin->id]
                );

                if ($thread->messages()->count() === 0) {
                    ChatMessage::create([
                        'thread_id' => $thread->id,
                        'sender_id' => $patient->user_id,
                        'message' => 'Здравствуйте! Хотел(а) бы записаться на прием. Подскажите, пожалуйста, ближайшие свободные даты.',
                    ]);

                    ChatMessage::create([
                        'thread_id' => $thread->id,
                        'sender_id' => $admin->id,
                        'message' => 'Здравствуйте! Мы поможем с записью. Выберите направление/врача на сайте или оставьте удобное время — администратор подтвердит запись.',
                    ]);
                }
            }
        }

    }
}