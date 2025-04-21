$(document).ready(function() {

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };
    const table = $('#dailyE-Payment').DataTable({
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
                data: 'payment'
            },
            {
                data: 'yesterday'
            },
            {
                data: 'today'
            }
        ]
    });

    const weeklyTable = $('#weeklyE-Payment').DataTable({
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
                data: 'payment'
            },
            {
                data: 'last_week'
            },
            {
                data: 'this_week'
            }
        ]
    });

    const monthlyTable = $('#monthlyE-Payment').DataTable({
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
                data: 'payment'
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
        url: dailyEpaymentURL,
        method: 'GET',
        success: function(response) {
            const today = response.data[0].today[0];
            const yesterday = response.data[0].yesterday[0];
            const compare = response.table_data;
            // console.log(compare);

            const container = $('#daily-epayment-comparison');

            container.empty(); // Biar gak dobel kalau dipanggil ulang
    
            compare.forEach(payment => {
                const html = `
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <div class="card-title">${payment.method}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value">${formatRupiah(payment.today)}</h2>
                                <span class="ms-2" style="color: ${payment.color}">
                                    ${payment.percent_change}
                                    ${payment.direction}
                                </span>
                            </div>
                            <div class="yesterday">Yesterday: ${formatRupiah(payment.yesterday)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });
    
            const rows = [{
                type: 'Emoney',
                yesterday: yesterday.emoneypayment,
                today: today.emoneypayment
            }, {
                type: 'Flazz',
                yesterday: yesterday.flazzpayment,
                today: today.flazzpayment,
            }, {
                type: 'Brizzi',
                yesterday: yesterday.brizzipayment,
                today: today.brizzipayment
            }, {
                type: 'Tap Cash',
                yesterday: yesterday.tapcashpayment,
                today: today.tapcashpayment
            }, {
                type: 'Parkee',
                yesterday: yesterday.parkeepayment,
                today: today.parkeepayment
            }, {
                type: 'Cash',
                yesterday: yesterday.cashpayment,
                today: today.cashpayment
            }]
            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                payment: item.type,
                yesterday: formatRupiah(item.yesterday),
                today: formatRupiah(item.today)
            }));

            table.rows.add(formattedRows).draw();

            $('#dailyE-Payment tfoot').html(`
                    <tr>
                        <th colspan="2" style="text-align:left">All E-Payment</th>
                        <th id="totalEpaymentYesterday">${formatRupiah(today.grandtotal)}</th>
                        <th id="totalEpaymentToday">${formatRupiah(today.grandtotal)}</th>
                    </tr>
                `);

            const labels = ['Emoney', 'Flazz', 'Brizzi', 'Tap Cash', 'Parkee', 'Cash', 'All'];
            const dataChart = response.data[0].today[0];

            const casualData = [
                dataChart.emoneypayment,
                dataChart.flazzpayment,
                dataChart.brizzipayment,
                dataChart.tapcashpayment,
                dataChart.parkeepayment,
                dataChart.cashpayment,
                dataChart.grandtotal
            ];

            const barColors = [
                '#0D61E2', // Emoney
                '#FFB800', // Flazz
                '#FF4D4D', // Brizzi
                '#00C9A7', // Tap Cash
                '#9C27B0', // Parkee
                '#795548', // Cash
                '#CCCCCC' // All (abu-abu muda)
            ];

            const barPaymentData = {
                labels: labels,
                datasets: [{
                    label: 'Today',
                    data: casualData,
                    backgroundColor: barColors,
                    borderColor: barColors,
                    borderWidth: 1,
                    datalabels: {
                        anchor: 'end',
                        align: 'end'
                    }
                }]
            };


            const barConfig = {
                type: 'bar',
                data: barPaymentData,
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
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
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
            const ctx = document.getElementById('dailyE-PaymentBar')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, barConfig);
            }

        }
    })

    $.ajax({
        url: weeklyEpaymentURL,
        method: 'GET',
        success: function(response) {
            const thisWeekEpayment = response.this_week.totals;
            const lastWeekEpayment = response.last_week.totals;

            const compare= response.table_data;
            const container = $('#weekly-epayment-comparison');

            container.empty(); // Biar gak dobel kalau dipanggil ulang
    
            compare.forEach(payment => {
                const html = `
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <div class="card-title">${payment.method}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value">${formatRupiah(payment.this_week)}</h2>
                                <span class="ms-2" style="color: ${payment.color}">
                                    ${payment.percent_change}
                                    ${payment.direction}
                                </span>
                            </div>
                            <div class="yesterday">Last Week: ${formatRupiah(payment.last_week)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });
    
            const rows = [{
                type: 'E Money',
                last_week: lastWeekEpayment.emoneypayment,
                this_week: thisWeekEpayment.emoneypayment
            }, {
                type: 'Flazz',
                last_week: lastWeekEpayment.flazzpayment,
                this_week: thisWeekEpayment.flazzpayment
            }, {
                type: 'Brizzi',
                last_week: lastWeekEpayment.brizzipayment,
                this_week: thisWeekEpayment.brizzipayment
            }, {
                type: 'Tap Cash',
                last_week: lastWeekEpayment.tapcashpayment,
                this_week: thisWeekEpayment.tapcashpayment
            }, {
                type: 'Parkee',
                last_week: lastWeekEpayment.parkeepayment,
                this_week: thisWeekEpayment.parkeepayment
            }, {
                type: 'Cash',
                last_week: lastWeekEpayment.cashpayment,
                this_week: thisWeekEpayment.cashpayment
            }]

            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                payment: item.type,
                last_week: formatRupiah(item.last_week),
                this_week: formatRupiah(item.this_week)
            }));

            weeklyTable.rows.add(formattedRows).draw();

            $('#weeklyE-Payment tfoot').html(`
                    <tr>
                        <th colspan="2" style="text-align:left">All E-Payment</th>
                        <th id="totalEpaymentLastWeek">${formatRupiah(lastWeekEpayment.allpayment)}</th>
                        <th id="totalEpaymentThisWeek">${formatRupiah(thisWeekEpayment.allpayment)}</th>
                    </tr>
                `);

            const thisWeekEpaymentChart = response.this_week.data;
            const labels = thisWeekEpaymentChart.map(item => {
                const date = new Date(item.tanggal);
                return date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short'
                }); // hasilnya: 14 Apr, 15 Apr, dst.
            });


            const weeklyData = [
                thisWeekEpaymentChart.map(item => item.emoneypayment),
                thisWeekEpaymentChart.map(item => item.flazzpayment),
                thisWeekEpaymentChart.map(item => item.brizzipayment),
                thisWeekEpaymentChart.map(item => item.tapcashpayment),
                thisWeekEpaymentChart.map(item => item.parkeepayment),
                thisWeekEpaymentChart.map(item => item.cashpayment),
                thisWeekEpaymentChart.map(item => item.allpayment)
            ];


            const barData = {
                labels: labels,
                datasets: [{
                        label: 'E Money',
                        data: weeklyData[0],
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Flazz',
                        data: weeklyData[1],
                        backgroundColor: '#FFB800',
                        borderColor: '#FFB800',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Brizzi',
                        data: weeklyData[2],
                        backgroundColor: '#FF4D4D',
                        borderColor: '#FF4D4D',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Tap Cash',
                        data: weeklyData[3],
                        backgroundColor: '#00C9A7',
                        borderColor: '#00C9A7',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Parkee',
                        data: weeklyData[4],
                        backgroundColor: '#9C27B0',
                        borderColor: '#9C27B0',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Cash',
                        data: weeklyData[5],
                        backgroundColor: '#795548',
                        borderColor: '#795548',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'All',
                        data: weeklyData[6],
                        backgroundColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderWidth: 1,
                        hidden: true,
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
                                weight: 'bold',
                                size: 9

                            },
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
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
                ]
            };

            const lineData = {
                labels: labels,
                datasets: [{
                        label: 'E Money',
                        data: weeklyData[0],
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 1,
                        fill: false,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Flazz',
                        data: weeklyData[1],
                        backgroundColor: '#FFB800',
                        borderColor: '#FFB800',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Brizzi',
                        data: weeklyData[2],
                        backgroundColor: '#FF4D4D',
                        borderColor: '#FF4D4D',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Tap Cash',
                        data: weeklyData[3],
                        backgroundColor: '#00C9A7',
                        borderColor: '#00C9A7',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Parkee',
                        data: weeklyData[4],
                        backgroundColor: '#9C27B0',
                        borderColor: '#9C27B0',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Cash',
                        data: weeklyData[5],
                        backgroundColor: '#795548',
                        borderColor: '#795548',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }, {
                        label: 'All',
                        data: weeklyData[6],
                        backgroundColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }
                ]
            };

            const lineConfig = {
                type: 'line',
                data: lineData,
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
                                weight: 'bold',
                                size: 9

                            },
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
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
                ]
            };
            const ctxLine = document.getElementById('weeklyE-PaymentLine')?.getContext('2d');
            if (ctxLine) {
                new Chart(ctxLine, lineConfig);
            }


            const ctx = document.getElementById('weeklyE-PaymentBar')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, barConfig);
            }

        }



    })

    $.ajax({
        url: monthlyEpaymentURL,
        method: 'GET',
        success: function(response) {
            const thisMonthEpayment = response.this_Month.totals;
            const lastMonthEpayment = response.last_Month.totals;

            const compare= response.table_data;
            const container = $('#monthly-epayment-comparison');

            container.empty(); // Biar gak dobel kalau dipanggil ulang
    
            compare.forEach(payment => {
                const html = `
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <div class="card-title">${payment.method}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value">${formatRupiah(payment.this_month)}</h2>
                                <span class="ms-2" style="color: ${payment.color}">
                                    ${payment.percent_change}
                                    ${payment.direction}
                                </span>
                            </div>
                            <div class="yesterday">Yesterday: ${formatRupiah(payment.last_month)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [{
                type: 'E Money',
                last_month: lastMonthEpayment.emoneypayment,
                this_month: thisMonthEpayment.emoneypayment
            }, {
                type: 'Flazz',
                last_month: lastMonthEpayment.flazzpayment,
                this_month: thisMonthEpayment.flazzpayment
            }, {
                type: 'Brizzi',
                last_month: lastMonthEpayment.brizzipayment,
                this_month: thisMonthEpayment.brizzipayment
            }, {
                type: 'Tap Cash',
                last_month: lastMonthEpayment.tapcashpayment,
                this_month: thisMonthEpayment.tapcashpayment
            }, {
                type: 'Parkee',
                last_month: lastMonthEpayment.parkeepayment,
                this_month: thisMonthEpayment.parkeepayment
            }, {
                type: 'Cash',
                last_month: lastMonthEpayment.cashpayment,
                this_month: thisMonthEpayment.cashpayment
            }]
            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                payment: item.type,
                last_month: formatRupiah(item.last_month),
                this_month: formatRupiah(item.this_month)
            }));

            monthlyTable.rows.add(formattedRows).draw();
            $('#monthlyE-Payment tfoot').html(`
                    <tr>
                        <th colspan="2" style="text-align:left">All E-Payment</th>
                        <th id="totalEpaymentLastMonth">${formatRupiah(lastMonthEpayment.allpayment)}</th>
                        <th id="totalEpaymentThisMonth">${formatRupiah(thisMonthEpayment.allpayment)}</th>
                    </tr>
                `);

            const thisMonthEpaymentChart = response.this_Month.weekly_totals;

            const labels = Object.keys(thisMonthEpaymentChart); // week1, week2, etc.

            const monthlyData = [
                Object.values(thisMonthEpaymentChart).map(item => item.emoneypayment),
                Object.values(thisMonthEpaymentChart).map(item => item.flazzpayment),
                Object.values(thisMonthEpaymentChart).map(item => item.brizzipayment),
                Object.values(thisMonthEpaymentChart).map(item => item.tapcashpayment),
                Object.values(thisMonthEpaymentChart).map(item => item.parkeepayment),
                Object.values(thisMonthEpaymentChart).map(item => item.cashpayment),
                Object.values(thisMonthEpaymentChart).map(item => item.allpayment)
            ];

            const barData = {
                labels: labels,
                datasets: [{
                        label: 'E Money',
                        data: monthlyData[0],
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Flazz',
                        data: monthlyData[1],
                        backgroundColor: '#FFB800',
                        borderColor: '#FFB800',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Brizzi',
                        data: monthlyData[2],
                        backgroundColor: '#FF4D4D',
                        borderColor: '#FF4D4D',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Tap Cash',
                        data: monthlyData[3],
                        backgroundColor: '#00C9A7',
                        borderColor: '#00C9A7',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Parkee',
                        data: monthlyData[4],
                        backgroundColor: '#9C27B0',
                        borderColor: '#9C27B0',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Cash',
                        data: monthlyData[5],
                        backgroundColor: '#795548',
                        borderColor: '#795548',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }, {
                        label: 'All',
                        data: monthlyData[6],
                        backgroundColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderWidth: 1,
                        hidden: true,
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
                                weight: 'bold',
                                size: 9

                            },
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
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
                ]
            };

            const lineData = {
                labels: labels,
                datasets: [{
                        label: 'E Money',
                        data: monthlyData[0],
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 1,
                        fill: false,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Flazz',
                        data: monthlyData[1],
                        backgroundColor: '#FFB800',
                        borderColor: '#FFB800',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Brizzi',
                        data: monthlyData[2],
                        backgroundColor: '#FF4D4D',
                        borderColor: '#FF4D4D',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Tap Cash',
                        data: monthlyData[3],
                        backgroundColor: '#00C9A7',
                        borderColor: '#00C9A7',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Parkee',
                        data: monthlyData[4],
                        backgroundColor: '#9C27B0',
                        borderColor: '#9C27B0',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    },
                    {
                        label: 'Cash',
                        data: monthlyData[5],
                        backgroundColor: '#795548',
                        borderColor: '#795548',
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }, {
                        label: 'All',
                        data: monthlyData[6],
                        backgroundColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderColor: '#CCCCCC', // warna abu-abu muda untuk All Payment
                        borderWidth: 1,
                        fill: false,
                        hidden: true,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }
                ]
            };

            const lineConfig = {
                type: 'line',
                data: lineData,
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
                                weight: 'bold',
                                size: 9

                            },
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
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
                ]
            };
            const ctxLine = document.getElementById('monthlyE-PaymentLine')?.getContext('2d');
            if (ctxLine) {
                new Chart(ctxLine, lineConfig);
            }

            const ctx = document.getElementById('monthlyE-PaymentBar')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, barConfig);
            }





        }
    })
});