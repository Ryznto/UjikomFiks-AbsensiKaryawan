<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Top 5 point tertinggi
        $topPoints = User::where('role', 'karyawan')
            ->with('karyawan')
            ->orderBy('point_balance', 'desc')
            ->limit(5)
            ->get();

        // Bottom 5 point terendah
        $bottomPoints = User::where('role', 'karyawan')
            ->with('karyawan')
            ->orderBy('point_balance', 'asc')
            ->limit(5)
            ->get();

        // Rata-rata poin
        $averagePoint = User::where('role', 'karyawan')->avg('point_balance') ?? 0;

        return view('admin.leaderboard.index', compact('topPoints', 'bottomPoints', 'averagePoint'));
    }
}