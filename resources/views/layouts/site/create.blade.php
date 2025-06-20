<x-app-layout>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true">Site Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="chp-tab" data-bs-toggle="tab" data-bs-target="#chp" type="button" role="tab"
                aria-controls="chp" aria-selected="false">GenSet Installation</button>
        </li>
        <!--<li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                role="tab" aria-controls="contact" aria-selected="false">Contact</button>
        </li>-->
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            @if (isset($customer) && !isset($site))
            <form action="{{ route('site.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                @if (isset($site))
                <form action="{{ route('site.update', $site->id) }}" method="POST" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <h3>{{ isset($site->id) ? 'Edit/View a Site' : 'Create a New Site' }}</h3>
                    <div class="row">
                        @if (isset($site))
                        <div class="col-md-3" id="imageUploader">
                            @if (isset($site->site_img))
                            <img src="{{ $site->getImageURL() }}" alt="Uploaded File" id="preview">
                            @else
                            <img src="https://dummyimage.com/380/ffffff/000000" id="preview">
                            @endif
                            <label for="site_img">Upload an Image</label>
                            <input type="file" id="site_img" name="site_img"
                                value="{{ old('site_img', isset($site->id) ? $site->site_img : '') }}" class="hidden"
                                onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]);document.getElementById('preview').style.display = 'block';">

                        </div>
                        @endif
                        <div class="col-md-9">
                            <div class="row">
                                <input type="hidden" id="account_id" name="account_id"
                                    value="{{ old('account_id', isset($site->id) ? $site->account_id : $customer->id) }}"
                                    required>

                                <label for="site_name" class="col-md-4">Site Name:
                                    <input type="text" id="site_name" name="site_name"
                                        value="{{ old('site_name', isset($site->id) ? $site->site_name : '') }}"
                                        required>
                                </label>
                                <label for="site_telephone" class="col-md-4">Site Telephone:
                                    <input type="text" id="site_telephone" name="site_telephone"
                                        value="{{ old('site_telephone', isset($site->id) ? $site->site_telephone : '') }}">
                                </label>
                                <h3 class="mt-4">Site Address</h3>
                                <label for="address_1" class="col-md-4">Address Line 1:
                                    <input type="text" id="address_1" name="address_1"
                                        value="{{ old('address_1', isset($site->id) ? $site->address_1 : '') }}">
                                </label>
                                <label for="address_2" class="col-md-4">Address Line 2:
                                    <input type="text" id="address_2" name="address_2"
                                        value="{{ old('address_2', isset($site->id) ? $site->address_2 : '') }}">
                                </label>
                                <label for="city" class="col-md-4">City/Town:
                                    <input type="text" id="city" name="city"
                                        value="{{ old('city', isset($site->id) ? $site->city : '') }}">
                                </label>
                                <label for="region" class="col-md-4">Region:
                                    <input type="text" id="region" name="region"
                                        value="{{ old('region', isset($site->id) ? $site->region : '') }}">
                                </label>
                                <label for="postcode" class="col-md-4">Postcode:
                                    <input type="text" id="postcode" name="postcode"
                                        value="{{ old('postcode', isset($site->id) ? $site->postcode : '') }}">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="mt-4">Extra Data</h3>
                        <label for="lat" class="col-md-3">Latitude:
                            <input type="text" id="lat" name="lat"
                                value="{{ old('lat', isset($site->id) ? $site->lat : '') }}">
                        </label>
                        <label for="lng" class="col-md-3">Longitude:
                            <input type="text" id="lng" name="lng"
                                value="{{ old('lng', isset($site->id) ? $site->lng : '') }}">
                        </label>
                        <label for="current_temp" class="col-md-3">Current Temp:
                            <input type="text" id="current_temp" name="current_temp"
                                value="{{ old('current_temp', isset($site->id) ? $site->current_temp : '') }}">
                        </label>
                        <label for="weather_icon" class="col-md-3">Weather Icon:
                            <input type="text" id="weather_icon" name="weather_icon"
                                value="{{ old('weather_icon', isset($site->id) ? $site->weather_icon : '') }}">
                        </label>
                    </div>

                    <button type="submit" class="btn mt-4 float-end">{{ isset($site->id) ? 'Update Site' : 'Create Site'
                        }}</button>
                    <!--validation-->
                    @if ($errors->any())
                    <ul class="px-4 py-2 bg-red-100">
                        @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif
                </form>
                <button type="button" onclick="window.location='{{ route('site.index') }}'"
                    class="btn-red float-start">Cancel</button>
        </div>
        <div class="tab-pane fade" id="chp" role="tabpanel" aria-labelledby="chp-tab">
            CHP
        </div>
    </div>
</x-app-layout>