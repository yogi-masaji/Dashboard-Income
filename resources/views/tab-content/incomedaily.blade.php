<style>
    @media (max-width: 768px) {
        #dailyIncome {
            font-size: 11px;
        }

        #dailyIncome tfoot th,
        #dailyIncome thead th {
            font-size: 10px;
        }

        #dailyIncome th,
        #dailyIncome td {
            padding: 1px 2px;
            white-space: nowrap;
        }
    }

    .chart-container {
        position: relative;
        height: 45vh;
        width: 100%;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 25vh;

        }
    }
</style>
<h5>Daily Income</h5>
<div class="row">
    <div class="col-12">
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-12 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison today & yesterday</h6>
                    <div class="row" id="daily-income-comparison"></div>
                </div>
            </div>

            <div class="col-12 mt-3 mt-md-0 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <table id="dailyIncome" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2" style="text-align:left">All Vehicle</th>
                                <th id="totalYesterday"></th>
                                <th id="totalToday"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="content-custom" style="min-height:300px;">
                    <div class="chart-container">
                        <canvas id="dailyIncomedonut"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
