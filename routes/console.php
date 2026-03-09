<?php

use Illuminate\Support\Facades\Schedule;    

Schedule::command('absen:mark-alfa')->hourly(); 
// cara debug kalo karyawan alfa php artisan absen:mark-alfa