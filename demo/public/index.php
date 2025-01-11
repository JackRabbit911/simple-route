<?php

use App\App;
use DI\ContainerBuilder;

chdir(__DIR__);
require '../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions('../app/config/container.php');
$containerBuilder->useAttributes(true);

$container = $containerBuilder->build();

$app = $container->get(App::class);
$app->run();
