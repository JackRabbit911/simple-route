<?php

use App\Middleware\TemplateSettings;
use Az\Route\Middleware\RouteDispatch;
use Az\Route\Middleware\RouteMatch;

var_dump(class_exists(RouteDispatch::class)); exit;
var_dump(class_exists(RouteMatch::class)); exit;

$this->pipe(TemplateSettings::class);

$this->pipe(RouteMatch::class);
$this->pipe(RouteDispatch::class);
