<?php
    $settings = DB::table('settings')->get();
?>
<table id="settings-table" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Public</th>
            <th>Valid</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($settings as $setting)
            <tr>
                <td>{{ $setting->id }}</td>
                <td>{{ $setting->name }}</td>
                <td>{{ $setting->public }}</td>
                <td>{{ $setting->valid }}</td>
                <td>@include('settings.partials.actions')</td>
            </tr>
        @endforeach
    </tbody>
</table>