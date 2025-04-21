<div class="nav nav-tabs custom-nav" id="inner-tab" role="tablist">
    <button class="nav-link active" id="inner-daily-epayment-tab" data-bs-toggle="tab"
        data-bs-target="#inner-daily-epayment" type="button" role="tab">Daily</button>
    <button class="nav-link" id="inner-weekly-epayment-tab" data-bs-toggle="tab" data-bs-target="#inner-weekly-epayment"
        type="button" role="tab">Weekly</button>
    <button class="nav-link" id="inner-monthly-epayment-tab" data-bs-toggle="tab"
        data-bs-target="#inner-monthly-epayment" type="button" role="tab">Monthly</button>
</div>

<div class="tab-content">
    <div class="tab-pane fade show active mt-5" id="inner-daily-epayment" role="tabpanel"
        aria-labelledby="inner-daily-epayment-tab">
        <h5>Daily Epayment</h5>
        <div class="row">
            <div class="col-12">
                <div class="row" id="dashboardRow">
                    <div class="row" id="daily-epayment-comparison"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                                <canvas id="dailyE-PaymentBar" height="200" width="auto"></canvas>
                            </div>
                            <div class="tab-pane fade" id="nav-dailyE-Payment-line" role="tabpanel"
                                aria-labelledby="nav-dailyE-Payment-line-tab" tabindex="0">
                                <canvas id="dailyE-PaymentLine" height="200" width="auto"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-weekly-epayment" role="tabpanel" aria-labelledby="inner-weekly-epayment-tab">
        <h5>Weekly E-Payment</h5>
        <div class="row">
            <div class="col-12">
                <div class="row" id="dashboardRow">
                    <div class="row" id="weekly-epayment-comparison"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                                <canvas id="weeklyE-PaymentBar" height="200" width="auto"></canvas>
                            </div>
                            <div class="tab-pane fade" id="nav-weeklyE-Payment-line" role="tabpanel"
                                aria-labelledby="nav-weeklyE-Payment-line-tab" tabindex="0">
                                <canvas id="weeklyE-PaymentLine" height="200" width="auto"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-monthly-epayment" role="tabpanel"
        aria-labelledby="inner-monthly-epayment-tab">
        <h5>Monthly E-Payment</h5>
        <div class="row">
            <div class="col-12">
                <div class="row" id="dashboardRow">
                    <div class="row" id="monthly-epayment-comparison"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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

<script>
    const dailyEpaymentURL = "{{ route('dailyEpayment') }}";
    const weeklyEpaymentURL = "{{ route('weeklyEpayment') }}";
    const monthlyEpaymentURL = "{{ route('monthlyEpayment') }}";
</script>
<script src="js/epayment.js"></script>
