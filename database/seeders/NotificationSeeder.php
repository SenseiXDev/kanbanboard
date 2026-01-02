<?php

namespace Database\Seeders;

use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            'new_project'=>'New Project', 'new_task'=>'New Task','new_invoice' =>'New Invoice','task_stage_updated'=>'Task Stage Updated' , 'new_milestone' =>'New Milestone','milestone_status_updated'=>'Milestone Status Updated','invoice_status_updated'=>'Invoice Status Updated'
        ];

        $defaultTemplate = [
                'new_project' => [
                    'variables' => '{
                    "Project Title": "project_name",
                    "Project Budget": "project_budget",
                    "Project Hours": "project_hours",
                    "Start Date": "start_date",
                    "End Date": "end_date",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                    'lang' => [
                        'ar' => 'تم تكوين المشروع {project_name} بواسطة {app_name}. اجمالي الساعات هي {project_ساعات}.',
                        'da' => 'Projektet {project_name} er oprettet af {app_name}. Det samlede antal timer er {project_hours}.',
                        'de' => 'Das Projekt {project_name} wird von {app_name} erstellt. Die Gesamtstunden sind {project_hours}.',
                        'en' => 'The {project_name} project is created by {app_name}. The total hours are {project_hours}.',
                        'es' => 'El proyecto {project_name} está creado por {app_name}. Las horas totales son {project_hours}.',
                        'fr' => "Le projet {project_name} est créé par {app_name}. Le nombre total d'heures est de {project_hours}.",
                        'it' => 'Il progetto {project_name} è creato da {app_name}. Le ore totali sono {project_hours}.',
                        'ja' => '{project_name} プロジェクトは {app_name}によって作成されます。 合計時間は {project_hours}です。',
                        'nl' => 'Het project {project_name} is gemaakt door {app_name}. De totale uren zijn {project_hours}.',
                        'pl' => 'Projekt {project_name } jest tworzony przez użytkownika {app_name}. Łączna liczba godzin to {project_hours}.',
                        'ru' => 'Проект {project_name} создан {app_name}. Общее число часов: {project_hours}.',
                        'pt' => 'O projeto {project_name} é criado por {app_name}. O total de horas são {project_hours}.',
                        'tr' => '{ proj_name } tarafından { project_name } projesi yaratıldı. Toplam saat: { project_hours }.',
                        'zh' => '{project_name} 项目由 {app_name} 创建。 总小时数为 {project_小时数}。',
                        'he' => 'הפרויקט {project_name} נוצר על-ידי {app_name}. השעות הכולל הן {project_השעות}.',
                        'pt-br' => 'O projeto {project_name} é criado por {app_name}. O total de horas são {project_hours}.',

                    ]
                ],

                'new_task' => [
                    'variables' => '{
                        "Task Name" : "task_name",
                        "Task Priority" : "task_priority",
                        "Task Project" : "task_project",
                        "Task Stage" : "task_stage",
                        "Owner Name" : "owner_name",
                        "Project Title": "project_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم تكوين المهمة {task_name} بواسطة {owner_name} من {project_name} المشروع.',
                            'da' => 'Opgave {task_name} er oprettet af {owner_name} af {project_name}-projektet.',
                            'de' => 'Die Task {task_name} wird von {owner_name} des Projekts {project_name} erstellt.',
                            'en' => 'The {task_name} Task is Created By {owner_name} of {project_name} Project',
                            'es' => 'La tarea {task_name} se ha creado por {owner_name} de {project_name} Project.',
                            'fr' => 'La tâche {task_name} est créée par {nom_utilisateur} du projet {nom_projet}.',
                            'it' => 'Il {task_name} Task è Creato By {owner_name} di {project_name} Project.',
                            'ja' => '{task_name } タスクは、 {project_name} プロジェクトの {owner_name} によって作成されます。',
                            'nl' => 'De taak {taaknaam} is gemaakt door {owner_name} van {project_name} Project.',
                            'pl' => 'Zadanie {task_name} zostało utworzone przez użytkownika {owner_name } z projektu {project_name }.',
                            'ru' => 'Задача {task_name} Создана {owner_name} проекта {project_name}.',
                            'pt' => 'A Tarefa {task_name} é Criada por {owner_name} de {project_name} Project.',
                            'tr' => '{ task_name } Görevi, { project_name } Project { owner_name } Tarafından Oluşturuldu.',
                            'zh' => '{task_name} 任务由 {project_name} 项目创建',
                            'he' => 'הפרויקט {project_name} נוצר על-ידי {app_name}. השעות הכולל הן {project_השעות}.',
                            'pt-br' => 'A Tarefa {task_name} é Criada por {owner_name} de {project_name} Project.',

                        ]
                    ],

                    'new_invoice' => [
                        'variables' => '{
                        "Invoice Name": "invoice_id",
                        "Owner Name" : "owner_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم تكوين الفاتورة الجديدة {invoice_id} بواسطة {owner_name}',
                            'da' => 'Ny faktura {invoice_id} oprettet af {owner_name}',
                            'de' => 'Neue Rechnung {invoice_id} erstellt von {owner_name}',
                            'en' => 'New Invoice {invoice_id} created by {owner_name} ',
                            'es' => 'Nueva factura {invoice_id} creada por {owner_name}',
                            'fr' => "Nouvelle facture {invoice_id} créée par { nom_utilisateur }",
                            'it' => 'Nuova Fattura {invoice_id} creata da {owner_name}',
                            'ja' => '{owner_name} によって作成された新規請求書 {請求 name}',
                            'nl' => 'Nieuwe factuur {invoice_id} gemaakt door {owner_name}',
                            'pl' => 'Nowa faktura {invoice_id } utworzona przez użytkownika {owner_name }',
                            'ru' => 'Новый инвойс {invoice_id} создан пользователем {owner_name}',
                            'pt' => 'Nova Fatura {invoice_id} criada por {owner_name}',
                            'tr' => '{ owner_name } tarafından oluşturulan yeni Fatura { invoice_id }',
                            'zh' => '{owner_name} 创建的新发票 {invoice_id} ',
                            'he' => 'חשבונית חדשה {invoice_id} נוצרה על-ידי {owner_name}',
                            'pt-br' => 'Nova Fatura {invoice_id} criada por {owner_name}',

                        ]
                    ],

                    'task_stage_updated' => [
                        'variables' => '{
                            "Task Name" : "task_name",
                            "New Stage" : "new_stage",
                            "old Stage" : "old_stage",
                            "Task Priority" : "task_priority",
                            "Task Project" : "task_project",
                            "App Name": "app_name",
                            "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم تعديل مرحلة المهمة من {task_name} من {old_مرحلة} الى المشروع { new_dattle }',
                            'da' => 'Opgavetrin for {task_name} opdateret fra {old_stage} til {ny_stage} projekt',
                            'de' => 'Taskstufe von {task_name} von {old_stage} auf {new_stage} Projekt aktualisiert',
                            'en' => 'Task stage of {task_name} updated from {old_stage} to {new_stage} Project',
                            'es' => 'Etapa de tarea de {task_name} actualizada de {old_stage} al proyecto {new_stage}',
                            'fr' => "Etape de la tâche {task_name} mise à jour de { old_étape } au projet {new_stage}",
                            'it' => 'Stage di attività di {task_name} aggiornato da {old_stage} al {new_stage} Project',
                            'ja' => '{task_name} のタスク・ステージが {old_stage} から {new_stage} プロジェクトに更新されました',
                            'nl' => 'Taakstadium van {task_name} bijgewerkt van {old_stage} naar { new_stage } Project',
                            'pl' => 'Etap zadania {task_name } został zaktualizowany z {old_stage } do projektu {new_stage}',
                            'ru' => 'Этап задачи {task_name} обновлен с {old_stage} на {new_stage} Project',
                            'pt' => 'Estágio de tarefa de {task_name} atualizado de {old_stage} para {new_stage} Project ',
                            'tr' => '{ task_name } görev aşaması { old_stage } tarafından { new_stage } Projesine güncellendi',
                            'zh' => '{task_name} 的任务阶段已从 {old_stage} 更新为 {new_stage} 项目',
                            'he' => 'שלב המשימה של {task_name} עודכן מ - {old_מהבמה} ל - {new_stage} פרויקט',
                            'pt-br' => 'Estágio de tarefa de {task_name} atualizado de {old_stage} para {new_stage} Project ',


                        ]
                    ],

                    'new_milestone' => [
                        'variables' => '{
                        "Milestone Title": "milestone_title",
                        "Milestone Status": "milestone_status",
                        "Owner Name" : "owner_name",
                        "Project Title": "project_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => '{ brow_one_title } Milestoner تم تكوينه بواسطة {owner_name} المشروع { project_name } المشروع',
                            'da' => '{ milestone_title } Milepæl er oprettet af {owner_name} af { project_name } projekt }
                            ',
                            'de' => '{milestone_title} Meilenstein wird erstellt von {owner_name} von {project_name} Projekt
                            ',
                            'en' => '{milestone_title} Milestone is Created By {owner_name} of {project_name} Project',
                            'es' => '{title one_title} El hito se crea por {owner_name} de {project_name} Project
                            ',
                            'fr' => 'Le jalon { milestone_title } est créé par { nom_utilisateur } du projet { nom_projet }
                            ',
                            'it' => '{milestone_title} Milestone è Creato By {owner_name} di {project_name} Project
                            ',
                            'ja' => '{ マイルストーン・タイトル} マイルストーンは、 {project_name} プロジェクトの {owner_name} { owner_name} によって作成されます
                            ',
                            'nl' => '{ milestone_title } Milestone wordt gemaakt door {owner_name} van { project_name } Project
                            ',
                            'pl' => 'Kamień milowy {milestone_title } został utworzony przez użytkownika {owner_name } z projektu {project_name }
                            ',
                            'ru' => '{ milestone_title } Ilestone Создается пользователем {owner_name} проекта { project_name }
                            ',
                            'pt' => '{milestone_title} Milestone é Criado por {owner_name} de {project_name} Project',
                            'tr' => '{ mileone_title } Aşama, { project_name } Projesinin { owner_name } Tarafından Oluşturuldu',
                            'zh' => '{milestone_title } 里程碑由 {project_name} 的 {owner_name} 创建',
                            'he' => '{המייל} אבן דרך נוצר על ידי {owner_name} של פרויקט {project_name}',
                            'pt-br' => '{milestone_title} Milestone é Criado por {owner_name} de {project_name} Project',

                        ]
                    ],

                    'milestone_status_updated' => [
                        'variables' => '{
                        "Milestone Title": "milestone_title",
                        "Milestone Status": "milestone_status",
                        "Milestone Progress": "milestone_progress",
                        "Owner Name" : "owner_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'حالة { elos_one_title } تم تعديلها بواسطة { owner_name }',
                            'da' => 'status for { milestone_title } opdateret af { owner_name }
                            ',
                            'de' => 'Status von {milestone_title} aktualisiert von {owner_name}',
                            'en' => 'status of {milestone_title} updated by {owner_name}',
                            'es' => 'estado de {title one_title} actualizado por {owner_name}
                            ',
                            'fr' => "Statut de { milestone_title } mis à jour par { nom_utilisateur }
                            ",
                            'it' => 'stato di {milestone_title} aggiornato da {owner_name}
                            ',
                            'ja' => '{owner_name} によって更新された {milestone_title} の状況
                            ',
                            'nl' => 'status van { milestone_title } bijgewerkt door { owner_name }
                            ',
                            'pl' => 'status {milestone_title } został zaktualizowany przez użytkownika {owner_name }
                            ',
                            'ru' => 'состояние { milestone_title } обновлено пользователем { owner_name }
                            ',
                            'pt' => 'status de {milestone_title} atualizado por {owner_name}',
                            'tr' => '{ mounone_title } durumu { owner_name } tarafından güncelleştirildi',
                            'zh' => '{owner_name} 已更新 {mileestone_title } 的状态',
                            'he' => 'הסטאטוס של {המייל stone_title} שעודכן על ידי {owner_name}',
                            'pt-br' => 'status de {milestone_title} atualizado por {owner_name}',

                        ]
                    ],


                    'invoice_status_updated' => [
                        'variables' => '{
                        "Invoice Name": "invoice_id",
                        "Owner Name" : "owner_name",
                        "App Name": "app_name",
                        "App Url": "app_url"
                        }',
                        'lang' => [
                            'ar' => 'تم تعديل حالة { invoice_id } بواسطة { owner_name }',
                            'da' => 'status på { invoice_id } opdateret af { owner_name }
                            ',
                            'de' => 'Status von {invoice_id} aktualisiert von {owner_name}',
                            'en' => 'status of {invoice_id} updated by {owner_name}',
                            'es' => 'estado de {invoice_id} actualizado por {owner_name}
                            ',
                            'fr' => "Statut de { invoice_id } mis à jour par { nom_utilisateur }
                            ",
                            'it' => 'stato di {invoice_id} aggiornato da {owner_name}
                            ',
                            'ja' => '{ owner_name} によって更新された {請求 name} の状況',
                            'nl' => 'status van { invoice_id } bijgewerkt door { owner_name }
                            ',
                            'pl' => 'status {invoice_id } został zaktualizowany przez użytkownika {owner_name }
                            ',
                            'ru' => 'состояние { invoice_id } обновлено пользователем { owner_name }
                            ',
                            'pt' => 'status de {invoice_id} atualizado por {owner_name}',
                            'tr' => '{ owner_name } tarafından güncellenen { invoice_id } durumu',
                            'zh' => '{ owner_name} 已更新 {invoice_id} 的状态',
                            'he' => 'הסטאטוס של {invoice_id} עודכן על-ידי {owner_name}',
                            'pt-br' => 'status de {invoice_id} atualizado por {owner_name}',

                        ]
                    ],
            ];


        $user = User::where('type','super admin')->first();

        foreach($notifications as $k => $n)
        {
            $ntfy = NotificationTemplates::where('slug',$k)->count();
            if($ntfy == 0)
            {
                $new = new NotificationTemplates();
                $new->name = $n;
                $new->slug = $k;
                $new->save();

                foreach($defaultTemplate[$k]['lang'] as $lang => $content)
                {
                    NotificationTemplateLangs::create(
                        [
                            'parent_id' => $new->id,
                            'lang' => $lang,
                            'variables' => $defaultTemplate[$k]['variables'],
                            'content' => $content,
                            'created_by' => !empty($user) ? $user->id : 1,
                        ]
                    );
                }
            }
        }
    }
}
