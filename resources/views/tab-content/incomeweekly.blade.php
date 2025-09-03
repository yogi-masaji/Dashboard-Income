<style>
    @media (max-width: 768px) {
        #weeklyIncome {
            font-size: 11px;
        }

        #weeklyIncome tfoot th,
        #weeklyIncome thead th {
            font-size: 9px !important;
        }

        #weeklyIncome th,
        #weeklyIncome td {
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
<h5>Weekly Income</h5>
<div class="row">
    <div class="col-12">
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-12 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison last week & two weeks ago (range: senin - minggu)</h6>
                    <div class="row" id="weekly-income-comparison"></div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-md-0 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <table id="weeklyIncome" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle</th>
                                <th>Last Week</th>
                                <th>This Week</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2" style="text-align:left">All Vehicle</th>
                                <th id="totalLastWeek"></th>
                                <th id="totalThisWeek"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="content-custom" style="min-height: 250px;">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-weeklyIncome-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weeklyIncome" type="button" role="tab"
                                aria-controls="nav-weeklyIncome" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weeklyIncome-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weeklyIncome-line" type="button" role="tab"
                                aria-controls="nav-weeklyIncome-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weeklyIncome" role="tabpanel"
                            aria-labelledby="nav-weeklyIncome-tab" tabindex="0">
                            <div class="chart-container">

                                <canvas id="weeklyIncomeBar"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-weeklyIncome-line" role="tabpanel"
                            aria-labelledby="nav-weeklyIncome-line-tab" tabindex="0">
                            <div class="chart-container">

                                <canvas id="weeklyIncomeLine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
