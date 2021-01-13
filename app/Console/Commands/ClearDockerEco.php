<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearDockerEco extends CreateDockerEco {
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'eco:clear';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Очищаем систему';

   /**
    * Execute the console command.
    *
    * @return void
    */
   public function handle(): void {
      $this->clearDefaultDirectories();
      $this->disk->delete('docker-compose.yml');

      $this->info('Система успешно удалена');
   }

   /**
    * Удаление директорий по умолчанию
    *
    * @return void
    */

   private function clearDefaultDirectories(): void {
      $this->info('Удаление директорий...');

      foreach (config('eco.directories') as $name) {
         $this->line('Удаление папки ' . $name);
         $this->disk->deleteDirectory($name);
         $this->line('Готово!');
         $this->newLine();
      }

      $this->info('Директории успешно удалены');
   }
}
