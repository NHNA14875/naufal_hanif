<?php

namespace App\Events;

use App\Models\DaftarPoli;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable;

    public $idJadwal;
    public $nomor;

    public function __construct($idJadwal)
    {
        $this->idJadwal = $idJadwal;
        $this->nomor = DaftarPoli::nomorDilayani($idJadwal);
    }

    public function broadcastOn(): array
    {
        return [new Channel('antrian')];
    }

    public function broadcastAs(): string
    {
        return 'antrian.updated';
    }
}
