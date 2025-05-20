<h5>Monthly Income</h5>
<div class="row">
    <div class="col-12">
        <div class="row" id="dashboardRow">
            <div class="row" id="monthly-income-comparison"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="content-custom">


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
            <div class="col-md-6">
                <div class="content-custom">


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
                            <canvas id="monthlyIncomeBar" height="200" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-monthlyIncome-line" role="tabpanel"
                            aria-labelledby="nav-monthlyIncome-line-tab" tabindex="0">
                            <canvas id="monthlyIncomeLine" height="200" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
