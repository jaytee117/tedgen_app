<x-app-layout>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true">Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false">Sites</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                role="tab" aria-controls="contact" aria-selected="false">Contact</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            @if (isset($customer->id))
                <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @else
                    <form action="{{ route('customer.store') }}" method="POST">
            @endif
            @csrf
            <h3>{{ isset($customer->id) ? 'Edit/View a Customer' : 'Create a New Customer' }}</h3>
            <div class="row">
                <label for="customer_name" class="col-md-3">Customer Name:
                    <input type="text" id="customer_name" name="customer_name"
                        value="{{ old('customer_name', isset($customer->id) ? $customer->customer_name : '') }}"
                        required>
                </label>

                <label for="company_number" class="col-md-3">Company Number:
                    <input type="text" id="company_number" name="company_number"
                        value="{{ old('company_number', isset($customer->id) ? $customer->company_number : '') }}"
                        required>
                </label>
                <label for="vat_number" class="col-md-3">VAT Number:
                    <input type="text" id="vat_number" name="vat_number"
                        value="{{ old('vat_number', isset($customer->id) ? $customer->vat_number : '') }}">
                </label>
            </div>
            <h3>Registered Company Address</h3>
            <div class="row">
                <label for="address_1" class="col-md-3">Address Line 1:
                    <input type="text" id="address_1" name="address_1"
                        value="{{ old('address_1', isset($customer->id) ? $customer->address_1 : '') }}" required>
                </label>
                <label for="address_2" class="col-md-3">Address Line 2:
                    <input type="text" id="address_2" name="address_2"
                        value="{{ old('address_2', isset($customer->id) ? $customer->address_2 : '') }}">
                </label>
                <label for="address_3" class="col-md-3">Address Line 3:
                    <input type="text" id="address_3" name="address_3"
                        value="{{ old('address_3', isset($customer->id) ? $customer->address_3 : '') }}">
                </label>
                <label for="city" class="col-md-3">City:
                    <input type="text" id="city" name="city"
                        value="{{ old('city', isset($customer->id) ? $customer->city : '') }}" required>
                </label>
                <label for="region" class="col-md-3">Region:
                    <input type="text" id="region" name="region"
                        value="{{ old('region', isset($customer->id) ? $customer->region : '') }}">
                </label>
                <label for="postcode" class="col-md-3">Postcode:
                    <input type="text" id="postcode" name="postcode"
                        value="{{ old('postcode', isset($customer->id) ? $customer->postcode : '') }}" required>
                </label>
                <label for="country" class="col-md-3">Country:
                    <input type="text" id="country" name="country"
                        value="{{ old('country', isset($customer->id) ? $customer->country : '') }}" required>
                </label>
            </div>
            <h3>Telephone Numbers</h3>
            <div class="row">
                <label for="telephone_1" class="col-md-3">Telephone Number:
                    <input type="text" id="telephone_1" name="telephone_1"
                        value="{{ old('telephone_1', isset($customer->id) ? $customer->telephone_1 : '') }}" required>
                </label>
                <label for="telephone_2" class="col-md-3">Second Telephone Number:
                    <input type="text" id="telephone_2" name="telephone_2"
                        value="{{ old('telephone_2', isset($customer->id) ? $customer->telephone_2 : '') }}">
                </label>
            </div>
            <button type="submit"
                class="btn-success mt-4 float-end">{{ isset($customer->id) ? 'Update Customer' : 'Create Customer' }}</button>
            <!--validation-->
            @if ($errors->any())
                <ul class="px-4 py-2 bg-red-100">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            </form>
            <button type="button" onclick="window.location='{{ route('customer.index') }}'"
                class="btn-red float-start">Cancel</button>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            @if (isset($customer))
                <a href="{{ route('site.create', $customer->id) }}" class="btn-success float-end">Add a Site</a>
                @include('components.sitelist', ['sites' => $customer->site])
            @endif


        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
    </div>
</x-app-layout>
