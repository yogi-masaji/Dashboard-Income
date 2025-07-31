<div class="row">
    <div class="col-md-12">
        <h5>Weekly E-Payment</h5>
        <div class="row d-flex align-items-stretch" id="dashboardRow">
            <div class="col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <h6>Comparison last week & two weeks ago</h6>
                    <div class="row" id="weekly-epayment-comparison"></div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="content-custom flex-fill">
                    <table id="weeklyE-Payment" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment</th>
                                <th>Last Week</th>
                                <th>This Week</th>
                            </tr>
                        </thead>
                        <tfoot>
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
                            <button class="nav-link active" id="nav-weeklyE-Payment-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weeklyE-Payment" type="button" role="tab"
                                aria-controls="nav-weeklyE-Payment" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weeklyE-Payment-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weeklyE-Payment-line" type="button" role="tab"
                                aria-controls="nav-weeklyE-Payment-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weeklyE-Payment" role="tabpanel"
                            aria-labelledby="nav-weeklyE-Payment-tab" tabindex="0">
                            <canvas id="weeklyE-PaymentBar" height="100" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-weeklyE-Payment-line" role="tabpanel"
                            aria-labelledby="nav-weeklyE-Payment-line-tab" tabindex="0">
                            <canvas id="weeklyE-PaymentLine" height="100" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
