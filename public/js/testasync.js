let tableTransaction, weeklyTable, monthlyTable, weeklyPassTable, monthlyPassTable;
$(document).ready(function() {
    tableTransaction = $('#dailyQuantity').DataTable({
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
    weeklyTable = $('#weeklyQuantity').DataTable({
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

    monthlyTable = $('#monthlyQuantity').DataTable({
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


    weeklyPassTable = $('#weeklyQuantityPass').DataTable({
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

    monthlyPassTable = $('#monthlyQuantityPass').DataTable({
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
    runAllFetch();

    // Set interval to run every 5 seconds
    setInterval(runAllFetch, 5000);
});


async function runAllFetch() {
    try{
        await dailyData();
        await weeklyData();
        await monthlyData();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

async function dailyData() {
    try{
        $.ajax({
            url: dailyTransactionURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];

                const compare = response.vehicle_comparison;
                const formatQuantity = val => parseInt(val).toLocaleString('id-ID');

                // console.log(compare);
            
                const container = $('#daily-transaction-comparison');

        container.empty(); // Clear container to avoid duplication

        compare.forEach(vehicle => {
            const html = `
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-title">${vehicle.type}</div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="card-value">${vehicle.today}</h2>
                            <span class="ms-2" style="color: ${vehicle.color}">
                                ${vehicle.percent_change}
                                ${vehicle.direction}
                            </span>
                        </div>
                        <div class="yesterday">Yesterday: ${vehicle.yesterday}</div>
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
                tableTransaction.clear().draw();
                tableTransaction.rows.add(formattedRows).draw();

                $('#dailyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalYesterday">${formatQuantity(yesterday.grandtotal)}</th>
                            <th id="totalToday">${formatQuantity(today.grandtotal)}</th>
                        </tr>
                    `);

                const labels = ['Car', 'Motorbike', 'Truck', 'Taxi', 'Other'];
                const casualData = [
                    today.carcasual,
                    today.motorbikecasual,
                    today.truckcasual,
                    today.taxicasual,
                    today.othercasual
                ];
                const passData = [
                    today.carpass,
                    today.motorbikepass,
                    today.truckpass,
                    today.taxipass,
                    today.otherpass
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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
                                    color: '#000'
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
                                formatter: formatQuantity,
                                padding: 3,
                                offset: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,color: '#fff'
                                },
                                grace: '10%'
                            },x: {
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
                const ctxLineDailyQuantity = document.getElementById('dailyQuantityLine')?.getContext('2d');
                if (ctxLineDailyQuantity) {
                    new Chart(ctxLineDailyQuantity, lineConfig);
                }

                const ctxBarDailyQuantity = document.getElementById('dailyQuantityBar')?.getContext('2d');
                if (ctxBarDailyQuantity) {
                    new Chart(ctxBarDailyQuantity, barConfig);
                }
            }
        });
    }
    catch (error) {
        console.error('Error fetching daily data:', error);
    }
}

async function weeklyData(){
    try{
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

                const formatQuantity = val => parseInt(val).toLocaleString('id-ID');
                const container = $('#weekly-transaction-comparison');

        container.empty(); // Clear container to avoid duplication

        compare.forEach(vehicle => {
            const html = `
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="card-title">${vehicle.vehicle}</div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="card-value">${vehicle.this_week}</h2>
                            <span class="ms-2" style="color: ${vehicle.color}">
                                ${vehicle.percent_change}
                                ${vehicle.direction}
                            </span>
                        </div>
                        <div class="yesterday">Two Weeks Ago: ${vehicle.last_week}</div>
                    </div>
                </div>
            `;
            container.append(html);
        });
                const rows = [{
                        type: 'Car',
                        thisWeek: formatQuantity(thisWeek.total_car),
                        lastWeek: formatQuantity(lastWeek.total_car)
                    },
                    {
                        type: 'Motorbike',
                        thisWeek: formatQuantity(thisWeek.total_motorbike),
                        lastWeek: formatQuantity(lastWeek.total_motorbike)
                    },
                    {
                        type: 'Truck',
                        thisWeek: formatQuantity(thisWeek.total_truck),
                        lastWeek: formatQuantity(lastWeek.total_truck)
                    },
                    {
                        type: 'Taxi',
                        thisWeek: formatQuantity(thisWeek.total_taxi),
                        lastWeek: formatQuantity(lastWeek.total_taxi)
                    },
                    {
                        type: 'Other',
                        thisWeek: formatQuantity(thisWeek.total_other),
                        lastWeek: formatQuantity(lastWeek.total_other)
                    }
                ];

                const formattedWeeklyRows = rows.map((item, index) => ({
                    no: index + 1,
                    vehicle: item.type,
                    this_week: item.thisWeek,
                    last_week: item.lastWeek
                }));
                weeklyTable.clear().draw();
                weeklyTable.rows.add(formattedWeeklyRows).draw();

                const rowsPass = [{
                    type: 'Car',
                    thisWeek: formatQuantity(thisWeekPass.total_car),
                    lastWeek: formatQuantity(lastWeekPass.total_car)
                },
                {
                    type: 'Motorbike',
                    thisWeek: formatQuantity(thisWeekPass.total_motorbike),
                    lastWeek: formatQuantity(lastWeekPass.total_motorbike)
                },
                {
                    type: 'Truck',
                    thisWeek: formatQuantity(thisWeekPass.total_truck),
                    lastWeek: formatQuantity(lastWeekPass.total_truck)
                },
                {
                    type: 'Taxi',
                    thisWeek: formatQuantity(thisWeekPass.total_taxi),
                    lastWeek: formatQuantity(lastWeekPass.total_taxi)
                },
                {
                    type: 'Other',
                    thisWeek: formatQuantity(thisWeekPass.total_other),
                    lastWeek: formatQuantity(lastWeekPass.total_other)
                }
            ];

            const formattedWeeklyRowsPass = rowsPass.map((item, index) => ({
                no: index + 1,
                vehicle: item.type,
                this_week: item.thisWeek,
                last_week: item.lastWeek
            }));
                weeklyPassTable.clear().draw();
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
                    }); // e.g.: 14 Apr, 15 Apr
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
                        },{
                            label: 'Other',
                            data: casualData[4],
                            backgroundColor: '#720049ff',
                            borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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
                        },{
                            label: 'Other',
                            data: passData[4],
                            backgroundColor: '#720049ff',
                            borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: "#fff",
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
                        },{
                            label: 'Other',
                            data: casualData[4],
                            backgroundColor: '#720049ff',
                            borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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
                        },{
                            label: 'Other',
                            data: passData[4],
                            backgroundColor: '#720049ff',
                            borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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

                const ctxLineWeeklyQuantityLine = document.getElementById('weeklyQuantityLine')?.getContext('2d');
                if (ctxLineWeeklyQuantityLine) {
                    new Chart(ctxLineWeeklyQuantityLine, lineConfig);
                }

                const ctxWeeklyQuantityBar = document.getElementById('weeklyQuantityBar')?.getContext('2d');
                if (ctxWeeklyQuantityBar) {
                    new Chart(ctxWeeklyQuantityBar, barConfig);
                }
                const ctxWeeklyPassQuantityBar = document.getElementById('weeklyPassQuantityBar')?.getContext('2d');
                if (ctxWeeklyPassQuantityBar) {
                    new Chart(ctxWeeklyPassQuantityBar, barPassConfig);
                }
                const ctxWeeklyPassQuantityLine = document.getElementById('weeklyPassQuantityLine')?.getContext('2d');
                if (ctxWeeklyPassQuantityLine) {
                    new Chart(ctxWeeklyPassQuantityLine, linePassConfig);
                }
            }
        });
    }catch (error) {
        console.error('Error fetching weekly data:', error);
    }
}
   

async function monthlyData(){
    try{
        $.ajax({
            url: monthlyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisMonth = response.this_month.totals.casual;
                const lastMonth = response.last_month.totals.casual;
                const thisMonthPass = response.this_month.totals.pass;
                const lastMonthPass = response.last_month.totals.pass;
                const compare = response.vehicle_comparison;

                const formatQuantity = val => parseInt(val).toLocaleString('id-ID');
                const container = $('#monthly-transaction-comparison');

        container.empty(); // Clear container to avoid duplication

        compare.forEach(vehicle => {
            const html = `
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="card-title">${vehicle.vehicle}</div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="card-value">${vehicle.this_month}</h2>
                            <span class="ms-2" style="color: ${vehicle.color}">
                                ${vehicle.percent_change}
                                ${vehicle.direction}
                            </span>
                        </div>
                        <div class="yesterday">Two Months Ago: ${vehicle.last_month}</div>
                    </div>
                </div>
            `;
            container.append(html);
        });
                
                const rows = [{
                        type: 'Car',
                        thisMonth: formatQuantity(thisMonth.total_car),
                        lastMonth: formatQuantity(lastMonth.total_car)
                    },
                    {
                        type: 'Motorbike',
                        thisMonth: formatQuantity(thisMonth.total_motorbike),
                        lastMonth: formatQuantity(lastMonth.total_motorbike)
                    },
                    {
                        type: 'Truck',
                        thisMonth: formatQuantity(thisMonth.total_truck),
                        lastMonth: formatQuantity(lastMonth.total_truck)
                    },
                    {
                        type: 'Taxi',
                        thisMonth: formatQuantity(thisMonth.total_taxi),
                        lastMonth: formatQuantity(lastMonth.total_taxi)
                    },
                    {
                        type: 'Other',
                        thisMonth: formatQuantity(thisMonth.total_other),
                        lastMonth: formatQuantity(lastMonth.total_other)
                    }
                ];

                const formattedMonthlyRows = rows.map((item, index) => ({
                    no: index + 1,
                    vehicle: item.type,
                    this_month: item.thisMonth,
                    last_month: item.lastMonth
                }));

                monthlyTable.clear().draw();
            monthlyTable.rows.add(formattedMonthlyRows).draw();

            const rowsPass = [{
                type: 'Car',
                thisMonth: formatQuantity(thisMonthPass.total_car),
                lastMonth: formatQuantity(lastMonthPass.total_car)
            },
            {
                type: 'Motorbike',
                thisMonth: formatQuantity(thisMonthPass.total_motorbike),
                lastMonth: formatQuantity(lastMonthPass.total_motorbike)
            },
            {
                type: 'Truck',
                thisMonth: formatQuantity(thisMonthPass.total_truck),
                lastMonth: formatQuantity(lastMonthPass.total_truck)
            },
            {
                type: 'Taxi',
                thisMonth: formatQuantity(thisMonthPass.total_taxi),
                lastMonth: formatQuantity(lastMonthPass.total_taxi)
            },
            {
                type: 'Other',
                thisMonth: formatQuantity(thisMonthPass.total_other),
                lastMonth: formatQuantity(lastMonthPass.total_other)
            }
        ];

        const formattedMonthlyRowsPass = rowsPass.map((item, index) => ({
            no: index + 1,
            vehicle: item.type,
            this_month: item.thisMonth,
            last_month: item.lastMonth
        }));
                monthlyPassTable.clear().draw();
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
                    const totalPassOther = Object.values(thisMonthPassChart).map(week => week
                        .total_other);
                const totalLostTicket = Object.values(thisMonthChart).map(week => week
                    .total_lostticket);
    

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
                        },{
                            label: 'Other',
                            data: totalPassOther,
                            backgroundColor: '#720049ff',
                            borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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
                        },{
                            label: 'Other',
                            data: totalPassOther,
                            backgroundColor: '#720049ff',
                            borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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

                    },{
                        label: 'Other',
                        data: totalPassOther,
                        backgroundColor: '#720049ff',
                        borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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

                    },{
                        label: 'Other',
                        data: totalPassOther,
                        backgroundColor: '#720049ff',
                        borderColor: '#720049ff',
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
                                    color: '#000'
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
                                formatter: formatQuantity,
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

                const ctxLineMonthlyQuantityLine = document.getElementById('monthlyQuantityLine')?.getContext(
                    '2d');
                if (ctxLineMonthlyQuantityLine) {
                    new Chart(ctxLineMonthlyQuantityLine, lineConfig);
                }
                const ctxmonthlyQuantityBar = document.getElementById('monthlyQuantityBar')?.getContext('2d');
                if (ctxmonthlyQuantityBar) {
                    new Chart(ctxmonthlyQuantityBar, barConfig);
                }
                const ctxmonthlyPassQuantityLine = document.getElementById('monthlyPassQuantityLine')?.getContext(
                    '2d');
                if (ctxmonthlyPassQuantityLine) {
                    new Chart(ctxmonthlyPassQuantityLine, linePassConfig);
                }
                const ctxmonthlyPassQuantityBar = document.getElementById('monthlyPassQuantityBar')?.getContext('2d');
                if (ctxmonthlyPassQuantityBar) {
                    new Chart(ctxmonthlyPassQuantityBar, barPassConfig);
                }
            }
        });
    }catch (error) {
        console.error('Error fetching monthly data:', error);
    }
}
