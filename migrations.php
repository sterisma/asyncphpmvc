<?php

// return [
//     "migrations-namespace" => "App\Migrations",
//     "migrations-directory" => __DIR__ . "/Migrations",
// ];

// versi yang sekarang pakai yang ini
return [
    'migrations_paths' => [
        'App\Migrations' => __DIR__.'/Migrations',
    ],
];