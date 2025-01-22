@extends("dashboard.layouts.main")

@section("js")
    <script>
        let alternatif = [];
        let nilaiAkhir = [];
        @foreach ($nilaiAkhir as $item)
            alternatif.push(' {{ $item->alternatif->alternatif }} ');
            nilaiAkhir.push(' {{ round($item->nilai, 3) }} ');
        @endforeach

        let chart_perankingan = {
            chart: {
                height: 300,
                type: "line",
                stacked: true
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                data: nilaiAkhir
            }, ],
            stroke: {
                curve: 'smooth',
                width: 4,
            },
            marker: {
                size: 10,
            },
            colors: ["#665551"],
            xaxis: {
                categories: alternatif,
                axisTicks: {
                    show: true
                },
                axisBorder: {
                    show: true,
                    color: "#77547C"
                },
                labels: {
                    style: {
                        colors: "#77547C"
                    }
                },
                title: {
                    text: "Alternatif",
                    style: {
                        color: "#665551"
                    }
                }
            },
            yaxis: [{
                axisTicks: {
                    show: true
                },
                axisBorder: {
                    show: true,
                    color: "#77547C"
                },
                labels: {
                    style: {
                        colors: "#77547C"
                    }
                },
                title: {
                    text: "Nilai",
                    style: {
                        color: "#665551"
                    }
                }
            }, ],
            tooltip: {
                enabled: true,
                shared: false,
                followCursor: false,
                x: {
                    show: false,
                },
                y: {
                    formatter: undefined,
                    title: {
                        formatter: (seriesName) => "",
                    },
                },
            },
        };

        var chart1 = new ApexCharts(document.querySelector("#chart-perankingan"), chart_perankingan);
        chart1.render();
    </script>
@endsection

@section("container")
    <div>
        <!-- row 1 -->
        <div class="-mx-3 mb-5 flex flex-wrap">
            <!-- Kriteria -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                <div class="relative flex min-w-0 flex-col break-words rounded-2xl bg-pale-pink bg-clip-border shadow-md dark:bg-spanish-white dark:shadow-spanish-white/20">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-zambezi">Kriteria</p>
                                    <h5 class="mb-2 font-bold text-zambezi dark:text-white">{{ $jmlKriteria }}</h5>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-affair to-pale-pink text-center dark:bg-gradient-to-tl dark:from-zambezi dark:to-spanish-white">
                                    <i class="ri-puzzle-line relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Kriteria -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                <div class="relative flex min-w-0 flex-col break-words rounded-2xl bg-pale-pink bg-clip-border shadow-md dark:bg-spanish-white dark:shadow-spanish-white/20">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-zambezi">Sub Kriteria</p>
                                    <h5 class="mb-2 font-bold text-zambezi dark:text-white">{{ $jmlSubKriteria }}</h5>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-affair to-pale-pink text-center dark:bg-gradient-to-tl dark:from-zambezi dark:to-spanish-white">
                                    <i class="ri-puzzle-2-fill relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alternatif -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
                <div class="relative flex min-w-0 flex-col break-words rounded-2xl bg-pale-pink bg-clip-border shadow-md dark:bg-spanish-white dark:shadow-spanish-white/20">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-zambezi">Alternatif</p>
                                    <h5 class="mb-2 font-bold text-zambezi dark:text-white">{{ $jmlAlternatif }}</h5>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-affair to-pale-pink text-center dark:bg-gradient-to-tl dark:from-zambezi dark:to-spanish-white">
                                    <i class="ri-survey-line relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- row 2 -->
        <div class="-mx-3 mb-5 flex flex-wrap">
            <!-- SPK -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0">
                <div class="min-w-0 rounded-lg bg-white bg-clip-border p-4 shadow-md dark:shadow-spanish-white/20">
                    <h4 class="mb-4 font-semibold text-affair dark:text-zambezi">
                        Sistem Pendukung Keputusan
                    </h4>
                    <p class="mb-3 text-justify text-zambezi dark:text-affair">
                        SMART merupakan metode pengambilan keputusan multi kriteria yang didasarkan pada setiap alternatif,
                        yang terdiri dari sejumlah kriteria yang memiliki nilai dan diberi bobot untuk menunjukkan
                        seberapa penting setiap kriteria dibandingkan dengan kriteria lainnya.
                    </p>
                    <a class="group text-sm font-semibold leading-normal text-affair dark:text-zambezi" href="{{ route("kriteria") }}">
                        Mulai
                        <i class="ri-arrow-right-line ease-bounce group-hover:translate-x-1.25 ml-1 text-sm leading-normal transition-all duration-200"></i>
                    </a>
                </div>
            </div>

            <!-- Manfaat -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0">
                <div class="min-w-0 rounded-lg bg-affair p-4 text-white shadow-md dark:bg-spanish-white dark:shadow-spanish-white/20">
                    <h4 class="mb-4 font-semibold text-pale-pink dark:text-zambezi">
                        Kegunaan SMART (Simple Multi Attribute Rating Tachnique):
                    </h4>
                    <ul style="list-style-type: square;" class="mx-5 mb-3 text-white dark:text-white">
                        <li>kesederhanaannya dalam merespon kebutuhan pembuat keputusan dan menganalisa responnya.</li>
                        <li>Analisa yang digunakan jelas sehingga metode ini memberikan pemahaman masalah yang kuat dan dapat diterima oleh pembuat keputusan.</li>
                        <li>Setiap penilaian pilihan tidak bergantung satu sama lain, metode SMART ini tidak akan mempengaruhi perhitungan pembobotan dengan menambah atau mengurangi pilihan.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- row 4 -->
        <div class="-mx-3 mt-6 flex flex-wrap gap-y-2">
            <div class="mt-0 w-full max-w-full px-3 lg:flex-none">
                <div class="dark:shadow-akaroa/20 relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-affair/10 bg-white bg-clip-border shadow-xl">
                    <div class="mb-0 rounded-t-2xl border-b-0 border-solid border-affair/10 p-6 pb-0 pt-4">
                        <h6 class="font-semibold capitalize text-affair dark:text-zambezi">Hasil Perhitungan SMART</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <div id="chart-perankingan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
