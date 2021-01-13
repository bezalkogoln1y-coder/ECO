<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class CreateDockerEco extends Command {
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'eco:init';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Инициализация системы';

   /**
    * Файловое окружение (storage/app/public)
    *
    * @var Illuminate\Contracts\Filesystem\Filesystem
    */
   protected $disk;

   /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct() {
      parent::__construct();
      $this->disk = Storage::disk('public');
   }

   /**
    * Execute the console command.
    *
    * @return void
    */
   public function handle(): void {
      $this->makeDefaultDirectories();
      $this->newDefaultDockerCompose();
      $this->info(
         'Добавьте систему в избранное вашего файлового менеджера'
      );
      $this->line('Путь - ' . storage_path('app/public'));
      if ($this->confirm('Хотите создать первый проект?')) {
         $this->call(CreateEcoProject::class);
      }
   }

   /**
    * Создание директорий по умолчанию
    *
    * @return void
    */

   private function makeDefaultDirectories(): void {
      $this->info('Создание директорий...');

      foreach (config('eco.directories') as $name) {
         $this->line('Создание папки ' . $name);
         $this->disk->makeDirectory($name);
         $this->line('Готово!');
         $this->newLine();
      }

      $this->info('Директории успешно созданы');
   }

   /**
    * Создание docker-compose по умолчанию
    *
    * @return void
    */

   private function newDefaultDockerCompose(): void {
      $this->info('Создание docker-compose.yml...');
      $this->disk->append('docker-compose.yml', view('docker-compose'));
      $this->info('Успешно создано');
      $this->newLine();
   }
}
