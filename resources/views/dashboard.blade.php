@extends('theme.default')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 mt-4">
                <div class="card-header">
                    <div class="h2" style="font-size:24px;font-weight:100;">
                        Today
                    </div>
                </div>
                <div class="row">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small  stretched-link">Gas Consumed</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Electricity Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Heat Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 mt-4">
                <div class="card-header">
                    <div class="h2" style="font-size:24px;font-weight:100;">
                        Yesterday
                    </div>
                </div>
                <div class="row">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small  stretched-link">Gas Consumed</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Electricity Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Heat Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 mt-4">
                <div class="card-header">

                    <div class="h2" style="font-size:24px;font-weight:100;">
                        Month To Date
                    </div>

                </div>
                <div class="row">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small  stretched-link">Gas Consumed</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Electricity Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Heat Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 mt-4">
                <div class="card-header">

                    <div class="h2" style="font-size:24px;font-weight:100;">
                        Year To Date
                    </div>

                </div>
                <div class="row">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small  stretched-link">Gas Consumed</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Electricity Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small stretched-link">Heat Generated</div>
                        <div class="small "><i class="fas fa-angle-right"></i> 99 Kw</div>
                    </div>
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
                    {{ $install->asset_id }}-{{ $install->site['site_name'] }} - {{
                    App\Models\Installation::$_machine_status[$install->machine_status] }}<div
                        style="position: absolute; top: 60px; right: 30px;"><img
                            src="https://openweathermap.org/img/wn/{{ $install->site['weather_icon'] }}.png"></div>
                </div>
                <div class="card-body">
                    <img src="storage/{{ $install->site['site_img'] }}" alt="Uploaded File" class="img-responsive">
                </div>
                <a href="{{ route('installation.viewdata', $install) }}" class="btn btn-default">View Details</a>
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