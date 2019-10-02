@extends('titan::layouts.admin')

@section('content')
    {{-- devices + browsers --}}
    <div class="row">
        <div class="col-md-6">
            @include('titan::admin.analytics.partials.devices_category')
        </div>

        <div class="col-md-6">
            @include('titan::admin.analytics.partials.browsers')
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            @include('titan::admin.analytics.partials.devices')
        </div>
    </div>
@endsection