<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
// attention ligne 5 à remplacer chez Hostinger pour éviter l'url avec /public
// require_once __DIR__.'/vendor/autoload_runtime.php';
// + tout mettre le répertoire public dans la racine


return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
