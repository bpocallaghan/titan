@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-edit"></i></span>
                        <span>{{ isset($item)? 'Edit the ' . $item->title . ' entry': 'Create a new City' }}</span>
                    </h3>
                </div>

                <div class="box-body no-padding">

                    @include('titan::admin.partials.info')

                    <form id="form-edit" method="post" action="{{ $selectedNavigation->url . (isset($item)? '/' . $item->id : '') }}">                        {!! csrf_field() !!}                        {!! method_field(isset($item)? 'put':'post') !!}

                        <input name="zoom_level" type="hidden" value="{{ isset($item)? $item->zoom_level : old('zoom_level') }}" readonly/>
                        <input name="latitude" type="hidden" value="{{ isset($item)? $item->latitude : old('latitude') }}" readonly/>
                        <input name="longitude" type="hidden" value="{{ isset($item)? $item->longitude : old('longitude') }}" readonly/>

                        <fieldset>
                            <div class="row">
                                <section class="col col-6">
                                    <div class="form-group {{ form_error_class('name', $errors) }}">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Please insert the Name" value="{{ ($errors && $errors->any()? old('name') : (isset($item)? $item->name : '')) }}">
                                        {!! form_error_message('name', $errors) !!}
                                    </div>
                                </section>

                                <section class="col col-6">
                                    <section class="form-group {{ form_error_class('city_id', $errors) }}">
                                        <label for="city_id">City</label>
                                        {!! form_select('city_id', ([0 => 'Please select a City'] + $cities), ($errors && $errors->any()? old('city_id') : (isset($item)? $item->city_id : '')), ['class' => 'select2 form-control']) !!}
                                        {!! form_error_message('city_id', $errors) !!}
                                    </section>
                                </section>
                            </div>
                        </fieldset>

                        @include('titan::admin.partials.form_footer')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-map-marker"></i></span>
                        <span>Google Map</span>
                    </h3>
                </div>

                <div class="box-body no-padding">
                    <div id="map_canvas" class="google_maps" style="height: 450px;">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_map_key') }}"></script>
    <script type="text/javascript" charset="utf-8">
        $(function () {
            var latitude = {{ isset($item) && strlen($item->latitude) > 2? $item->latitude : -30 }};
            var longitude = {{ isset($item) && strlen($item->longitude) > 2? $item->longitude : 24 }};
            var zoom_level = {{ isset($item) && strlen($item->zoom_level) >= 1? $item->zoom_level : 6 }};

            initGoogleMapEditClean('map_canvas', latitude, longitude, zoom_level);
        })
    </script>
@endsection
