<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Notifications\Notification;

// Dikirim ke PASIEN saat admin memverifikasi pembayaran (lunas)
class PembayaranDiverifikasi extends Notification
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
            'pesan' => 'Pembayaran Anda telah diverifikasi dan berstatus lunas.',
        ];
    }
}
