<h5>Daily Income</h5>
<div class="row">
    <div class="col-12">
        <div class="row" id="dashboardRow">
            <div class="row" id="daily-income-comparison"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table id="dailyIncome" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Vehicle</th>
                            <th>Yesterday</th>
                            <th>Today</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalYesterday"></th>
                            <th id="totalToday"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-6">
                <canvas id="dailyIncomedonut" height="70"></canvas>
            </div>
        </div>
    </div>
</div>
