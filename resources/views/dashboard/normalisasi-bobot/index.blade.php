@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });
        });

        function normalisasi_button() {
            Swal.fire({
                title: 'Normalisasi Bobot',
                text: "Menghitung normalisasi bobot kriteria",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hitung',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("normalisasi-bobot.perhitungan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Perhitungan berhasil dilakukan!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                title: 'Perhitungan gagal dilakukan!',
                                icon: 'error',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            })
        }
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Normalisasi Bobot Kriteria --}}
            <div class="relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl dark:bg-white dark:shadow-spanish-white/20">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold text-affair dark:text-zambezi">Tabel {{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <button class="mb-0 inline-block cursor-pointer rounded-lg border border-solid border-success bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal tracking-tight text-success shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 active:opacity-90 md:px-8 md:py-2" onclick="return normalisasi_button()">
                            <i class="ri-add-fill"></i>
                            Normalisasi Bobot
                        </button>
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr class="bg-affair text-xs font-bold uppercase text-white dark:bg-zambezi dark:text-white">
                                    <th class="rounded-tl">
                                        Kode
                                    </th>
                                    <th>
                                        Nama Kriteria
                                    </th>
                                    <th>
                                        Bobot
                                    </th>
                                    <th class="rounded-tr">
                                        Normalisasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($normalisasiBobot as $item)
                                    <tr class="border-b border-affair bg-transparent dark:border-zambezi">
                                        <td>
                                            <p class="text-center align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                {{ $item->kriteria->kode }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-left align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                {{ $item->kriteria->kriteria }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-center align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                {{ $item->kriteria->bobot }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-center align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                {{ round($item->normalisasi, 3) }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <td></td>
                                <td class="text-right align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">Total:</td>
                                <td class="text-center align-middle text-base font-bold leading-tight text-affair dark:text-zambezi">
                                    @if ($normalisasiBobot->first())
                                        {{ $sumBobot }}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td class="text-center align-middle text-base font-bold leading-tight text-affair dark:text-zambezi">
                                    {{ $normalisasiBobot->sum("normalisasi") }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Normalisasi Bobot Kriteria --}}
        </div>
    </div>
@endsection
