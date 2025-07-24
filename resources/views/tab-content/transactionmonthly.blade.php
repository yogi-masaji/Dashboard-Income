<div class="row">
    <div class="col-12">
        <h5>Monthly Quantity</h5>
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <div class="row" id="monthly-transaction-comparison"></div>
                </div>
            </div>

            <div class="col-md-6 d-flex">
                <div class="content-custom flex-fill" style="min-height: 373px;">
                    <table id="monthlyQuantity" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle</th>
                                <th>Last Month</th>
                                <th>This Month</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2" style="text-align:left">All Vehicle</th>
                                <th id="totalLastMonth"></th>
                                <th id="totalThisMonth"></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>


        </div>
        <div class="row mt-5">

            <div class="col-md-12">
                <div class="content-custom">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-monthly-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthly" type="button" role="tab" aria-controls="nav-monthly"
                                aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-monthly-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthly-line" type="button" role="tab"
                                aria-controls="nav-monthly-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-monthly" role="tabpanel"
                            aria-labelledby="nav-monthly-tab" tabindex="0">
                            <canvas id="monthlyQuantityBar" height="100" width="auto"></canvas>

                        </div>
                        <div class="tab-pane fade" id="nav-monthly-line" role="tabpanel"
                            aria-labelledby="nav-monthly-line-tab" tabindex="0">
                            <canvas id="monthlyQuantityLine" height="100" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
