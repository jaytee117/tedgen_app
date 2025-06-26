@extends('theme.default')
@section('content')
    <div class="container-fluid px-4">
        <table id="customerTable" class="display stripe">
            <thead class="bg-sky-700 text-white">
                <tr>
                    <th>Company Name</th>
                    <th>Company Number</th>
                    <th>VAT Number</th>
                    <th>Telephone</th>
                    <th>Secondary Telephone</th>
                    <th>Number of Sites</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->company_number }}</td>
                        <td>{{ $customer->vat_number }}</td>
                        <td>{{ $customer->telephone_1 }}</td>
                        <td>{{ $customer->telephone_2 }}</td>
                        <td>{{ $customer->site->count() }}</td>
                        <td><a href="{{ route('customer.show', $customer->id) }}" class="btn">View Details</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script type="module">
        initCustomerTable();
    </script>
@endsection
