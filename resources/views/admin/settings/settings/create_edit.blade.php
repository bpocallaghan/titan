@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-edit"></i></span>
                        <span>{{ isset($item)? 'Edit the ' . $item->title . ' entry': 'Create the Settings' }}</span>
                    </h3>
                </div>

                <div class="box-body no-padding">

                    @include('titan::admin.partials.info')

                    <form method="POST" action="{{$selectedNavigation->url . (isset($item)? "/{$item->id}" : '')}}" accept-charset="UTF-8">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input name="_method" type="hidden" value="{{isset($item)? 'PUT':'POST'}}">

                        <input name="zoom_level" type="hidden" value="{{ isset($item)? $item->zoom_level : old('zoom_level') }}" readonly/>
                        <input name="latitude" type="hidden" value="{{ isset($item)? $item->latitude : old('latitude') }}" readonly/>
                        <input name="longitude" type="hidden" value="{{ isset($item)? $item->longitude : old('longitude') }}" readonly/>

                        <fieldset>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('name', $errors) }}">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Please insert the Name" value="{{ ($errors && $errors->any()? old('name') : (isset($item)? $item->name : '')) }}">
                                        {!! form_error_message('name', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('slogan', $errors) }}">
                                        <label for="slogan">Slogan</label>
                                        <input type="text" class="form-control" id="slogan" name="slogan" placeholder="Please insert the Slogan" value="{{ ($errors && $errors->any()? old('slogan') : (isset($item)? $item->slogan : '')) }}">
                                        {!! form_error_message('slogan', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ form_error_class('description', $errors) }}">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="2" class="form-control">{{ ($errors && $errors->any()? old('description') : (isset($item)? $item->description : '')) }}</textarea>
                                {!! form_error_message('description', $errors) !!}
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('author', $errors) }}">
                                        <label for="author">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" placeholder="Please insert the Author" value="{{ ($errors && $errors->any()? old('author') : (isset($item)? $item->author : '')) }}">
                                        {!! form_error_message('author', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('keywords', $errors) }}">
                                        <label for="keywords">Keywords</label>
                                        <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Please insert the Keywords" value="{{ ($errors && $errors->any()? old('keywords') : (isset($item)? $item->keywords : '')) }}">
                                        {!! form_error_message('keywords', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('email', $errors) }}">
                                        <label for="email">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Please insert the Email" value="{{ ($errors && $errors->any()? old('email') : (isset($item)? $item->email : '')) }}">
                                        </div>
                                        {!! form_error_message('email', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('telephone', $errors) }}">
                                        <label for="telephone">Telephone</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
                                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Please insert the Telephone" value="{{ ($errors && $errors->any()? old('telephone') : (isset($item)? $item->telephone : '')) }}">
                                        </div>
                                        {!! form_error_message('telephone', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('cellphone', $errors) }}">
                                        <label for="cellphone">Cellphone</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                            <input type="text" class="form-control" id="cellphone" name="cellphone" placeholder="Please insert the Cellphone" value="{{ ($errors && $errors->any()? old('cellphone') : (isset($item)? $item->cellphone : '')) }}">
                                        </div>
                                        {!! form_error_message('cellphone', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('address', $errors) }}">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Please insert the Address" value="{{ ($errors && $errors->any()? old('address') : (isset($item)? $item->address : '')) }}">
                                        {!! form_error_message('address', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('po_box', $errors) }}">
                                        <label for="po_box">PO BOX</label>
                                        <input type="text" class="form-control" id="po_box" name="po_box" placeholder="Please insert the PO BOX" value="{{ ($errors && $errors->any()? old('po_box') : (isset($item)? $item->po_box : '')) }}">
                                        {!! form_error_message('po_box', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('facebook', $errors) }}">
                                        <label for="facebook">Facebook</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                                            <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Please insert the Facebook" value="{{ ($errors && $errors->any()? old('facebook') : (isset($item)? $item->facebook : '')) }}">
                                        </div>
                                        {!! form_error_message('facebook', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('twitter', $errors) }}">
                                        <label for="twitter">Twitter</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
                                            <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Please insert the Twitter" value="{{ ($errors && $errors->any()? old('twitter') : (isset($item)? $item->twitter : '')) }}">
                                        </div>
                                        {!! form_error_message('twitter', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('instagram', $errors) }}">
                                        <label for="instagram">Instagram</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-instagram"></i></span>
                                            <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Please insert the Instagram" value="{{ ($errors && $errors->any()? old('instagram') : (isset($item)? $item->instagram : '')) }}">
                                        </div>
                                        {!! form_error_message('instagram', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('googleplus', $errors) }}">
                                        <label for="googleplus">Googleplus</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-google-plus"></i></span>
                                            <input type="text" class="form-control" id="googleplus" name="googleplus" placeholder="Please insert the Googleplus" value="{{ ($errors && $errors->any()? old('googleplus') : (isset($item)? $item->googleplus : '')) }}">
                                        </div>
                                        {!! form_error_message('googleplus', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('linkedin', $errors) }}">
                                        <label for="linkedin">Linkedin</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-linkedin"></i></span>
                                            <input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="Please insert the Linkedin" value="{{ ($errors && $errors->any()? old('linkedin') : (isset($item)? $item->linkedin : '')) }}">
                                        </div>
                                        {!! form_error_message('linkedin', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ form_error_class('youtube', $errors) }}">
                                        <label for="youtube">Youtube</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-youtube"></i></span>
                                            <input type="text" class="form-control" id="youtube" name="youtube" placeholder="Please insert the Youtube" value="{{ ($errors && $errors->any()? old('youtube') : (isset($item)? $item->youtube : '')) }}">
                                        </div>
                                        {!! form_error_message('youtube', $errors) !!}
                                    </div>
                                </div>
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
            <div class="box box-solid">
                <div class="box-header with-border bg-light-blue">
                    <h3 class="box-title">
                        <span><i class="fa fa-map-marker"></i></span>
                        <span>Google Map</span>
                    </h3>
                </div>

                <div class="box-body no-padding">
                    <input id="pac-input" class="controls" type="text" placeholder="Enter Address">
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_map_key') }}&libraries=places"></script>
    <script type="text/javascript" charset="utf-8">
        $(function () {
            initSummerNote('.summernote');

            var latitude = {{ isset($item) && strlen($item->latitude) > 2? $item->latitude : -30 }};
            var longitude = {{ isset($item) && strlen($item->longitude) > 2? $item->longitude : 24 }};
            var zoom_level = {{ isset($item) && strlen($item->zoom_level) >= 1? $item->zoom_level : 6 }};

            initGoogleMapEditMarker('map_canvas', latitude, longitude, zoom_level);
        })
    </script>
@endsection