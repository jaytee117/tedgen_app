@extends('theme.default')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4">Dashboard</h3>
    <div class="row">
        <div class="col-xl-3 col-md-6">
             <div class="card card-price cardblue">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="" style="width: 100px;margin: 0 auto;">
                        </div>
                        <div class="col-md-8">
                            <div class="h2" style="font-size:24px;font-weight:100;margin-top:12px">
                                Today
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Gas Consumed</p>
                        </div>
                        <div class="col-md-6">
                            <div class="h99 pull-right"> kW</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Electricity Generated</p>
                        </div>
                        <div class="col-md-6">
                            <div class="h99 pull-right">kW</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Heat Generated</p>
                        </div>
                        <div class="col-md-6">
                            <div class="h99 pull-right"> kWth</div>
                        </div>
                    </div>
                </div>
            <!--<div class="card bg-primary text-white mb-4">
                <div class="card-body">Primary Card</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>-->
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Warning Card</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Success Card</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Danger Card</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($installations as $install)
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-signal me1"></i>
                    {{ $install->asset_id }}-{{ $install->site['site_name'] }} - {{ App\Models\Installation::$_machine_status[$install->machine_status] }}<div
                        style="position: absolute; top: 60px; right: 30px;"><img
                            src="https://openweathermap.org/img/wn/{{ $install->site['weather_icon'] }}.png"></div>
                </div>
                <div class="card-body">
                    <img src="storage/{{ $install->site['site_img'] }}" alt="Uploaded File" class="img-responsive">
                </div>
            </div>
        </div>
        @endforeach
        {{ $installations->links() }}
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Area Chart Example
                </div>
                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Bar Chart Example
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection