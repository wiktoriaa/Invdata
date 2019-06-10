@extends('layouts.dashboard')
@section('menu')
	<li class=""><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
	@if (checkPermission(['superadmin']))
	<li><a href="{{ url('users') }}"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Users</span></a></li>
	<li><a href="{{ url('rooms') }}"><i class="fas fa-door-open" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Rooms</span></a></li>
	<li><a href="{{ url('types') }}"><i class="fa fa-server" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Types</span></a></li>
	<li><a href="{{ url('import') }}"><i class="fas fa-file-csv" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Import</span></a></li>
	@endif
	<li><a href="{{ url('items') }}"><i class="fa fa-desktop" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Items</span></a></li>
	<li class="active"><a href="{{ url('scrap') }}"><i class="fa fa-database" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Scrap</span></a></li>
@stop
@section('content')

<h1>Scrap</h1>

@if (checkPermission(['user']))
	<h3>Room: {{ auth::user()->room->name }}</h3>
	<br>
@endif

<h4 class="bolder">Filters</h4>
{!! Form::open(['route' => 'scrap.create', 'method' => 'get']) !!}
<label for="type">Type:</label>
<select name="type" class="without-border">
	<option value="">All Types</option>
	@foreach ($types as $type)
		<option value="{{ $type->id }}">{{ $type->name }}</option>
	@endforeach
</select>
<br>
@if (checkPermission(['superadmin']))
	<label for="room">Room:</label>
	<select name="room" class="without-border">
		<option value="">All Rooms</option>
		@foreach ($rooms as $room)
			<option value="{{ $room->id }}">{{ $room->name }}</option>
		@endforeach
	</select> 
@endif

<br><br>
<input type="submit" name="" value="Apply" class="btn btn-secondary">
{!! Form::close() !!}
<br><br>


<table class="table">
	<thead>
		<tr>
			<th scope="col">Identifier</th>
			<th>Type</th>
			<th>Date</th>
			@if (checkPermission(['superadmin']))
				<th>Room</th>
				<th>Actions</th>
			@endif
		</tr>
	</thead>
	<tbody>
		@foreach($items as $item)
		<tr>
			<td>
				{{ $item->identifier }}
			</td>
			<td>
				{{ $item->type->name }}
			</td>
			<td>
				{{ $item->type->created_at }}
			</td>
			@if (checkPermission(['superadmin']))
				<td>
					{{ $item->room->name }}
				</td>
			<td>
				{!! Form::open(['route' => 'scrap.delete', 'method' => 'delete']) !!}
					<input type="hidden" name="id" value="{{ $item->id }}">
					<input type="submit" class="btn btn-danger" name="delete" value="Delete">
				{!! Form::close() !!}
         
			</td>
			@endif
		</tr>
		@endforeach
	</tbody>
</table>
{{ $items->links() }}

@stop