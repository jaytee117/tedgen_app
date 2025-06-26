@extends('theme.default')
@section('content')
    <div class="container-fluid px-4">
        <form action="{{ route('dataline.update', $dataline->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <h3><i class="fa fa-cog"></i> Dataline Info</h3>
                </div>
                <div class="col-md-6">

                </div>
                <input type="hidden" id="installation_id" name="installation_id"
                    value="{{ old('installation_id', $dataline->installation_id) }}">
                <label for="data_line_type" class="col-md-3">Dataline Type:
                    <select class="form-control" id="data_line_type" name="data_line_type" required>
                        <option value="" disabled selected>Select a type</option>
                        @foreach (App\Models\Dataline::$_data_line_type as $key => $value)
                            <option value="{{ $key }}" @if (isset($dataline) && $key == $dataline->data_line_type) selected @endif>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="line_reference" class="col-md-3">Line Reference:
                    <input class="form-control" type="text" id="line_reference" name="line_reference"
                        value="{{ old('line_reference', isset($dataline->id) ? $dataline->line_reference : '') }}" required>
                </label>

                <label for="x420_line_assignment" class="col-md-3">X420 Line Assignment:
                    <select class="form-control" id="x420_line_assignment" name="x420_line_assignment">
                        <option value="" disabled selected>Select a type</option>
                        @foreach (App\Models\Dataline::$_x420_line_assignment as $key => $value)
                            <option value="{{ $key }}" @if (isset($dataline) && $key == $dataline->x420_line_assignment) selected @endif>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="xero_account_code" class="col-md-3">Xero Accounting Code:
                    <input class="form-control" type="text" id="xero_account_code" name="xero_account_code"
                        value="{{ old('xero_account_code', isset($dataline->id) ? $dataline->xero_account_code : '') }}"
                        >
                </label>
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
                        class="btn btn-primary btn-block float-end">{{ isset($dataline->id) ? 'Update Dataline' : 'Create Installation' }}</button>
                    <button type="button" onclick="window.location='{{ route('installation.index') }}'"
                        class="btn btn-warning btn-block float-start">Go Back</button>
                </div>
            </div>
        </form>
    </div>
@endsection
