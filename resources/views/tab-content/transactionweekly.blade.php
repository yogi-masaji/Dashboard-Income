<div class="row">
    <div class="col-12">
        <h5>Weekly Quantity</h5>

        <div class="row" id="dashboardRow">
            {{-- @foreach ($processedData as $data)
                <!-- Total Casual Card -->
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="card-title">{{ $data['label'] }}</div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="card-value">{{ $data['today'] }}</h2>
                            <span class=" ms-2" style="color: {{ $data['color'] }}">
                                {{ $data['percent_change'] }}%
                                {{ $data['direction'] }}</span>
                        </div>
                        <div class="yesterday">Yesterday: {{ $data['yesterday'] }}</div>
                    </div>
                </div>
            @endforeach --}}
            <div class="row" id="weekly-transaction-comparison"></div>


        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="content-custom">
                    <table id="weeklyQuantity" class="table table-striped">
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
            </div>
            <div class="col-md-6">
                <div class="content-custom">

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-weekly-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly" type="button" role="tab" aria-controls="nav-weekly"
                                aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weekly-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly-line" type="button" role="tab"
                                aria-controls="nav-weekly-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weekly" role="tabpanel"
                            aria-labelledby="nav-weekly-tab" tabindex="0">
                            <canvas id="weeklyQuantityBar" height="200" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-weekly-line" role="tabpanel"
                            aria-labelledby="nav-weekly-line-tab" tabindex="0">
                            <canvas id="weeklyQuantityLine" height="200" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
