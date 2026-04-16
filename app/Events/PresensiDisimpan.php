<?php

namespace App\Events;

use App\Models\Presensi;
use Illuminate\Foundation\Events\Dispatchable;

class PresensiDisimpan
{
    use Dispatchable;

    public function __construct(public Presensi $presensi) {}
}