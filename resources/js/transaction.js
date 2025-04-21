$(document).ready(function() {
      
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

        $.ajax({
            url: `http://110.0.100.70:8080/v3/api/daily-quantity?location_code=${kodeLokasi}`,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];

                const rows = [{
                        type: 'Total Casual',
                        yesterday: yesterday.grandcasual,
                        today: today.grandcasual
                    },
                    {
                        type: 'Total Pass',
                        yesterday: yesterday.grandpass,
                        today: today.grandpass
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
                            <th id="totalYesterday">${yesterday.grandtotal}</th>
                            <th id="totalToday">${today.grandtotal}</th>
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
                                formatter: Math.round,
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
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
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
            url: "{{ route('weeklyTransaction') }}",
            method: 'GET',
            success: function(response) {
                const thisWeek = response.this_week.totals.casual;
                const lastWeek = response.last_week.totals.casual;
                const thisWeekChart = response.this_week.casual;

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
                    this_week: item.thisWeek,
                    last_week: item.lastWeek
                }));

                weeklyTable.rows.add(formattedWeeklyRows).draw();

                $('#weeklyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalLastWeek">${lastWeek.total_vehicle}</th>
                            <th id="totalThisWeek">${thisWeek.total_vehicle}</th>
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
                                formatter: Math.round,
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
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
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
                }

                const ctxLine = document.getElementById('weeklyQuantityLine')?.getContext('2d');
                if (ctxLine) {
                    new Chart(ctxLine, lineConfig);
                }

                const ctx = document.getElementById('weeklyQuantityBar')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, barConfig);
                }
            }
        });

        $.ajax({
            url: "{{ route('monthlyTransaction') }}",
            method: 'GET',
            success: function(response) {
                const thisMonth = response.this_month.totals.casual;
                const lastMonth = response.last_month.totals.casual;

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
                    this_month: item.thisMonth,
                    last_month: item.lastMonth
                }));

                monthlyTable.rows.add(formattedMonthlyRows).draw();

                $('#monthlyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalLastMonth">${lastMonth.total_vehicle}</th>
                            <th id="totalThisMonth">${thisMonth.total_vehicle}</th>
                        </tr>
                    `);

                const thisMonthChart = response.this_month.weekly_totals.casual;

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
                                formatter: Math.round,
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
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
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

            }
        });
    });

