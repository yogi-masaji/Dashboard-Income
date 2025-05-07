<h5>Weekly Income</h5>
<div class="row">
    <div class="col-12">
        <div class="row" id="dashboardRow">
            <div class="row" id="weekly-income-comparison"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table id="weeklyIncome" class="table table-striped table-bordered">
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
            <div class="col-md-6">
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
                        <canvas id="weeklyIncomeBar" height="200" width="auto"></canvas>
                    </div>
                    <div class="tab-pane fade" id="nav-weeklyIncome-line" role="tabpanel"
                        aria-labelledby="nav-weeklyIncome-line-tab" tabindex="0">
                        <canvas id="weeklyIncomeLine" height="200" width="auto"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
