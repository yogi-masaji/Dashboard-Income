$(document).ready(function() {
    function formatQuantity(quantity) {
        return new Intl.NumberFormat().format(quantity);
    }
        const table = $('#dailyQuantity').DataTable({
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
                    data: 'type'
                },
                {
                    data: 'yesterday'
                },
                {
                    data: 'today'
                }
            ]
        });
        const weeklyTable = $('#weeklyQuantity').DataTable({
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
                    data: 'vehicle'
                },
                {
                    data: 'last_week'
                },
                {
                    data: 'this_week'
                }
            ]
        });

        const monthlyTable = $('#monthlyQuantity').DataTable({
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
                    data: 'vehicle'
                },
                {
                    data: 'last_month'
                },
                {
                    data: 'this_month'
                }
            ]
        });


        const weeklyPassTable = $('#weeklyQuantityPass').DataTable({
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
                    data: 'vehicle'
                },
                {
                    data: 'last_week'
                },
                {
                    data: 'this_week'
                }
            ]
        });

        const monthlyPassTable = $('#monthlyQuantityPass').DataTable({
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
                    data: 'vehicle'
                },
                {
                    data: 'last_month'
                },
                {
                    data: 'this_month'
                }
            ]
        });

        $.ajax({
            url: dailyTransactionURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];

                const compare = response.vehicle_comparison;

                // console.log(compare);
            
                const container = $('#daily-transaction-comparison');

        container.empty(); // Biar gak dobel kalau dipanggil ulang

        const colClasses = ['col-md-6', 'col-md-6', 'col-md-12']; // atau sesuai jumlah compare

compare.forEach((vehicle, index) => {
    const col = colClasses[index] || 'col-md-4'; // fallback ke col-md-4
    const html = `
        <div class="${col}">
            <div class="dashboard-card">
                <div class="card-title" style="color: #000 !important;">${vehicle.type}</div>
                <div class="d-flex align-items-baseline">
                    <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.today)}</h2>
                    <span class="ms-2" style="color: ${vehicle.color}">
                        ${vehicle.percent_change} ${vehicle.direction}
                    </span>
                </div>
                <div class="yesterday" style="color: #000 !important;">Yesterday: ${formatQuantity(vehicle.yesterday)}</div>
            </div>
        </div>
    `;
    container.append(html);
});


                const rows = [{
                        type: 'Total Casual',
                        yesterday: formatQuantity(yesterday.grandcasual),
                        today: formatQuantity(today.grandcasual)
                    },
                    {
                        type: 'Total Pass',
                        yesterday: formatQuantity(yesterday.grandpass),
                        today: formatQuantity(today.grandpass)
                    }
                ];

                const formattedRows = rows.map((item, index) => ({
                    no: index + 1,
                    type: item.type,
                    yesterday: item.yesterday,
                    today: item.today
                }));

                table.rows.add(formattedRows).draw();

                $('#dailyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalYesterday">${formatQuantity(yesterday.grandtotal)}</th>
                            <th id="totalToday">${formatQuantity(today.grandtotal)}</th>
                        </tr>
                    `);

                const labels = ['Car', 'Motorbike', 'Truck', 'Taxi'];
                const casualData = [
                    today.carcasual,
                    today.motorbikecasual,
                    today.truckcasual,
                    today.taxicasual
                ];
                const passData = [
                    today.carpass,
                    today.motorbikepass,
                    today.truckpass,
                    today.taxipass
                ];

                const barData = {
                    labels: labels,
                    datasets: [{
                            label: 'Casual',
                            data: casualData,
                            backgroundColor: 'rgba(255, 0, 0, 1)',
                            borderColor: 'rgba(255, 0, 0, 1)',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        },
                        {
                            label: 'Pass',
                            data: passData,
                            backgroundColor: 'rgba(0, 132, 247, 1)',
                            borderColor: 'rgba(0, 132, 247, 1)',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        }
                    ]
                };

                const barConfig = {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },
                            

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0, 
                                    color: '#000'
                                },
                                grace: '10%',
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const lineData = {
                    labels: labels,
                    datasets: [{
                            label: 'Casual',
                            data: casualData,
                            borderColor: 'rgba(255, 0, 0, 1)',
                            backgroundColor: 'rgba(255, 0, 0, 1)',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            borderWidth: 3

                        },
                        {
                            label: 'Pass',
                            data: passData,
                            backgroundColor: 'rgba(0, 132, 247, 1)',
                            borderColor: 'rgba(0, 132, 247, 1)',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            borderWidth: 3
                        }
                    ]
                };

                const lineConfig = {
                    type: 'line',
                    data: lineData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                                
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            }, 
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                }
                const ctxLine = document.getElementById('dailyQuantityLine')?.getContext('2d');
                if (ctxLine) {
                    new Chart(ctxLine, lineConfig);
                }

                const ctx = document.getElementById('dailyQuantityBar')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, barConfig);
                }
            }
        });

        $.ajax({
            url: weeklyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisWeek = response.this_week.totals.casual;
                const lastWeek = response.last_week.totals.casual;
                const thisWeekPass = response.this_week.totals.pass;
                const lastWeekPass = response.last_week.totals.pass;
                const thisWeekChart = response.this_week.casual;
                const thisWeekPassChart = response.this_week.pass;
                const compare = response.vehicle_comparison;

            
                const container = $('#weekly-transaction-comparison');
const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12']; // bebas sesuai kebutuhan
container.empty();

compare.forEach((vehicle, index) => {
    const colClass = colClasses[index] || 'col-md-4'; // fallback
    const html = `
        <div class="${colClass} d-flex">
            <div class="dashboard-card flex-fill">
                <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                <div class="d-flex align-items-baseline">
                    <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.this_week)}</h2>
                    <span class="ms-2" style="color: ${vehicle.color}">
                        ${vehicle.percent_change} ${vehicle.direction}
                    </span>
                </div>
                <div class="yesterday" style="color: #000 !important;">Two Weeks Ago: ${formatQuantity(vehicle.two_weeks_ago)}</div>
            </div>
        </div>
    `;
    container.append(html);
});
                const rows = [{
                        type: 'Car',
                        thisWeek: thisWeek.total_car,
                        lastWeek: lastWeek.total_car
                    },
                    {
                        type: 'Motorbike',
                        thisWeek: thisWeek.total_motorbike,
                        lastWeek: lastWeek.total_motorbike
                    },
                    {
                        type: 'Truck',
                        thisWeek: thisWeek.total_truck,
                        lastWeek: lastWeek.total_truck
                    },
                    {
                        type: 'Taxi',
                        thisWeek: thisWeek.total_taxi,
                        lastWeek: lastWeek.total_taxi
                    }
                ];

                const formattedWeeklyRows = rows.map((item, index) => ({
                    no: index + 1,
                    vehicle: item.type,
                    this_week: formatQuantity(item.thisWeek),
                    last_week: formatQuantity(item.lastWeek)
                }));

                weeklyTable.rows.add(formattedWeeklyRows).draw();

                const rowsPass = [{
                    type: 'Car',
                    thisWeek: thisWeekPass.total_car,
                    lastWeek: lastWeek.total_car
                },
                {
                    type: 'Motorbike',
                    thisWeek: thisWeekPass.total_motorbike,
                    lastWeek: lastWeekPass.total_motorbike
                },
                {
                    type: 'Truck',
                    thisWeek: thisWeekPass.total_truck,
                    lastWeek: lastWeekPass.total_truck
                },
                {
                    type: 'Taxi',
                    thisWeek: thisWeekPass.total_taxi,
                    lastWeek: lastWeekPass.total_taxi
                }
            ];

            const formattedWeeklyRowsPass = rowsPass.map((item, index) => ({
                no: index + 1,
                vehicle: item.type,
                this_week: item.thisWeek,
                last_week: item.lastWeek
            }));

            weeklyPassTable.rows.add(formattedWeeklyRowsPass).draw();

                $('#weeklyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalLastWeek">${formatQuantity(lastWeek.total_vehicle)}</th>
                            <th id="totalThisWeek">${formatQuantity(thisWeek.total_vehicle)}</th>
                        </tr>
                    `);

                const labels = thisWeekChart.map(item => {
                    const date = new Date(item.tanggal);
                    return date.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short'
                    }); // hasilnya: 14 Apr, 15 Apr, dst.
                });

                const casualData = [
                    thisWeekChart.map(item => item.carcasual),
                    thisWeekChart.map(item => item.motorbikecasual),
                    thisWeekChart.map(item => item.taxicasual),
                    thisWeekChart.map(item => item.truckcasual),
                    thisWeekChart.map(item => item.othercasual),
                    thisWeekChart.map(item => item.vehiclecasual),
                    thisWeekChart.map(item => item.lostticketcasual),
                ];

                
                const passData = [
                    thisWeekPassChart.map(item => item.carpass),
                    thisWeekPassChart.map(item => item.motorbikepass),
                    thisWeekPassChart.map(item => item.taxipass),
                    thisWeekPassChart.map(item => item.truckpass),
                    thisWeekPassChart.map(item => item.otherpass),
                    thisWeekPassChart.map(item => item.vehiclepass),
                    thisWeekPassChart.map(item => item.lostticketcasual),
                ];


                const barData = {
                    labels: labels,
                    datasets: [{
                            label: 'Car',
                            data: casualData[0],
                            backgroundColor: '#0D61E2',
                            borderColor: '#0D61E2',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        }, {
                            label: 'Motorbike',
                            data: casualData[1],
                            backgroundColor: '#E60045',
                            borderColor: '#E60045',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: casualData[2],
                            backgroundColor: '#FFCD56',
                            borderColor: '#FFCD56',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: casualData[3],
                            backgroundColor: '#32CD7D',
                            borderColor: '#32CD7D',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: casualData[5],
                            backgroundColor: '#E69500',
                            borderColor: '#E69500',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }

                    ]
                };

                const barConfig = {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };
                const barPassData = {
                    labels: labels,
                    datasets: [{
                            label: 'Car',
                            data: passData[0],
                            backgroundColor: '#0D61E2',
                            borderColor: '#0D61E2',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        }, {
                            label: 'Motorbike',
                            data: passData[1],
                            backgroundColor: '#E60045',
                            borderColor: '#E60045',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: passData[2],
                            backgroundColor: '#FFCD56',
                            borderColor: '#FFCD56',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: passData[3],
                            backgroundColor: '#32CD7D',
                            borderColor: '#32CD7D',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: passData[5],
                            backgroundColor: '#E69500',
                            borderColor: '#E69500',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }

                    ]
                };

                const barPassConfig = {
                    type: 'bar',
                    data: barPassData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };
                const lineData = {
                    labels: labels,
                    datasets: [{
                            label: 'Car',
                            data: casualData[0],
                            backgroundColor: '#0D61E2',
                            borderColor: '#0D61E2',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,

                        }, {
                            label: 'Motorbike',
                            data: casualData[1],
                            backgroundColor: '#E60045',
                            borderColor: '#E60045',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: casualData[2],
                            backgroundColor: '#FFCD56',
                            borderColor: '#FFCD56',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: casualData[3],
                            backgroundColor: '#32CD7D',
                            borderColor: '#32CD7D',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: casualData[5],
                            backgroundColor: '#E69500',
                            borderColor: '#E69500',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }

                    ]
                };

                const lineConfig = {
                    type: 'line',
                    data: lineData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                }

                const linePassData = {
                    labels: labels,
                    datasets: [{
                            label: 'Car',
                            data: passData[0],
                            backgroundColor: '#0D61E2',
                            borderColor: '#0D61E2',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,

                        }, {
                            label: 'Motorbike',
                            data: passData[1],
                            backgroundColor: '#E60045',
                            borderColor: '#E60045',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: passData[2],
                            backgroundColor: '#FFCD56',
                            borderColor: '#FFCD56',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: passData[3],
                            backgroundColor: '#32CD7D',
                            borderColor: '#32CD7D',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: passData[5],
                            backgroundColor: '#E69500',
                            borderColor: '#E69500',
                            borderWidth: 3,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            tension: 0.5,
                            hidden: true,
                        }

                    ]
                };

                const linePassConfig = {
                    type: 'line',
                    data: linePassData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                }

                const ctxLine = document.getElementById('weeklyQuantityLine')?.getContext('2d');
                if (ctxLine) {
                    new Chart(ctxLine, lineConfig);
                }

                const ctx = document.getElementById('weeklyQuantityBar')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, barConfig);
                }
                const ctxPass = document.getElementById('weeklyPassQuantityBar')?.getContext('2d');
                if (ctxPass) {
                    new Chart(ctxPass, barPassConfig);
                }
                const ctxPassLine = document.getElementById('weeklyPassQuantityLine')?.getContext('2d');
                if (ctxPassLine) {
                    new Chart(ctxPassLine, linePassConfig);
                }
            }
        });

        $.ajax({
            url: monthlyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisMonth = response.this_month.totals.casual;
                const lastMonth = response.last_month.totals.casual;
                const thisMonthPass = response.this_month.totals.pass;
                const lastMonthPass = response.last_month.totals.pass;
                const compare = response.vehicle_comparison;

                
                const container = $('#monthly-transaction-comparison');
                const colClasses = ['col-md-6', 'col-md-6','col-md-6', 'col-md-6', 'col-md-12'];
                container.empty(); // Biar gak dobel kalau dipanggil ulang

        compare.forEach((vehicle, index) => {
            const html = `
                <div class="${colClasses[index]}">
                    <div class="dashboard-card">
                        <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.this_month)}</h2>
                            <span class="ms-2" style="color: ${vehicle.color}">
                                ${vehicle.percent_change}
                                ${vehicle.direction}
                            </span>
                        </div>
                        <div class="yesterday" style="color: #000 !important;">Two Months Ago: ${formatQuantity(vehicle.two_months_ago)}</div>
                    </div>
                </div>
            `;
            container.append(html);
        });
                
                const rows = [{
                        type: 'Car',
                        thisMonth: thisMonth.total_car,
                        lastMonth: lastMonth.total_car
                    },
                    {
                        type: 'Motorbike',
                        thisMonth: thisMonth.total_motorbike,
                        lastMonth: lastMonth.total_motorbike
                    },
                    {
                        type: 'Truck',
                        thisMonth: thisMonth.total_truck,
                        lastMonth: lastMonth.total_truck
                    },
                    {
                        type: 'Taxi',
                        thisMonth: thisMonth.total_taxi,
                        lastMonth: lastMonth.total_taxi
                    }
                ];

                const formattedMonthlyRows = rows.map((item, index) => ({
                    no: index + 1,
                    vehicle: item.type,
                    this_month: formatQuantity(item.thisMonth),
                    last_month: formatQuantity(item.lastMonth)
                }));

                
            monthlyTable.rows.add(formattedMonthlyRows).draw();

            const rowsPass = [{
                type: 'Car',
                thisMonth: thisMonthPass.total_car,
                lastMonth: lastMonthPass.total_car
            },
            {
                type: 'Motorbike',
                thisMonth: thisMonthPass.total_motorbike,
                lastMonth: lastMonthPass.total_motorbike
            },
            {
                type: 'Truck',
                thisMonth: thisMonthPass.total_truck,
                lastMonth: lastMonthPass.total_truck
            },
            {
                type: 'Taxi',
                thisMonth: thisMonthPass.total_taxi,
                lastMonth: lastMonthPass.total_taxi
            }
        ];

        const formattedMonthlyRowsPass = rowsPass.map((item, index) => ({
            no: index + 1,
            vehicle: item.type,
            this_month: item.thisMonth,
            last_month: item.lastMonth
        }));
            monthlyPassTable.rows.add(formattedMonthlyRowsPass).draw();

                $('#monthlyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalLastMonth">${formatQuantity(lastMonth.total_vehicle)}</th>
                            <th id="totalThisMonth">${formatQuantity(thisMonth.total_vehicle)}</th>
                        </tr>
                    `);

                const thisMonthChart = response.this_month.weekly_totals.casual;
                const thisMonthPassChart = response.this_month.weekly_totals.pass;
                const labels = Object.keys(thisMonthChart);

                const totalCars = Object.values(thisMonthChart).map(week => week
                    .total_car);
                const totalMotorbikes = Object.values(thisMonthChart).map(week => week
                    .total_motorbike);
                const totalTrucks = Object.values(thisMonthChart).map(week => week
                    .total_truck);
                const totalTaxis = Object.values(thisMonthChart).map(week => week
                    .total_taxi);
                const totalVehicles = Object.values(thisMonthChart).map(week => week
                    .total_vehicle);

                    const totalPassCars = Object.values(thisMonthPassChart).map(week => week
                        .total_car);
                    const totalPassMotorbikes = Object.values(thisMonthPassChart).map(week => week
                        .total_motorbike);
                    const totalPassTrucks = Object.values(thisMonthPassChart).map(week => week
                        .total_truck);
                    const totalPassTaxis = Object.values(thisMonthPassChart).map(week => week
                        .total_taxi);
                    const totalPassVehicles = Object.values(thisMonthPassChart).map(week => week
                        .total_vehicle);
    

                const barData = {
                    labels: labels,
                    datasets: [{
                            label: 'Car',
                            data: totalCars,
                            backgroundColor: '#51AA20',
                            borderColor: '#51AA20',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        }, {
                            label: 'Motorbike',
                            data: totalMotorbikes,
                            backgroundColor: '#DB6715',
                            borderColor: '#DB6715',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: totalTrucks,
                            backgroundColor: '#8D60ED',
                            borderColor: '#8D60ED',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: totalTaxis,
                            backgroundColor: '#C46EA6',
                            borderColor: '#C46EA6',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: totalVehicles,
                            backgroundColor: '#D3D6DD',
                            borderColor: '#D3D6DD',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }

                    ]
                };

                const barConfig = {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barPassData = {
                    labels: labels,
                    datasets: [{
                            label: 'Car',
                            data: totalPassCars,
                            backgroundColor: '#51AA20',
                            borderColor: '#51AA20',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        }, {
                            label: 'Motorbike',
                            data: totalPassMotorbikes,
                            backgroundColor: '#DB6715',
                            borderColor: '#DB6715',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: totalPassTrucks,
                            backgroundColor: '#8D60ED',
                            borderColor: '#8D60ED',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: totalPassTaxis,
                            backgroundColor: '#C46EA6',
                            borderColor: '#C46EA6',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: totalPassVehicles,
                            backgroundColor: '#D3D6DD',
                            borderColor: '#D3D6DD',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }

                    ]
                };

                const barPassConfig = {
                    type: 'bar',
                    data: barPassData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };


                const lineData = {
                    labels: labels,
                    datasets: [{
                        label: 'Car',
                        data: totalCars,
                        backgroundColor: '#51AA20',
                        borderColor: '#51AA20',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,

                    }, {
                        label: 'Motorbike',
                        data: totalMotorbikes,
                        backgroundColor: '#DB6715',
                        borderColor: '#DB6715',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }, {
                        label: 'Truck',
                        data: totalTrucks,
                        backgroundColor: '#8D60ED',
                        borderColor: '#8D60ED',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }, {
                        label: 'Taxi',
                        data: totalTaxis,
                        backgroundColor: '#C46EA6',
                        borderColor: '#C46EA6',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,

                    }, {
                        label: 'Vehicle',
                        data: totalVehicles,
                        backgroundColor: '#D3D6DD',
                        borderColor: '#D3D6DD',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }]
                };

                const lineConfig = {
                    type: 'line',
                    data: lineData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                }

                const linePassData = {
                    labels: labels,
                    datasets: [{
                        label: 'Car',
                        data: totalPassCars,
                        backgroundColor: '#51AA20',
                        borderColor: '#51AA20',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,

                    }, {
                        label: 'Motorbike',
                        data: totalPassMotorbikes,
                        backgroundColor: '#DB6715',
                        borderColor: '#DB6715',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }, {
                        label: 'Truck',
                        data: totalPassTrucks,
                        backgroundColor: '#8D60ED',
                        borderColor: '#8D60ED',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }, {
                        label: 'Taxi',
                        data: totalPassTaxis,
                        backgroundColor: '#C46EA6',
                        borderColor: '#C46EA6',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,

                    }, {
                        label: 'Vehicle',
                        data: totalPassVehicles,
                        backgroundColor: '#D3D6DD',
                        borderColor: '#D3D6DD',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }]
                };

                const linePassConfig = {
                    type: 'line',
                    data: linePassData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#000',

                                }
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#000'
                                },
                                grace: '10%'
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                }

                const ctxLine = document.getElementById('monthlyQuantityLine')?.getContext(
                    '2d');
                if (ctxLine) {
                    new Chart(ctxLine, lineConfig);
                }
                const ctx = document.getElementById('monthlyQuantityBar')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, barConfig);
                }
                const ctxPassLine = document.getElementById('monthlyPassQuantityLine')?.getContext(
                    '2d');
                if (ctxPassLine) {
                    new Chart(ctxPassLine, linePassConfig);
                }
                const ctxPass = document.getElementById('monthlyPassQuantityBar')?.getContext('2d');
                if (ctxPass) {
                    new Chart(ctxPass, barPassConfig);
                }
            }
        });
    });

