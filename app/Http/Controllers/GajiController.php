<?php
namespace App\Http\Controllers;

use App\Models\Gaji;
use Barryvdh\DomPDF\Facade\PDF as PDF;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gajis = Gaji::all();
        return view('gaji.index', compact('gajis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nip'         => 'required',
            'nama'        => 'required',
            'tahun_masuk' => 'required',
            'gaji_pokok'  => 'required|numeric',
            'jabatan'     => 'required',
        ]);

        $nip         = $request->input('nip');
        $nama        = $request->input('nama');
        $tahun_masuk = $request->input('tahun_masuk');
        $gaji_pokok  = $request->input('gaji_pokok');
        $jabatan     = $request->input('jabatan');

        $jam_lembur            = $request->input('jam_lembur');
        $jumlah_pelanggan      = $request->input('jumlah_pelanggan');
        $peningkatan_penjualan = $request->input('peningkatan');

        $tahun_sekarang = date('Y');

        if ($jabatan == 'satpam') {
            $gaji_lembur = $jam_lembur * 20000;
            $gaji_akhir  = $gaji_pokok + $gaji_lembur;

        } elseif ($jabatan == 'sales') {
            $komisi     = $jumlah_pelanggan * 50000;
            $gaji_akhir = $gaji_pokok + $komisi;

        } elseif ($jabatan == 'administrasi') {
            if ($tahun_sekarang - $tahun_masuk >= 5) {
                $tunjangan = $gaji_pokok * 0.03;
            } elseif ($tahun_sekarang - $tahun_masuk < 5) {
                $tunjangan = $gaji_pokok * 0.01;
            } elseif ($tahun_sekarang - $tahun_masuk < 3) {
                $tunjangan = 0;
            }
            $gaji_akhir = $gaji_pokok + $tunjangan;

        } elseif ($jabatan == 'manajer') {
            if ($peningkatan_penjualan > 10) {
                $bonus = $gaji_pokok * 0.1;
            } elseif ($peningkatan_penjualan < 10 || $peningkatan_penjualan >= 6) {
                $bonus = $gaji_pokok * 0.05;
            } elseif ($peningkatan_penjualan < 6 || $peningkatan_penjualan >= 1) {
                $bonus = $gaji_pokok * 0.02;
            } else {
                $bonus = 0;
            }
            $gaji_akhir = $gaji_pokok + $bonus;
        }

        $save = Gaji::create([
            'nip'                   => $nip,
            'nama'                  => $nama,
            'tahun_masuk'           => $tahun_masuk,
            'gaji_pokok'            => $gaji_pokok,
            'jabatan'               => $jabatan,
            'jumlah_pelanggan'      => $jumlah_pelanggan ? $jumlah_pelanggan : 0,
            'jam_lembur'            => $jam_lembur ? $jam_lembur : 0,
            'peningkatan_penjualan' => $peningkatan_penjualan ? $peningkatan_penjualan : 0,
            'gaji_akhir'            => $gaji_akhir,
        ]);

        if ($save) {
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            session()->flash('error', 'Data gagal disimpan.');
        }

        return redirect()->route('gaji.index')->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function cetak(string $id)
    {
        // Ambil data dari database menggunakan model Gaji
        $gaji = Gaji::find($id);

// Periksa apakah data ditemukan
        if (! $gaji) {
            abort(404, 'Data tidak ditemukan');
        }

// Konversi model Gaji ke array
        $data = $gaji->toArray();

// Render view ke PDF
        $pdf = PDF::loadView('gaji/slip-gaji', $data);

// view file PDF
        return $pdf->stream('slip-gaji_' . $gaji->nip . '.pdf');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gaji = Gaji::findOrFail($id);
        $gaji->delete();

        if ($gaji) {
            session()->flash('success', 'Data berhasil dihapus.');
        } else {
            session()->flash('error', 'Data gagal dihapus.');
        }
        return redirect()->route('gaji.index');
    }
}
