@extends('titan::layouts.admin')

@section('content')
    @include('titan::admin.analytics.partials.analytics_header', ['activeUsers' => true])

    <div class="row">
        <div class="col-sm-12">
            @include('titan::admin.analytics.partials.visitors_views')
        </div>
    </div>

    {{-- locations + devices_category --}}
    <div class="row">
        <div class="col-md-7">
            @include('titan::admin.analytics.partials.locations')
        </div>
        <div class="col-md-5">
            @include('titan::admin.analytics.partials.devices_category')
        </div>
    </div>
@endsection