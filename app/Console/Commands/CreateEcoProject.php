<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;

class CreateEcoProject extends CreateDockerEco {
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'eco:project-create 
   {name? : Имя проекта}
   {--php-version=7.4 : Версия PHP}
   ';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Создать проект в эко-системе';

   /**
    * Execute the console command.
    *
    * @return void
    */
   public function handle(): void {
      $name = $this->checkProjectName($this->argument('name'));
      $ver = $this->option('php-version');

      $submitted = $this->submitProject(
         [
            ['Имя проекта', $name],
            ['Версия PHP', $ver]
         ]
      );

      while (!$submitted) {
         ['name' => $name, 'ver' => $ver] = $this->newParams();
         $submitted = $this->submitProject(
            [
               ['Имя проекта', $name],
               ['Версия PHP', $ver]
            ]
         );
      }

      $this->createProject($name, $ver);
      $this->info('Проект успешно создан. Структура');
      $this->line(exec('cd storage/app/public && ls -la'));
   }

   /**
    * Проверка на пустоту имени проекта
    *
    * @param string|null $name - Имя проекта
    *
    * @return string
    */

   private function checkProjectName(?string $name): string {
      if (!$name) {
         $this->info('Вы не ввели имя проекта');
         $name = $this->ask('Имя проекта', 'project');
      }

      return $name;
   }

   /**
    * Подтверждение введенных данных
    *
    * @param array $data Данные аргументов и опций
    *
    * @return bool Ответ да или нет
    */

   private function submitProject(array $data): bool {
      $this->info('Ваши параметры');
      $this->table(['Параметр', 'Значение'], $data);

      return $this->confirm('Все верно?', true);
   }

   /**
    * Новые данные
    *
    * @return array Данные
    */
   private function newParams(): array {
      return [
         'name' => $this->ask('Имя проекта', 'project'),
         'ver' => $this->ask('Версия PHP', '7.4')
      ];
   }

   /**
    * Создание проекта
    *
    * @param string $name Имя проекта
    * @param string $version Версия PHP
    *
    * @return void
    */
   private function createProject(string $name, string $version): void {
      $this->info('Создаю папку с проектом');
      $this->disk->makeDirectory(
         implode('/', [
            config('eco.directories.projects'),
            "$name.test"
         ])
      );
      $this->line('Создана папка ' . "$name.test");
      $this->newLine();

      $this->info('Создаю файл конфигурации NGINX');
      $this->disk->append(
         implode('/', [
            config('eco.directories.configs'),
            "$name.conf"
         ]),
         view('nginx-conf', [
            'version' => $version,
            'name' => $name
         ])
      );
      $this->line('Конфигурация создана - ' . "$name.conf");
   }
}
