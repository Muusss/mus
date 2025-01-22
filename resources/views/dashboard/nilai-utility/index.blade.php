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

        function utility_button() {
            Swal.fire({
                title: 'Nilai Utility',
                text: "Menghitung nilai utility",
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
                        url: "{{ route("nilai-utility.perhitungan") }}",
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
            {{-- Awal Tabel Nilai Utility --}}
            <div class="relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl dark:bg-white dark:shadow-spanish-white/20">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold text-affair dark:text-zambezi">Tabel {{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <button class="mb-0 inline-block cursor-pointer rounded-lg border border-solid border-success bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal tracking-tight text-success shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 active:opacity-90 md:px-8 md:py-2" onclick="return utility_button()">
                            <i class="ri-add-line"></i>
                            Hitung Nilai Utility
                        </button>
                    </div>
                </div>
                <div class="flex-auto px-0 pb-2 pt-0">
                    <div class="overflow-x-auto p-0 px-6 pb-6">
                        <table id="myTable" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top" style="width: 100%;">
                            <thead class="align-bottom">
                                <tr class="bg-affair text-xs font-bold uppercase text-white dark:bg-zambezi dark:text-white">
                                    <th class="rounded-tl"></th>
                                    @foreach ($kriteria as $item)
                                        <th>
                                            {{ $item->kriteria }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatif as $item)
                                    <tr class="border-b border-affair bg-transparent dark:border-zambezi">
                                        <td>
                                            <p class="text-left align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                {{ $item->alternatif }}
                                            </p>
                                        </td>
                                        @if ($nilaiUtility->first())
                                            @foreach ($nilaiUtility->where("alternatif_id", $item->id) as $value)
                                                <td>
                                                    <p class="text-center align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                        @if ($value->nilai == 0)
                                                            {{ round($value->nilai, 3) }}
                                                        @elseif ($value->nilai == null)
                                                            -
                                                        @else
                                                            {{ round($value->nilai, 3) }}
                                                        @endif
                                                    </p>
                                                </td>
                                            @endforeach
                                        @else
                                            @foreach ($kriteria as $item)
                                                <td>
                                                    <p class="text-center align-middle text-base font-semibold leading-tight text-affair dark:text-zambezi">
                                                        -
                                                    </p>
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="w-fit overflow-x-auto">
                            <table class="table table-xs">
                                <tr>
                                    <td class="text-base font-semibold text-affair dark:text-zambezi">Keterangan:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-base text-affair dark:text-zambezi">* Pastikan setiap alternatif terisi semua pada menu penilaian</td>
                                    <td class="text-base text-affair dark:text-zambezi"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Nilai Utility --}}
        </div>
    </div>
@endsection
