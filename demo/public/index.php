<?php

use App\App;
use DI\ContainerBuilder;

require '../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions('../app/config/container.php');
$container = $containerBuilder->build();

$app = $container->get(App::class);
$app->run();
