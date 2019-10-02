@extends('titan::layouts.admin')

@section('content')
    {{-- demographics --}}
    <div class="row">
        <div class="col-md-6">
            @include('titan::admin.analytics.partials.gender')
        </div>

        <div class="col-md-6">
            @include('titan::admin.analytics.partials.age')
        </div>
    </div>
@endsection