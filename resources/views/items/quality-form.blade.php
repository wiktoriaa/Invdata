@extends('layouts.dashboard')
@section('menu')
	<li class=""><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
	@if (checkPermission(['superadmin']))
	<li><a href="{{ url('users') }}"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Users</span></a></li>
	<li><a href="{{ url('rooms') }}"><i class="fas fa-door-open" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Rooms</span></a></li>
	<li><a href="{{ url('types') }}"><i class="fa fa-server" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Types</span></a></li>
	<li><a href="{{ url('import') }}"><i class="fas fa-file-csv" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Import</span></a></li>
	@endif
	<li class="active"><a href="{{ url('items') }}"><i class="fa fa-desktop" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Items</span></a></li>
	<li><a href="{{ url('scrap') }}"><i class="fa fa-database" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Scrap</span></a></li>
@stop
@section('content')
	{!! Form::open(['route' => 'items.delete-precious']) !!}
	@csrf
	<h1>Reason for 
		@if ($action == 'scrap')
			transferring to scrap
		@else
			@if ($action == 'delete')
				deletion
			@else
				moving to another room ({{ $room_name }})
			@endif
		@endif
	</h1>

	<table class="table">
	<thead>
		<tr>
			<th scope="col">Identifier</th>
			<th>Type</th>
			<th>Room</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				{{ $item->identifier }}
			</td>
			<td>
				{{ $item->type->name }}
			</td>
			<td>
				{{ $item->room->name }}
			</td>
		</tr>
	</tbody>
</table>

	<div class="form-group">

		{!! Form::label('reason', 'Reason') !!}
		{!! Form::textarea('reason', null, ['class' => 'form-control']) !!}
		<input type="hidden" name="item-action" value="{{ $action }}">
		<input type="hidden" name="room" value="{{ $room }}">
		<input type="hidden" name="room_name" value="{{ $room_name }}">
		<input type="hidden" name="id" value="{{ $item->id }}">
		<br>
		{!! Form::submit('Confirm', ['class' => 'btn btn-primary']) !!}

	{!! Form::close() !!}
	</div>
@stop