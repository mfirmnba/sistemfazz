<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Minuman;
use Illuminate\Http\Request;

class MinumanController extends Controller
{
    public function edit($id)
    {
        $minuman = Minuman::findOrFail($id);
        return view('owner.minuman.edit', compact('minuman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hpp' => 'required|numeric|min:0',
        ]);

        $minuman = Minuman::findOrFail($id);

        // Hitung keuntungan & margin
        $hpp = $request->hpp;
        $harga = $minuman->harga;

        $keuntungan = $harga - $hpp;
        $margin = $hpp > 0 ? ($keuntungan / $harga) * 100 : 0;

        // Update ke database
        $minuman->update([
            'hpp' => $hpp,
            'keuntungan' => $keuntungan,
            'margin' => $margin,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'HPP berhasil diperbarui!');
    }
}
