<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Minuman;

class ResetStokMinuman extends Command
{
    protected $signature = 'minuman:reset-stok';
    protected $description = 'Tambahkan stok hari ini dengan stok besok, lalu kosongkan stok besok';

    public function handle()
    {
        $minumans = Minuman::all();

        foreach($minumans as $minuman){
            $minuman->stok_hari_ini += $minuman->stok_besok;
            $minuman->stok_besok = 0;
            $minuman->save();
        }

        $this->info('✅ Stok hari ini telah ditambahkan dengan stok besok, dan stok besok dikosongkan.');
    }
}
