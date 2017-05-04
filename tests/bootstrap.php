<?php

require __DIR__ . '/../vendor/autoload.php';

// setup database
$database = new Illuminate\Database\Capsule\Manager;
$database->addConnection([
    'driver'   => 'sqlite',
    'database' => ':memory:',
]);
$database->bootEloquent();
$database->setAsGlobal();

class_alias(Illuminate\Support\Facades\Schema::class, 'Schema');
