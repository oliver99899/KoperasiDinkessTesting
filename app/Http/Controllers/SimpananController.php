<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Simpanan;
use Illuminate\View\View;

class SimpananController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $currentYear = (int) date('Y');

        $availableYears = Simpanan::where('user_id', $user->id)
            ->whereNotNull('verified_at')
            ->selectRaw('YEAR(tanggal_potong) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [$currentYear];
        }

        $requestedYear = $request->input('tahun');
        $requestedYear = is_numeric($requestedYear) ? (int) $requestedYear : null;

        $selectedYear = ($requestedYear && in_array($requestedYear, $availableYears, true))
            ? $requestedYear
            : $availableYears[0];

        $totalSaldo = Simpanan::where('user_id', $user->id)
            ->whereNotNull('verified_at')
            ->sum('jumlah');

        $riwayatSimpanan = Simpanan::where('user_id', $user->id)
            ->whereNotNull('verified_at')
            ->whereYear('tanggal_potong', $selectedYear)
            ->orderByDesc('tanggal_potong')
            ->orderByDesc('id')
            ->get();

        $dataIntegrity = true;

        foreach ($riwayatSimpanan as $item) {
            if (method_exists($item, 'signatureIsValid')) {
                if (!$item->signatureIsValid()) {
                    $dataIntegrity = false;
                    break;
                }
            }
        }

        return view('user.simpanan', compact(
            'totalSaldo',
            'riwayatSimpanan',
            'selectedYear',
            'availableYears',
            'dataIntegrity'
        ));
    }
}
