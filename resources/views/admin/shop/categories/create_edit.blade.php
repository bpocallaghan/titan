@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-edit"></i></span>
                        <span>{{ isset($item)? 'Edit the ' . $item->name . ' entry': 'Create a new Product Category' }}</span>
                    </h3>
                </div>

                <div class="box-body no-padding">

                    @include('titan::admin.partials.info')

                    <form method="POST" action="{{$selectedNavigation->url . (isset($item)? "/{$item->id}" : '')}}" accept-charset="UTF-8">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input name="_method" type="hidden" value="{{isset($item)? 'PUT':'POST'}}">

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
                                    <div class="form-group {{ form_error_class('parent_id', $errors) }}">
                                        <label for="parent">Parent</label>
                                        {!! form_select('parent_id', ([0 => 'Please select a Parent'] + $parents), ($errors && $errors->any()? old('parent_id') : (isset($item)? $item->parent_id : '')), ['class' => 'select2 form-control']) !!}
                                        {!! form_error_message('parent_id', $errors) !!}
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
@endsection