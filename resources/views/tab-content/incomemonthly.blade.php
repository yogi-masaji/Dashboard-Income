<style>
    @media (max-width: 768px) {
        #monthlyIncome {
            font-size: 11px;
        }

        #monthlyIncome tfoot th,
        #monthlyIncome thead th {
            font-size: 9px !important;
        }

        #monthlyIncome th,
        #monthlyIncome td {
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
<h5>Monthly Income</h5>
<div class="row">
    <div class="col-12">
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-12 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison last month & two months ago</h6>
                    <div class="row" id="monthly-income-comparison"></div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-md-0 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <table id="monthlyIncome" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle</th>
                                <th>Last Month</th>
                                <th>This Month</th>
                            </tr>
                        </thead>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="content-custom" style="min-height: 250px;">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-monthlyIncome-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthlyIncome" type="button" role="tab"
                                aria-controls="nav-monthlyIncome" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-monthlyIncome-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthlyIncome-line" type="button" role="tab"
                                aria-controls="nav-monthlyIncome-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-monthlyIncome" role="tabpanel"
                            aria-labelledby="nav-monthlyIncome-tab" tabindex="0">
                            <div class="chart-container">

                                <canvas id="monthlyIncomeBar"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-monthlyIncome-line" role="tabpanel"
                            aria-labelledby="nav-monthlyIncome-line-tab" tabindex="0">
                            <div class="chart-container">

                                <canvas id="monthlyIncomeLine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
