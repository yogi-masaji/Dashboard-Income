<div class="row">
    <div class="col-md-12">
        <div class="tab-pane fade show active" id="inner-weekly-traffic" role="tabpanel"
            aria-labelledby="inner-weekly-traffic-tab">
            <ul class="nav nav-pills nav-custom-tab mb-3 w-100" id="pills-tab-weekly" role="tablist">
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link active w-100" id="pills-pintumasuk-tab-weekly" data-bs-toggle="pill"
                        data-bs-target="#pills-pintumasuk-weekly" type="button" role="tab"
                        aria-controls="pills-pintumasuk-weekly" aria-selected="true">
                        Pintu Masuk
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link w-100" id="pills-pintukeluar-tab-weekly" data-bs-toggle="pill"
                        data-bs-target="#pills-pintukeluar-weekly" type="button" role="tab"
                        aria-controls="pills-pintukeluar-weekly" aria-selected="false">
                        Pintu Keluar
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent-weekly">
                <div class="tab-pane fade show active" id="pills-pintumasuk-weekly" role="tabpanel"
                    aria-labelledby="pills-pintumasuk-tab-weekly">
                    @include('nested-tab.weeklyMasuk')
                </div>
                <div class="tab-pane fade" id="pills-pintukeluar-weekly" role="tabpanel"
                    aria-labelledby="pills-pintukeluar-tab-weekly">
                    @include('nested-tab.weeklyKeluar')
                </div>
            </div>
        </div>
    </div>
</div>
