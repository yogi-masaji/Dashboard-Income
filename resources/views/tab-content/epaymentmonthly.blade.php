<style>
    @media (max-width: 768px) {
        #monthlyIncome {
            font-size: 11px;
        }

        #monthlyIncome tfoot th,
        #monthlyIncome thead th {
            font-size: 10px;
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
<div class="row">
    <div class="col-md-12">
        <h5>Monthly E-Payment</h5>
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-12 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison last month & two months ago</h6>
                    <div class="row" id="monthly-epayment-comparison"></div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-md-0 col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <table id="monthlyE-Payment" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment</th>
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
                            <button class="nav-link active" id="nav-monthlyE-Payment-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthlyE-Payment" type="button" role="tab"
                                aria-controls="nav-monthlyE-Payment" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-monthlyE-Payment-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthlyE-Payment-line" type="button" role="tab"
                                aria-controls="nav-monthlyE-Payment-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-monthlyE-Payment" role="tabpanel"
                            aria-labelledby="nav-monthlyE-Payment-tab" tabindex="0">
                            <div class="chart-container">

                                <canvas id="monthlyE-PaymentBar"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-monthlyE-Payment-line" role="tabpanel"
                            aria-labelledby="nav-monthlyE-Payment-line-tab" tabindex="0">
                            <div class="chart-container">

                                <canvas id="monthlyE-PaymentLine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
