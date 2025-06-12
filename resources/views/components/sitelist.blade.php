<table id="siteTable" class="display stripe">
    <thead class="bg-sky-700 text-white">
        <tr>
            <th>Site Name</th>
            <th>Company Name</th>
            <th>Address line 1</th>
            <th>Address line 2</th>
            <th>City</th>
            <th>Region</th>
            <th>Postcode</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sites as $site)
            <tr>
                <td>{{ $site->site_name }}</td>
                <td>{{ $site->account->customer_name }}</td>
                <td>{{ $site->address_1 }}</td>
                <td>{{ $site->address_2 }}</td>
                <td>{{ $site->city }}</td>
                <td>{{ $site->region }}</td>
                <td>{{ $site->postcode }}</td>
                <td><a href="{{ route('site.show', $site->id) }}" class="btn">View Details</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script type="module">
    initSiteTable();
</script>
