@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-{{ $item->status? $item->status->category:'primary' }}">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span>#{{ $item->order_number }} - N$ {{ $item->amount }} </span>
                        {{--<span class="pull-right"> {{ $item->status? $item->status->name:'' }}</span>--}}
                    </h3>
                </div>

                <div class="box-body no-padding">
                    <form>
                        <fieldset>
                            @if($item->status)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group pull-right">
                                            {{ $item->reference }}

                                            {!! $item->status->badge !!}
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <a target="_blank" href="/admin/shop/transactions/{{ $item->id }}/print" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Print Order">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>User</label>
                                        <input type="text" class="form-control" value="{{ $item->user->fullname }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" value="{{ $item->user->email }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cellphone</label>
                                        <input type="text" class="form-control" value="{{ $item->user->cellphone }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr/>

                            @if($item->shippingAddress)
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" value="{{ $item->shippingAddress->address }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Postal Code</label>
                                            <input type="text" class="form-control" value="{{ $item->shippingAddress->postal_code }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" value="{{ $item->shippingAddress->city }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Province</label>
                                            <input type="text" class="form-control" value="{{ $item->shippingAddress->province }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input type="text" class="form-control" value="{{ $item->shippingAddress->country }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <hr/>
                            @endif

                            @if($item->user->shippingAddress)
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" value="{{ $item->user->shippingAddress->address }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Postal Code</label>
                                            <input type="text" class="form-control" value="{{ $item->user->shippingAddress->postal_code }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" value="{{ $item->user->shippingAddress->city }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Province</label>
                                            <input type="text" class="form-control" value="{{ $item->user->shippingAddress->province }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input type="text" class="form-control" value="{{ $item->user->shippingAddress->country }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <hr/>
                            @endif

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Grand Total</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">N$</span>
                                            <input type="text" class="form-control" value="{{ $item->amount }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>EFT Payment Reference</label>
                                        <input type="text" class="form-control" value="{{ $item->reference }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Created At</label>
                                        <input type="text" class="form-control" value="{{ $item->created_at }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Items Total</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">N$</span>
                                            <input type="text" class="form-control" value="{{ $item->amount_items }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Handling Fee 12%</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">N$</span>
                                            <input type="text" class="form-control" value="{{ $item->amount_handling }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="col-md-4">
                                    <div class="form-group">
                                        <label>Output Tax 15%</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-link"></i></span>
                                            <input type="text" class="form-control" value="{{ $item->amount_tax }}" readonly>
                                        </div>
                                    </div>
                                </div>--}}
                            </div>

                            <hr/>

                            @include('admin.shop.product_list')
                        </fieldset>

                        @include('titan::admin.partials.form_footer', ['submit' => false])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection