<div class="row">
    <div class="col-12">
        <div class="row" id="dashboardRow">
            <div class="row" id="daily-transaction-comparison"></div>



        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="content-custom">

                    <table id="dailyQuantity" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Type</th>
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
            </div>
            <div class="col-md-6">
                <div class="content-custom">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Line</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab" tabindex="0">
                            <canvas id="dailyQuantityBar" height="200"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">
                            <canvas id="dailyQuantityLine" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
