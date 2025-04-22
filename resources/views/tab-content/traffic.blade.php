<div class="nav nav-tabs custom-nav" id="inner-tab" role="tablist">
    <button class="nav-link active" id="inner-daily-traffic-tab" data-bs-toggle="tab" data-bs-target="#inner-daily-traffic"
        type="button" role="tab">daily-traffic</button>
    <button class="nav-link" id="inner-weekly-traffic-tab" data-bs-toggle="tab" data-bs-target="#inner-weekly-traffic"
        type="button" role="tab">weekly-traffic</button>
    <button class="nav-link" id="inner-monthly-traffic-tab" data-bs-toggle="tab" data-bs-target="#inner-monthly-traffic"
        type="button" role="tab">monthly-traffic</button>
</div>

<div class="tab-content">
    <div class="tab-pane fade show active mt-5" id="inner-daily-traffic" role="tabpanel"
        aria-labelledby="inner-daily-traffic-tab">
        <h5>Pintu masuk Today</h5>
        <div class="row">
            <div class="col-6">
                <canvas id="DailyPintuMasuk" height="300px"></canvas>
                <table id="DailyPintuMasukTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pintu Masuk</th>
                            <th>Nama Pintu Masuk</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-weekly-traffic" role="tabpanel" aria-labelledby="inner-weekly-traffic-tab">
        <h5>weekly-traffic Income</h5>
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-monthly-traffic" role="tabpanel" aria-labelledby="inner-monthly-traffic-tab">
        <h5>monthly-traffic Income</h5>
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
</div>

<script>
    const dailyTrafficURL = "{{ route('dailyTraffic') }}";
</script>

<script>
    $(document).ready(function() {
        // Initialize the DataTable for daily traffic
        const table = $('#DailyPintuMasukTable').DataTable({
            searching: false,
            paging: false,
            autoWidth: false,
            ordering: false,
            info: false,
            data: [],
            columns: [{
                    data: 'no'
                },
                {
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        $.ajax({
            url: dailyTrafficURL,
            method: 'GET',
            success: function(response) {
                const pintumasukToday = response.data[0].pm_current_period;
                const pintumasukYesterday = response.data[0].pm_last_period;




                const formattedRows = pintumasukToday.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));

                table.rows.add(formattedRows).draw();


                const labels = pintumasukToday.map(item => item.namapos);
                const pintumasukTodayMap = pintumasukToday.map(item => item.qty);
                console.log(pintumasukTodayMap);
                console.log(labels);


                function getRandomColor() {
                    const letters = '0123456789ABCDEF';
                    let color = '#';
                    for (let i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                }
                const barData = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukTodayMap,
                        backgroundColor: pintumasukTodayMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };
                const barConfig = {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };



                const ctx = document.getElementById('DailyPintuMasuk')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, barConfig);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching daily traffic data:', error);
            }
        });
    });
</script>
