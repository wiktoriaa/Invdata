@extends('layouts.dashboard')
@section('menu')
	<li class=""><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
	@if (checkPermission(['superadmin']))
	<li><a href="{{ url('users') }}"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Users</span></a></li>
	<li><a href="{{ url('rooms') }}"><i class="fas fa-door-open" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Rooms</span></a></li>
	<li class="active"><a href="{{ url('types') }}"><i class="fa fa-server" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Types</span></a></li>
	<li><a href="{{ url('import') }}"><i class="fas fa-file-csv" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Import</span></a></li>
	@endif
	<li><a href="{{ url('items') }}"><i class="fa fa-desktop" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Items</span></a></li>
	<li><a href="{{ url('scrap') }}"><i class="fa fa-database" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Scrap</span></a></li>
@stop
@section('content')

{!! Form::open(['route' => 'types.store']) !!}
<h1>Types</h1>

<div class="form-group">
	{!! Form::label('types', 'Type names separated by comma (computer,HDD,mouse)') !!}
	{!! Form::text('types', null, ['class' => 'form-control']) !!}
</div>


{!! Form::submit('Add type(s)', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}

<table class="table">
	<thead>
		<tr>
			<th scope="col">types</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($types as $type)
		<tr>
			<td>
				{{ $type->name }}
			</td>
			<td>
				{!! Form::open(['route' => 'types.delete', 'method' => 'delete']) !!}
					<input type="hidden" name="id" value="{{ $type->id }}">
					<input type="submit" class="btn btn-danger" value="Delete"> 
				{!! Form::close() !!}          
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
{{ $types->links() }}

@stop