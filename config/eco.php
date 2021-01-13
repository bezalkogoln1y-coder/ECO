<?php

return [
   'directories' => [
      'projects' => env('ECO_PROJECTS_DIR_PROJECTS', 'domains'),
      'configs' => env('ECO_PROJECTS_DIR_CONFIGS', 'hosts'),
      'db' => env('ECO_PROJECTS_DIR_DB', 'mariadb'),
      'logs' => env('ECO_PROJECTS_DIR_LOGS', 'logs'),
      'php_images' => env('ECO_PROJECTS_DIR_PHP_IMAGES', 'images')
   ]
];