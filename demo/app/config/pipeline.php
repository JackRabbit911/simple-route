<?php

use App\Middleware\RouteBootstrap;
use App\Middleware\TemplateSettings;
use Az\Route\Middleware\RouteDispatch;
use Az\Route\Middleware\RouteMatch;

$this->pipe(TemplateSettings::class);
$this->pipe(RouteBootstrap::class);
$this->pipe(RouteMatch::class);
$this->pipe(RouteDispatch::class);
