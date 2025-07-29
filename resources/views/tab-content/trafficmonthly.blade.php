<div class="row">
    <div class="col-md-12">
        <div class="tab-pane fade show active" id="inner-monthly-traffic" role="tabpanel"
            aria-labelledby="inner-monthly-traffic-tab">
            <ul class="nav nav-pills nav-custom-tab mb-3 w-100" id="pills-tab-monthly" role="tablist">
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link active w-100" id="pills-pintumasuk-tab-monthly" data-bs-toggle="pill"
                        data-bs-target="#pills-pintumasuk-monthly" type="button" role="tab"
                        aria-controls="pills-pintumasuk-monthly" aria-selected="true">
                        Pintu Masuk
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link w-100" id="pills-pintukeluar-tab-monthly" data-bs-toggle="pill"
                        data-bs-target="#pills-pintukeluar-monthly" type="button" role="tab"
                        aria-controls="pills-pintukeluar-monthly" aria-selected="false">
                        Pintu Keluar
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent-monthly">
                <div class="tab-pane fade show active" id="pills-pintumasuk-monthly" role="tabpanel"
                    aria-labelledby="pills-pintumasuk-tab-monthly">
                    @include('nested-tab.monthlymasuk')
                </div>
                <div class="tab-pane fade" id="pills-pintukeluar-monthly" role="tabpanel"
                    aria-labelledby="pills-pintukeluar-tab-monthly">
                    @include('nested-tab.monthlykeluar')
                </div>
            </div>
        </div>
    </div>
</div>
