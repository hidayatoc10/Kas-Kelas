<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\SimpleXLSX;
use App\Models\BulanPembayaran;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;
use App\Models\TransaksiMasuk;

class BendaharaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::count();
        $transaksi = TransaksiMasuk::sum('jumlah');
        $pengeluaran = TransaksiKeluar::sum('jumlah_pengeluaran');
        $total = $transaksi - $pengeluaran;
        return view('bendahara.dashboard', [
            'siswa' => $siswa,
            'pemasukan' => $transaksi,
            'pengeluaran' => $pengeluaran,
            'total' => $total,
        ]);
    }

    public function editAccount()
    {
        return view('bendahara.akun_saya');
    }
    public function editAccountPost(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($validated['old_password'], $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak sesuai.']);
        }
        if (Hash::check($validated['new_password'], $user->password)) {
            return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.']);
        }
        $user->password = Hash::make($validated['new_password']);
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Password berhasil diubah.');
    }
    public function siswa()
    {
        $siswa = Siswa::latest()->get();
        return view('bendahara.siswa', [
            'siswa' => $siswa,
        ]);
    }
    public function siswaAdd()
    {
        $siswa = Siswa::all();
        return view('bendahara.tambah_siswa', [
            'siswa' => $siswa,
        ]);
    }
    public function siswaAddPost(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|max:10|unique:siswa',
            'jenis_kelamin' => 'required|string|max:10',
        ]);
        Siswa::create($validated);
        return redirect()->route('siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }
    public function siswaEdit($nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();
        return view('bendahara.ubah_siswa', [
            'siswa' => $siswa,
        ]);
    }
    public function siswaEditPost(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $validated = $request->validate([
            'nis' => 'required|string|max:10|unique:siswa,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:10',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa')->with('success', 'Siswa berhasil diubah.');
    }
    public function siswaDelete($nis)
    {
        Siswa::where('nis', $nis)->delete();
        return redirect()->route('siswa')->with('success', 'Siswa berhasil dihapus.');
    }
    public function importSiswa(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx',
        ]);

        $file = $request->file('excel_file');
        $path = $file->getRealPath();

        require_once app_path('Helpers/SimpleXLSX.php');
        if ($xlsx = SimpleXLSX::parse($path)) {
            $rows = $xlsx->rows();

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                Siswa::create([
                    'nis' => $row[0],
                    'nama' => $row[1],
                    'jenis_kelamin' => $row[2],
                ]);
            }

            return back()->with('success', 'Import berhasil!');
        } else {
            return back()->with('error', 'Gagal membaca file Excel.');
        }
    }
    public function pemasukan()
    {
        $siswa = Siswa::latest()->get();
        $transaksi = TransaksiMasuk::with('siswa')->latest()->get();
        $totalPemasukan = TransaksiMasuk::sum('jumlah');
        $namaBulan = Carbon::now()->translatedFormat('F');
        $bulanList = BulanPembayaran::with('transaksi')->get();
        foreach ($bulanList as $bulan) {
            $bulan->total = $bulan->transaksi->sum('jumlah');
        }
        return view('bendahara.pemasukan', [
            'siswa' => $siswa,
            'transaksi' => $transaksi,
            'totalPemasukan' => $totalPemasukan,
            'namaBulan' => $namaBulan,
            'bulanList' => $bulanList,
        ]);
    }

    public function pemasukanAdd()
    {
        $siswa = Siswa::latest()->get();
        return view('bendahara.catat_kas', [
            'siswa' => $siswa,
        ]);
    }
    public function pemasukanAddPost(Request $request)
    {
        $validated = $request->validate([
            'nama_id' => 'required|exists:siswa,id',
            'status' => 'required|in:LUNAS,BELUM LUNAS',
            'description' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:0',
        ]);
        TransaksiMasuk::create($validated);
        return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil ditambahkan.');
    }
    public function pemasukanDelete($id)
    {
        TransaksiMasuk::where('id', $id)->delete();
        return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil dihapus.');
    }
    public function AddBulan(Request $request)
    {
        $validated = $request->validate([
            'nama_bulan' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
            'pembayaran_seminggu' => 'required|integer|min:0',
        ]);
        $bulan = new BulanPembayaran();
        $bulan->nama_bulan = $validated['nama_bulan'];
        $bulan->tahun = $validated['tahun'];
        $bulan->pembayaran_seminggu = $validated['pembayaran_seminggu'];
        $bulan->save();
        return redirect()->route('pemasukan')->with('success', 'Bulan pembayaran berhasil ditambahkan.');
    }
    public function DeleteBulan($id)
    {
        BulanPembayaran::where('id', $id)->delete();
        return redirect()->route('pemasukan')->with('success', 'Bulan pembayaran berhasil dihapus.');
    }
    public function show($bulan_id)
    {
        $bulan = BulanPembayaran::findOrFail($bulan_id);
        $siswaList = Siswa::all();
        foreach ($siswaList as $siswa) {
            TransaksiMasuk::firstOrCreate(
                ['nama_id' => $siswa->id, 'bulan_id' => $bulan_id],
                [
                    'minggu_1' => 'BELUM LUNAS',
                    'minggu_2' => 'BELUM LUNAS',
                    'minggu_3' => 'BELUM LUNAS',
                    'minggu_4' => 'BELUM LUNAS',
                    'status_lunas' => 'BELUM LUNAS',
                    'jumlah' => 0,
                ],
            );
        }
        $transaksi = TransaksiMasuk::with('siswa')->where('bulan_id', $bulan_id)->get();
        return view('bendahara.catat_kas', compact('transaksi', 'bulan'));
    }
    public function updateMinggu(Request $request)
    {
        $transaksi = TransaksiMasuk::findOrFail($request->id);
        $minggu = $request->minggu;
        if ($transaksi->$minggu === 'LUNAS') {
            return response()->json(['status' => 'error', 'message' => 'Pembayaran sudah lunas.']);
        }
        $transaksi->$minggu = 'LUNAS';
        $bulan = BulanPembayaran::find($transaksi->bulan_id);
        if (!$bulan) {
            return response()->json(['status' => 'error', 'message' => 'Data bulan tidak ditemukan.']);
        }
        $transaksi->jumlah += $bulan->pembayaran_seminggu;
        $allLunas = !in_array('BELUM LUNAS', [$transaksi->minggu_1, $transaksi->minggu_2, $transaksi->minggu_3, $transaksi->minggu_4]);
        $transaksi->status_lunas = $allLunas ? 'LUNAS' : 'BELUM LUNAS';
        $transaksi->save();
        return response()->json(['status' => 'success', 'message' => 'Berhasil melunasi pembayaran.']);
    }
    public function laporan()
    {
        $bulans = BulanPembayaran::all();
        return view('bendahara.laporan', compact('bulans'));
    }

    public function tampilkan(Request $request)
    {
        $bulan_id = $request->bulan_id;

        $bulan = BulanPembayaran::findOrFail($bulan_id);
        $transaksis = TransaksiMasuk::where('bulan_id', $bulan_id)->with('siswa')->get();

        return view('bendahara.laporan', [
            'bulans' => BulanPembayaran::all(),
            'transaksis' => $transaksis,
            'bulan_terpilih' => $bulan,
        ]);
    }
    public function pengeluaran()
    {
        $pengeluaran = TransaksiKeluar::latest()->get();
        $totalPemasukan = TransaksiMasuk::sum('jumlah');
        $totalPengeluaran = TransaksiKeluar::sum('jumlah_pengeluaran');
        $sisaSaldo = $totalPemasukan - $totalPengeluaran;

        return view('bendahara.pengeluaran', compact('pengeluaran', 'totalPemasukan', 'sisaSaldo'));
    }
    public function pengeluaranAdd(Request $request)
    {
        $validated = $request->validate([
            'jumlah_pengeluaran' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
        ]);
        $totalPemasukan = TransaksiMasuk::sum('jumlah');
        $totalPengeluaran = TransaksiKeluar::sum('jumlah_pengeluaran');
        $sisaSaldo = $totalPemasukan - $totalPengeluaran;
        if ($validated['jumlah_pengeluaran'] > $sisaSaldo) {
            return redirect()->route('pengeluaran')->with('error', 'Saldo tidak cukup. Pengeluaran melebihi sisa kas atau nanti uang anda akan minus.');
        }
        TransaksiKeluar::create($validated);
        return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }
    public function pengeluaranDelete($id)
    {
        TransaksiKeluar::where('id', $id)->delete();
        return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil dihapus.');
    }
    public function pengeluaranEdit($id)
    {
        $pengeluaran = TransaksiKeluar::findOrFail($id);
        return view('bendahara.ubah_pengeluaran', compact('pengeluaran'));
    }
    public function pengeluaranEditPost(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_pengeluaran' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
        ]);
        TransaksiKeluar::where('id', $id)->update($validated);
        return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil diubah.');
    }
    public function laporanPengeluaran()
    {
        return view('bendahara.laporan_pengeluaran', [
            'bulans' => BulanPembayaran::all(),
        ]);
    }

    public function laporanPengeluaranTampilkan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $transaksis = TransaksiKeluar::whereDate('created_at', '>=', $tanggal_awal)
            ->whereDate('created_at', '<=', $tanggal_akhir)
            ->get();

        $total_pemasukan = TransaksiMasuk::whereDate('created_at', '>=', $tanggal_awal)
            ->whereDate('created_at', '<=', $tanggal_akhir)
            ->sum('jumlah');

        $total_pengeluaran = $transaksis->sum('jumlah_pengeluaran');

        return view('bendahara.laporan_pengeluaran', [
            'bulans' => BulanPembayaran::all(),
            'transaksis' => $transaksis,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'total_pemasukan' => $total_pemasukan,
            'total_pengeluaran' => $total_pengeluaran,
        ]);
    }
    public function cek_siswa()
    {
        $bulanList = BulanPembayaran::orderBy('id', 'desc')->get();
        return view('welcome', compact('bulanList'));
    }
    public function proses_cek_siswa(Request $request)
    {
        $request->validate([
            'nis' => 'required|numeric',
            'bulan_id' => 'required|exists:bulan_pembayaran,id'
        ]);
        $siswa = Siswa::where('nis', $request->nis)->first();
        $bulanList = BulanPembayaran::orderBy('id', 'desc')->get();
        if (!$siswa) {
            return back()->with('error', 'Siswa dengan NIS tersebut tidak ditemukan.')->withInput();
        }
        $transaksi = TransaksiMasuk::where('nama_id', $siswa->id)
            ->where('bulan_id', $request->bulan_id)
            ->with('bulan')
            ->first();

        return view('welcome', compact('siswa', 'transaksi', 'bulanList'));
    }
    public function cetak_bukti(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'bulan_id' => 'required|exists:bulan_pembayaran,id'
        ]);

        $siswa = Siswa::where('nis', $request->nis)->firstOrFail();
        $transaksi = TransaksiMasuk::where('nama_id', $siswa->id)
            ->where('bulan_id', $request->bulan_id)
            ->with('bulan')
            ->firstOrFail();

        $ref_number = strtoupper($siswa->nis . '-' . $transaksi->bulan->nama_bulan . '-' . $transaksi->bulan->tahun);

        return view('bendahara.bukti_bayar', compact('siswa', 'transaksi', 'ref_number'));
    }
}
