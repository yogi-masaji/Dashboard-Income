<div class="row">
    <div class="col-md-12">
        <h5>Daily Epayment</h5>
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison today & yesterday</h6>
                    <div class="row" id="daily-epayment-comparison"></div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <table id="dailyE-Payment" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment</th>
                                <th>Yesterday</th>
                                <th>Today</th>
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
                            <button class="nav-link active" id="nav-dailyE-Payment-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-dailyE-Payment" type="button" role="tab"
                                aria-controls="nav-dailyE-Payment" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-dailyE-Payment-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-dailyE-Payment-line" type="button" role="tab"
                                aria-controls="nav-dailyE-Payment-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-dailyE-Payment" role="tabpanel"
                            aria-labelledby="nav-dailyE-Payment-tab" tabindex="0">
                            <canvas id="dailyE-PaymentBar" height="100" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-dailyE-Payment-line" role="tabpanel"
                            aria-labelledby="nav-dailyE-Payment-line-tab" tabindex="0">
                            <canvas id="dailyE-PaymentLine" height="100" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
