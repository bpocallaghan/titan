@extends('titan::layouts.admin')

@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">
						<span><i class="fa fa-table"></i></span>
						<span>List All Cities</span>
					</h3>
				</div>

				<div class="box-body">

					@include('titan::admin.partials.info')

					@include('titan::admin.partials.toolbar')

					<table id="tbl-list" data-server="false" data-page-length="25" class="dt-table table nowrap table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
                            <th>Title</th>
                            <th>Province</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($items as $item)
							<tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->province->name }}</td>
                                <td>{{ $item->latitude }}</td>
                                <td>{{ $item->longitude }}</td>
                                <td>
                                    {!! action_row($selectedNavigation->url, $item->id, $item->title, ['show', 'edit', 'delete']) !!}
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
