<style>
    @media (max-width: 768px) {
        #weeklyQuantity {
            font-size: 10px;
            /* kecilin font biar muat */
        }

        #weeklyQuantity tfoot th,
        #weeklyQuantity thead th {
            font-size: 10px;
        }

        #weeklyQuantity th,
        #weeklyQuantity td {
            padding: 4px 6px;
            /* kecilin padding */
            white-space: nowrap;
            /* cegah teks pecah ke 2 baris */
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
<div class="row">
    <div class="col-12">
        <h5>Weekly Quantity</h5>

        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-12 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison last week & two weeks ago (range: senin - minggu)</h6>
                    <div class="row" id="weekly-transaction-comparison"></div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-md-0 col-md-6 d-flex">
                <div class="content-custom flex-fill" style="">
                    <table id="weeklyQuantity" class="table table-striped">
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
                            <button class="nav-link active" id="nav-weekly-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly" type="button" role="tab" aria-controls="nav-weekly"
                                aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weekly-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly-line" type="button" role="tab"
                                aria-controls="nav-weekly-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weekly" role="tabpanel"
                            aria-labelledby="nav-weekly-tab" tabindex="0">
                            <div class="chart-container">
                                <canvas id="weeklyQuantityBar"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-weekly-line" role="tabpanel"
                            aria-labelledby="nav-weekly-line-tab" tabindex="0">
                            <div class="chart-container">
                                <canvas id="weeklyQuantityLine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
