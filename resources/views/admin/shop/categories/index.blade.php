@extends('titan::layouts.admin')

@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">
						<span><i class="fa fa-table"></i></span>
						<span>List All Product Categories</span>
					</h3>
				</div>

				<div class="box-body">

					@include('titan::admin.partials.info')

                    @include('titan::admin.partials.toolbar', ['order' => true])

					<table id="tbl-list" data-server="false" class="dt-table table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th>Product Category</th>
                            <th>Url</th>
                            <th>Parent</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($items as $item)
							<tr>
								<td>{{ $item->name }}</td>
                                <td>{!! $item->url !!}</td>
                                <td>{{ ($item->parent)? $item->parent->name : '-' }}</td>
								<td>{!! action_row($selectedNavigation->url, $item->id, $item->name, ['edit', 'delete']) !!}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection