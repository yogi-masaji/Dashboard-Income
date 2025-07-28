<div class="row">
    <div class="col-md-12">
        <div class="tab-pane fade show active" id="inner-daily-traffic" role="tabpanel"
            aria-labelledby="inner-daily-traffic-tab">
            <ul class="nav nav-pills nav-custom-tab mb-3 w-100" id="pills-tab-daily" role="tablist">
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link active w-100" id="pills-pintumasuk-tab-daily" data-bs-toggle="pill"
                        data-bs-target="#pills-pintumasuk-daily" type="button" role="tab"
                        aria-controls="pills-pintumasuk-daily" aria-selected="true">
                        Pintu Masuk
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link w-100" id="pills-pintukeluar-tab-daily" data-bs-toggle="pill"
                        data-bs-target="#pills-pintukeluar-daily" type="button" role="tab"
                        aria-controls="pills-pintukeluar-daily" aria-selected="false">
                        Pintu Keluar
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent-daily">
                <div class="tab-pane fade show active" id="pills-pintumasuk-daily" role="tabpanel"
                    aria-labelledby="pills-pintumasuk-tab-daily">
                    @include('nested-tab.dailymasuk')
                </div>
                <div class="tab-pane fade" id="pills-pintukeluar-daily" role="tabpanel"
                    aria-labelledby="pills-pintukeluar-tab-daily">
                    @include('nested-tab.dailykeluar')
                </div>
            </div>
        </div>
    </div>
</div>
