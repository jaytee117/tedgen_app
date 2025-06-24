<table id="installationTable" class="display stripe">
    <thead class="bg-sky-700 text-white">
        <tr>
            <th>Asset ID</th>
            <th>Machine Type</th>
            <th>Machine Status</th>
            <th>Logger Type</th>
            <th>Xero Contact ID</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($installations as $install)
            <tr>
                <td>{{ $install->asset_id }}</td>
                <td>{{ App\Models\Installation::$_machine_type[$install->machine_type] }}</td>
                <td>{{ App\Models\Installation::$_machine_status[$install->machine_status] }}</td>
                <td>{{ App\Models\Installation::$_logger_type[$install->logger_type] }}</td>
                <td>{{ $install->xero_id }}</td>
                <td><a href="{{ route('installation.show', $install->id) }}" class="btn">View Details</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script type="module">
    initInstallationTable();
</script>
