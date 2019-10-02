@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-table"></i></span>
                        <span>List All Products</span>
                    </h3>
                </div>

                <div class="box-body">

                    @include('titan::admin.partials.info')

                    @include('titan::admin.partials.toolbar')

                    <table id="tbl-list" data-server="false" data-page-length="25" class="dt-table table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Amount</th>
                            <th>Cover Photo</th>
                            <th>Category</th>
                            <th>Views</th>
                            <th>Purchases</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->reference }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>
                                    @if($item->cover_photo)
                                        <a target="_blank" href="{{ $item->cover_photo->url }}">
                                            <img style="height: 50px;" src="{{ $item->cover_photo->thumbUrl }}" title="{{ $item->cover_photo->name }}">
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $item->category_and_parent }}</td>
                                <td>{{ $item->total_views }}</td>
                                <td>{{ $item->total_purchases }}</td>
                                <td>
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <a href="/admin/photos/products/{{ $item->id }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="Add Photos to {{ $item->name }}">
                                                <i class="fa fa-image"></i>
                                            </a>
                                        </div>
                                        {!! action_row($selectedNavigation->url, $item->id, $item->name, [/*'show', */'edit', 'delete'], false) !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection