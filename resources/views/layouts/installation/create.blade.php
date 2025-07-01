@extends('theme.default')
@section('content')
    <div class="container-fluid px-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">CHP Installation Data</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="chp-tab" data-bs-toggle="tab" data-bs-target="#chp" type="button"
                    role="tab" aria-controls="data" aria-selected="false">Logger Data Lines</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <div class="row">
                    <div class="col-md-6">
                        <h3><i class="fa fa-cog"></i> CHP Installation Data</h3>
                    </div>
                    <div class="col-md-6">
                        @if (isset($installation))
                            <form action="{{ route('installation.destroy', $installation->id) }}" method="POST"
                                class="mb-0 pb-0">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger float-end mr-2" style="width:160px" type="submit">Delete
                                    Installation</button>
                            </form>
                        @endif
                    </div>
                    @if (isset($site) && !isset($installation))
                        <form action="{{ route('installation.store') }}" method="POST">
                    @endif
                    @if (isset($installation))
                        <form action="{{ route('installation.update', $installation->id) }}" method="POST">
                    @endif
                    @csrf
                    <input type="hidden" id="account_id" name="account_id"
                        value="{{ old('account_id', isset($site->id) ? $site->account_id : $installation->account_id) }}">
                    <input type="hidden" id="site_id" name="site_id"
                        value="{{ old('site_id', isset($site->id) ? $site->id : $installation->site_id) }}">
                    <label for="asset_id" class="col-md-3">Asset Identifier:
                        <input class="form-control" type="text" id="asset_id" name="asset_id"
                            value="{{ old('asset_id', isset($installation->id) ? $installation->asset_id : '') }}" required>
                    </label>
                    <label for="machine_status" class="col-md-3">Machine Status:
                        <select class="form-control" id="machine_status" name="machine_status" required>
                            <option value="" disabled selected>Select a status</option>
                            @foreach (App\Models\Installation::$_machine_status as $key => $value)
                                <option value="{{ $key }}" @if (isset($installation) && $key == $installation->machine_status) selected @endif>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="machine_type" class="col-md-3">Machine Type:
                        <select class="form-control" id="machine_type" name="machine_type" required>
                            <option value="" disabled selected>Select a type</option>
                            @foreach (App\Models\Installation::$_machine_type as $key => $value)
                                <option value="{{ $key }}" @if (isset($installation) && $key == $installation->machine_type) selected @endif>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="logger_type" class="col-md-3">Logger Type:
                        <select class="form-control" id="logger_type" name="logger_type" required>
                            <option value="" disabled selected>Select a type</option>
                            @foreach (App\Models\Installation::$_logger_type as $key => $value)
                                <option value="{{ $key }}" @if (isset($installation) && $key == $installation->logger_type) selected @endif>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="ip_address" class="col-md-3">IP Address/2g Asset ID:
                        <input class="form-control" type="text" id="ip_address" name="ip_address"
                            value="{{ old('ip_address', isset($installation->id) ? $installation->ip_address : '') }}">
                    </label>
                    <label for="xero_id" class="col-md-3">Xero Contact ID:
                        <input class="form-control" type="text" id="xero_id" name="xero_id"
                            value="{{ old('xero_id', isset($installation->id) ? $installation->xero_id : '') }}">
                    </label>
                </div>
                <div class="row">
                    <h3 class="mt-4"><i class="fa fa-plug"></i> Supplier Rates</h3>
                    <label for="elec_day_rate" class="col-md-3">Electric Day Rate:
                        <input class="form-control" type="text" id="elec_day_rate" name="elec_day_rate"
                            value="{{ old('elec_day_rate', isset($installation->id) ? $installation->elec_day_rate : '') }}">
                    </label>
                    <label for="elec_ccl_rate" class="col-md-3">Electric CCL:
                        <input class="form-control" type="text" id="elec_ccl_rate" name="elec_ccl_rate"
                            value="{{ old('elec_ccl_rate', isset($installation->id) ? $installation->elec_ccl_rate : '') }}">
                    </label>
                    <label for="elec_ccl_discount" class="col-md-3">Electric CCL Discount(%):
                        <input class="form-control" type="text" id="elec_ccl_discount" name="elec_ccl_discount"
                            value="{{ old('elec_ccl_discount', isset($installation->id) ? $installation->elec_ccl_discount : '') }}">
                    </label>
                    <label for="boiler_efficiency" class="col-md-3">Boiler Efficiency(%):
                        <input class="form-control" type="text" id="boiler_efficiency" name="boiler_efficiency"
                            value="{{ old('boiler_efficiency', isset($installation->id) ? $installation->boiler_efficiency : '') }}">
                    </label>
                    <label for="elec_night_rate" class="col-md-3">Electric Night Rate:
                        <input class="form-control" type="text" id="elec_night_rate" name="elec_night_rate"
                            value="{{ old('elec_night_rate', isset($installation->id) ? $installation->elec_night_rate : '') }}">
                    </label>
                    <label for="gas_ccl_rate" class="col-md-3">Gas CCL:
                        <input class="form-control" type="text" id="gas_ccl_rate" name="gas_ccl_rate"
                            value="{{ old('gas_ccl_rate', isset($installation->id) ? $installation->gas_ccl_rate : '') }}">
                    </label>
                    <label for="gas_ccl_discount" class="col-md-3">Gas CCL Discount(%):
                        <input class="form-control" type="text" id="gas_ccl_discount" name="gas_ccl_discount"
                            value="{{ old('gas_ccl_discount', isset($installation->id) ? $installation->gas_ccl_discount : '') }}">
                    </label>
                    <label for="tedgen_discount" class="col-md-3">TedGen Discount(%):
                        <input class="form-control" type="text" id="tedgen_discount" name="tedgen_discount"
                            value="{{ old('tedgen_discount', isset($installation->id) ? $installation->tedgen_discount : '') }}">
                    </label>
                    <label for="gas_rate" class="col-md-3">Gas Rate:
                        <input class="form-control" type="text" id="gas_rate" name="gas_rate"
                            value="{{ old('gas_rate', isset($installation->id) ? $installation->gas_rate : '') }}">
                    </label>
                </div>
                <div class="row">
                    <h3 class="mt-4">Conversion / Carbon Factors</h3>
                    <label for="calorific_value" class="col-md-3">Calorific Value:
                        <input class="form-control" type="text" id="calorific_value" name="calorific_value"
                            value="{{ old('calorific_value', isset($installation->id) ? $installation->calorific_value : '') }}">
                    </label>
                    <label for="conversion_factor" class="col-md-3">Conversion Factor:
                        <input class="form-control" type="text" id="conversion_factor" name="conversion_factor"
                            value="{{ old('conversion_factor', isset($installation->id) ? $installation->conversion_factor : '') }}">
                    </label>
                    <label for="elec_carbon_rate" class="col-md-3">Electricity Carbon Rate (Tonnes/kW):
                        <input class="form-control" type="text" id="elec_carbon_rate" name="elec_carbon_rate"
                            value="{{ old('elec_carbon_rate', isset($installation->id) ? $installation->elec_carbon_rate : '') }}">
                    </label>
                    <label for="gas_carbon_rate" class="col-md-3">Gas Carbon Rate (Tonnes/kW):
                        <input class="form-control" type="text" id="gas_carbon_rate" name="gas_carbon_rate"
                            value="{{ old('gas_carbon_rate', isset($installation->id) ? $installation->gas_carbon_rate : '') }}">
                    </label>
                </div>
                <div class="row">
                    <h3 class="mt-4"><i class="fa fa-lock"></i> Calculated Rates</h3>
                    <label for="tedgen_elec_day" class="col-md-4">Calculated Electric Day Rate:
                        <input class="form-control" type="text" id="tedgen_elec_day" name="tedgen_elec_day"
                            value="{{ old('tedgen_elec_day', isset($installation->id) ? $installation->tedgen_elec_day : '') }}"
                            readonly>
                    </label>
                    <label for="tedgen_elec_night" class="col-md-4">Calculated Electric Night Rate:
                        <input class="form-control" type="text" id="tedgen_elec_night" name="tedgen_elec_night"
                            value="{{ old('tedgen_elec_night', isset($installation->id) ? $installation->tedgen_elec_night : '') }}"
                            readonly>
                    </label>
                    <label for="tedgen_gas_heating" class="col-md-4">Calculated Gas Heating Rate:
                        <input class="form-control" type="text" id="tedgen_gas_heating" name="tedgen_gas_heating"
                            value="{{ old('tedgen_gas_heating', isset($installation->id) ? $installation->tedgen_gas_heating : '') }}"
                            readonly>
                    </label>
                </div>
                <!--validation-->
                @if ($errors->any())
                    <ul class="px-4 py-2 bg-red-100">
                        @foreach ($errors->all() as $error)
                            <li class="my-2 text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="pt-10 pb-10">
                    <button type="submit"
                        class="btn btn-primary btn-block float-end">{{ isset($installation->id) ? 'Update Installation' : 'Create Installation' }}</button>
                    <button type="button" onclick="window.location='{{ route('site.index') }}'"
                        class="btn btn-warning btn-block float-start">Go Back</button>
                </div>
                </form>
            </div>
            <div class="tab-pane fade" id="chp" role="tabpanel" aria-labelledby="chp-tab">
                @if (isset($installation->datalines) && count($installation->datalines) > 2)
                    <div class="col-md-12">
                        <h3><i class="fa fa-network-wired"></i> Installation Logger Lines</h3>
                        @foreach ($installation->datalines as $dataline)
                            <div class="card">
                                <div>
                                    <div class="float-start">
                                        <h5 class="card-title">{{ $dataline['line_reference'] }}</h5>
                                        <p class="card-text">Xero Account Code : {{ $dataline['xero_account_code'] }}
                                        </p>
                                    </div>
                                    <div class="float-end">
                                        <button type="button"
                                            onclick="window.location='{{ route('dataline.show', $dataline->id) }}'"
                                            class="btn btn-warning btn-block float-start">Edit Logger line</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div style="clear:both"></div>
        </div>
    </div>
@endsection
