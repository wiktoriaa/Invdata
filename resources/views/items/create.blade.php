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

{!! Form::open(['route' => 'items.store']) !!}
@csrf
<h1>Add item</h1>

<div class="form-group">

	{!! Form::label('identifier', 'Identifier') !!}
	{!! Form::text('identifier', null, ['class' => 'form-control']) !!}
	<br>

	{!! Form::label('type_id', 'Type') !!}
	<select name="type_id" class="form-control">
		@foreach ($types as $type)
			<option value="{{ $type->id }}">{{ $type->name }}</option>
		@endforeach
	</select>
	<br>

	<label for="quality">Quality</label>
	<select name="quality" class="form-control">
		<option value="0" selected>Default</option>
		<option value="1">Precious</option>
	</select>
	<br>

	@if (checkPermission(['superadmin']))
		{!! Form::label('room_id', 'Room') !!}
		<select name="room_id" class="form-control">
			@foreach ($rooms as $room)
				<option value="{{ $room->id }}">{{ $room->name }}</option>
			@endforeach
		</select>
	@endif

</div>

{!! Form::submit('Add item', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
<br><br>
<h1>All items</h1>

@if (checkPermission(['user']))
	<h3>Room: {{ auth::user()->room->name }}</h3>
	<br>
@endif

<h4 class="bolder">Filters</h4>
{!! Form::open(['route' => 'items.create', 'method' => 'get']) !!}
<label for="type">Type:</label>
<select name="type" class="without-border">
	<option value="">All Types</option>
	@foreach ($types as $type)
		<option value="{{ $type->id }}">{{ $type->name }}</option>
	@endforeach
</select>
<br>
<label for="quality">Quality</label>
<select name="quality" class="without-border">
	<option value="">All Quality</option>
	<option value="0">Default</option>
	<option value="1">Precious</option>
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


<div class="form-group row bolder">
	<div class="col-md-2 pad-10">Identifier</div>
	<div class="col-md-2 pad-10">Type</div>
	<div class="col-md-2 pad-10">Quality</div>
	@if (checkPermission(['superadmin']))
	<div class="col-md-2 pad-10">Room</div>
	<div class="col-md-2 pad-10"></div>
	@endif
</div>
<hr>
@foreach($items as $item)
<div class="form-group row pad-10">
	<div class="col-md-2 pad-10">
		{{ $item->identifier }}
	</div>
	<div class="col-md-2 pad-10">
		{{ $item->type->name }}
	</div>
	<div class="col-md-2 pad-10">
		{{ $item->quality() }}
	</div>
	@if (checkPermission(['superadmin']))
	{!! Form::open(['route' => 'items.delete', 'method' => 'delete', 'class' => 'delete']) !!}
		@csrf
	<div class="col-md-2 pad-10">
		<select name="room" class="without-border">
			<option value="{{ $item->room->id }}">{{ $item->room->name }}</option>
			@foreach ($rooms as $room)
				<option value="{{ $room->id }}">{{ $room->name }}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-2 pad-10">
		<input type="hidden" name="id" value="{{ $item->id }}">
		<input type="submit" class="btn btn-primary space" name="move" value="Move">
		<input type="submit" class="btn btn-primary space" name="scrap" value="Scrap">
		<input type="submit" class="btn btn-danger space" name="delete" value="Delete">
	</div>
	{!! Form::close() !!}
	@endif
</div>
<hr>
@endforeach

{{ $items->links() }}

@stop