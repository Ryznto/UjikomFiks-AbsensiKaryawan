<?php

namespace App\Providers;

use App\Events\PresensiDisimpan;
use App\Listeners\ProsesPointDariPresensi;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PresensiDisimpan::class => [
            ProsesPointDariPresensi::class,
        ],
    ];
}