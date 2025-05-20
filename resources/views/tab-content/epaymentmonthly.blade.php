<div class="row">
    <div class="col-md-12">
        <h5>Monthly E-Payment</h5>
        <div class="row">
            <div class="col-12">
                <div class="row" id="dashboardRow">
                    <div class="row" id="monthly-epayment-comparison"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-custom">


                            <table id="monthlyE-Payment" class="table table-striped table-bordered">
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
                    <div class="col-md-6">
                        <div class="content-custom">


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
                                    <canvas id="monthlyE-PaymentBar" height="200" width="auto"></canvas>
                                </div>
                                <div class="tab-pane fade" id="nav-monthlyE-Payment-line" role="tabpanel"
                                    aria-labelledby="nav-monthlyE-Payment-line-tab" tabindex="0">
                                    <canvas id="monthlyE-PaymentLine" height="200" width="auto"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
