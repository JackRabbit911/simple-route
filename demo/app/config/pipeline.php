<?php

use App\Middleware\RouteBootstrap;
use Az\Route\Middleware\RouteDispatch;
use Az\Route\Middleware\RouteMatch;

$this->pipe(RouteBootstrap::class);
$this->pipe(RouteMatch::class);
$this->pipe(RouteDispatch::class);
