@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-edit"></i></span>
                        <span>{{ isset($item)? 'Edit the ' . $item->name . ' entry': 'Create a new Product' }}</span>
                    </h3>
                </div>

                <div class="box-body no-padding">

                    @include('titan::admin.partials.info')

                    <form method="POST" action="{{$selectedNavigation->url . (isset($item)? "/{$item->id}" : '')}}" accept-charset="UTF-8">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input name="_method" type="hidden" value="{{isset($item)? 'PUT':'POST'}}">

                        <fieldset>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group {{ form_error_class('name', $errors) }}">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Please insert the Name" value="{{ ($errors && $errors->any()? old('name') : (isset($item)? $item->name : '')) }}">
                                        {!! form_error_message('name', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="in_stock" name="in_stock" {{ ($errors && $errors->any()? (old('in_stock')? 'checked="checked"':'') :  (isset($item) && $item->in_stock? 'checked="checked"':'checked="checked"')) }}>
                                            <i></i> In Stock
                                        </label>
                                        {!! form_error_message('in_stock', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('reference', $errors) }}">
                                        <label for="reference">Reference (Code)</label>
                                        <input type="text" class="form-control" id="reference" name="reference" placeholder="Please insert the Reference" value="{{ ($errors && $errors->any()? old('reference') : (isset($item)? $item->reference : '')) }}">
                                        {!! form_error_message('reference', $errors) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('amount', $errors) }}">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">N$</span>
                                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Please insert the Amount" value="{{ ($errors && $errors->any()? old('amount') : (isset($item)? $item->amount : '')) }}">
                                        </div>
                                        {!! form_error_message('amount', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ form_error_class('category_id', $errors) }}">
                                        <label for="category">Category</label>
                                        {!! form_select('category_id', ([0 => 'Please select a Category'] + $categories), ($errors && $errors->any()? old('category_id') : (isset($item)? $item->category_id : '')), ['class' => 'select2 form-control']) !!}
                                        {!! form_error_message('category_id', $errors) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ form_error_class('content', $errors) }}">
                                <label for="content-content">Content</label>
                                <textarea class="form-control summernote" id="content-content" name="content" rows="18" placeholder="Please insert the Content">{{ ($errors && $errors->any()? old('content') : (isset($item)? $item->content : '')) }}</textarea>
                                {!! form_error_message('content', $errors) !!}
                            </div>
                        </fieldset>

                        @include('titan::admin.partials.form_footer')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($item))
        @include('titan::admin.photos.photoable', ['photoable' => $item, 'photos' => $item->photos])
    @endif
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" charset="utf-8">
        $(function () {
            initSummerNote('.summernote');
//            $('#form-edit').on('submit', function () {
//                $('#content-content').html($('.summernote').val());
//                return true;
//            });
        })
    </script>
@endsection