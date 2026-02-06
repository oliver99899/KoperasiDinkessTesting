<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitKerja::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_unit', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $units = $query->orderBy('nama_unit', 'asc')->paginate(15)->withQueryString();

        return view('admin.unit-kerja.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255', 'unique:unit_kerja,nama_unit'],
            'jenis' => ['required', 'string', Rule::in(['dinas', 'puskesmas'])],
            'alamat' => ['nullable', 'string', 'max:500'],
            'telepon' => ['nullable', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
        ]);

        UnitKerja::create($validated);

        return back()->with('success', 'Unit kerja baru berhasil didaftarkan ke dalam sistem.');
    }

    public function update(Request $request, $id)
    {
        $unit = UnitKerja::findOrFail($id);

        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255', Rule::unique('unit_kerja', 'nama_unit')->ignore($id)],
            'jenis' => ['required', 'string', Rule::in(['dinas', 'puskesmas'])],
            'alamat' => ['nullable', 'string', 'max:500'],
            'telepon' => ['nullable', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
        ]);

        $unit->update($validated);

        return back()->with('success', 'Informasi unit kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit = UnitKerja::findOrFail($id);

        if ($unit->profiles()->exists()) {
            return back()->with('error', 'Gagal: Prosedur keamanan integritas data mencegah penghapusan unit yang masih memiliki anggota aktif.');
        }

        $unit->delete();

        return back()->with('success', 'Unit kerja berhasil dihapus dari sistem.');
    }
}