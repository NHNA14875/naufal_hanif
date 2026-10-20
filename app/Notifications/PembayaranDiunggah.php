<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Notifications\Notification;

// Dikirim ke DOKTER saat pasien mengupload bukti bayar
class PembayaranDiunggah extends Notification
{
    public function __construct(public Periksa $periksa)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'id_periksa' => $this->periksa->id,
            'pesan' => 'Pasien ' . $this->periksa->daftarPoli->pasien->nama . ' mengupload bukti pembayaran.',
        ];
    }
}
