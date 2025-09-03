<?php

namespace App\Mail;

use App\Models\Alternatif;
use App\Models\Periode;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NilaiSiswa extends Mailable
{
    use Queueable, SerializesModels;

    public $siswa;
    public $periode;
    public $kriteria;
    public $penilaian;
    public $nilaiAkhir;
    public $ranking;

    /**
     * Create a new message instance.
     */
    public function __construct(Alternatif $siswa, Periode $periode)
    {
        $this->siswa = $siswa;
        $this->periode = $periode;
        $this->kriteria = Kriteria::orderBy('kode')->get();
        
        // Get penilaian
        $this->penilaian = Penilaian::where('alternatif_id', $siswa->id)
            ->where('periode_id', $periode->id)
            ->with(['kriteria', 'subKriteria'])
            ->get()
            ->keyBy('kriteria_id');
        
        // Get nilai akhir
        $this->nilaiAkhir = NilaiAkhir::where('alternatif_id', $siswa->id)
            ->where('periode_id', $periode->id)
            ->first();
        
        // Calculate ranking
        if ($this->nilaiAkhir) {
            $this->ranking = NilaiAkhir::where('periode_id', $periode->id)
                ->whereHas('alternatif', function($q) use ($siswa) {
                    $q->where('kelas', $siswa->kelas);
                })
                ->where('total', '>', $this->nilaiAkhir->total)
                ->count() + 1;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Nilai Siswa Teladan - ' . $this->siswa->nama_siswa
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nilai-siswa',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}