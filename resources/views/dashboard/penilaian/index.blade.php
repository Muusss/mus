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
                        url: "{{ route('normalisasi-bobot.perhitungan') }}",
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
                                    location.reload(); // Reload page to show updated normalisasi values
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
            });
        }

        function edit_button(alternatif_id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            @foreach ($kriteria as $item)
                $("#loading_edit_{{ $item->id }}").html(loading);
            @endforeach

            $.ajax({
                type: "get",
                url: "{{ route('penilaian.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "alternatif_id": alternatif_id
                },
                success: function(data) {
                    $("input[name='alternatif_id']").val(data[0].alternatif_id);
                    @foreach ($kriteria as $value => $item)
                        $("input[id='kriteria_id_{{ $item->id }}']").val(data[{{ $value }}].kriteria_id);
                        $("select[id='sub_kriteria_id_{{ $item->id }}']").val(data[{{ $value }}].sub_kriteria_id);
                    @endforeach

                    // Loading effect end
                    loading = "";
                    @foreach ($kriteria as $item)
                        $("#loading_edit_{{ $item->id }}").html(loading);
                    @endforeach
                }
            });
        }
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Penilaian --}}
            <div class="relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl dark:bg-white dark:shadow-secondary-color-dark/20">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold text-primary-color dark:text-primary-color-dark">Tabel {{ $title }}</h6>
                    <div class="w-1/2 max-w-full flex-none px-3 text-right">
                        <!-- Tombol Normalisasi Bobot -->
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
                                <tr class="bg-primary-color text-xs font-bold uppercase text-white dark:bg-primary-color-dark dark:text-white">
                                    <th class="rounded-tl"></th>
                                    @foreach ($kriteria as $item)
                                        <th>
                                            {{ $item->kriteria }}
                                        </th>
                                    @endforeach
                                    <th class="rounded-tr">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatif as $item)
                                    <tr class="border-b border-primary-color bg-transparent dark:border-primary-color-dark">
                                        <td>
                                            <p class="text-left align-middle text-base font-semibold leading-tight text-primary-color dark:text-primary-color-dark">
                                                {{ $item->alternatif }}
                                            </p>
                                        </td>
                                        @foreach ($penilaian->where("alternatif_id", $item->id) as $value)
                                            <td>
                                                <p class="text-center align-middle text-base font-semibold leading-tight text-primary-color dark:text-primary-color-dark">
                                                    @if ($value->sub_kriteria_id == null)
                                                        - 
                                                    @else
                                                        {{ $value->subKriteria->sub_kriteria }}
                                                    @endif
                                                </p>
                                            </td>
                                        @endforeach
                                        <td>
                                            <div class="text-center align-middle">
                                                <label for="edit_button" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button('{{ $item->id }}')">
                                                    <i class="ri-pencil-fill text-base"></i>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="w-fit overflow-x-auto">
                            <table class="table table-xs">
                                <tr>
                                    <td class="text-base font-semibold text-primary-color dark:text-primary-color-dark">Keterangan:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-base text-primary-color dark:text-primary-color-dark">* Pastikan setiap alternatif terisi semua</td>
                                    <td class="text-base text-primary-color dark:text-primary-color-dark"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Akhir Tabel Penilaian --}}
        </div>
    </div>
@endsection
