@props([
    'alternatif',        // Collection<Model Alternatif>
    'kriteria',          // Collection<Model Kriteria>
    'data',              // Collection of stdClass|array: {alternatif_id, kriteria_id, nilai}
    'showTotal' => false // bool: tampilkan kolom total di ujung baris?
])

@php
    // Group sekali di awal agar akses cepat per alternatif
    $byAlt = $data instanceof \Illuminate\Support\Collection
        ? $data->groupBy('alternatif_id')
        : collect($data)->groupBy('alternatif_id');
@endphp

<tbody>
@foreach ($alternatif as $alt)
    @php
        $rows = $byAlt->get($alt->id, collect());

        // Siapkan map nilai per kriteria_id => nilai (null/number)
        // Supaya urutan kolom pasti mengikuti $kriteria
        $nilaiPerKrit = [];
        foreach ($kriteria as $krit) {
            $rec = $rows->firstWhere('kriteria_id', $krit->id);
            $nilaiPerKrit[$krit->id] = $rec->nilai ?? null;
        }

        // Hitung total (anggap null sebagai 0)
        $total = 0.0;
        foreach ($nilaiPerKrit as $v) {
            $total += ($v === null ? 0 : (float)$v);
        }
    @endphp

    <tr class="border-b border-primary-color bg-transparent dark:border-primary-color-dark">
        <td>
            <p class="text-left align-middle text-base font-semibold leading-tight text-primary-color dark:text-primary-color-dark">
                {{ $alt->nama_siswa }}
            </p>
        </td>

        @foreach ($kriteria as $krit)
            @php $v = $nilaiPerKrit[$krit->id]; @endphp
            <td class="text-center">
                <p class="align-middle text-base font-semibold leading-tight text-primary-color dark:text-primary-color-dark">
                    {{ $v === null ? '-' : number_format((float)$v, 3) }}
                </p>
            </td>
        @endforeach

        @if($showTotal)
            <td class="text-center">
                <p class="align-middle text-base font-bold leading-tight text-primary-color dark:text-primary-color-dark">
                    {{ number_format($total, 3) }}
                </p>
            </td>
        @endif
    </tr>
@endforeach
</tbody>
