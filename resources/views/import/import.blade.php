@extends('layouts.dashboard')
@section('menu')
	<li class=""><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
	@if (checkPermission(['superadmin']))
	<li><a href="{{ url('users') }}"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Users</span></a></li>
	<li><a href="{{ url('rooms') }}"><i class="fas fa-door-open" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Rooms</span></a></li>
	<li><a href="{{ url('types') }}"><i class="fa fa-server" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Types</span></a></li>
	<li class="active"><a href="{{ url('import') }}"><i class="fas fa-file-csv" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Import</span></a></li>
	@endif
	<li><a href="{{ url('items') }}"><i class="fa fa-desktop" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Items</span></a></li>
	<li><a href="{{ url('scrap') }}"><i class="fa fa-database" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Scrap</span></a></li>
@stop
@section('content')
	<h1>Import from file</h1>
	<h3>Using this form you can import data from .csv file. All whitespaces will be removed.</h3><br>
		<h4>Syntax for add items:</h4>
		identifier,type_name,room_name,quality<br>
		identifier,type_name,room_name,quality<br><br>

		<h4>Syntax for move items:</h4>
		identifier,type_name,room_name,new_room_name<br>
		identifier,type_name,room_name,new_room_name<br><br>

		<h4>Important:</h4>
		<ul>
			<li>You cannot delete there precious items.</li>
			<li>If room or type does't exist, the record is skipped</li>
		</ul>
		<br>

		<h4>Data type:</h4>
		identifier - text<br>
		type_name - text<br>
		room_name - text<br>
		quality - number (0 or 1)<br><br><br>

		<form method='post' action='/import' enctype='multipart/form-data' >
	       {{ csrf_field() }}
	       <input type='file' name='file' >
	       <br>
	       <input type='submit' class="btn btn-primary" name='add-items' value='Add items'>
	       <input type='submit' class="btn btn-primary" name='move-rooms' value='Move items'>
	     </form>
@stop