@extends('layouts.dashboard')
@section('menu')
	<li class=""><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
	@if (checkPermission(['superadmin']))
	<li class="active"><a href="{{ url('users') }}"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Users</span></a></li>
	<li><a href="{{ url('rooms') }}"><i class="fas fa-door-open" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Rooms</span></a></li>
	<li><a href="{{ url('types') }}"><i class="fa fa-server" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Types</span></a></li>
	<li><a href="{{ url('import') }}"><i class="fas fa-file-csv" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Import</span></a></li>
	@endif
	<li><a href="{{ url('items') }}"><i class="fa fa-desktop" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Items</span></a></li>
	<li><a href="{{ url('scrap') }}"><i class="fa fa-database" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Scrap</span></a></li>@stop
@section('content')
<h1>Users</h1>
<form method="POST" action="{{ route('users.store') }}">
	@csrf
	<div class="form-group row">
		<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

		<div class="col-md-6">
			<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

			@error('name')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror
		</div>
	</div>

	<div class="form-group row">
		<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

		<div class="col-md-6">
			<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

			@error('email')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror
		</div>
	</div>

	<div class="form-group row">
		<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

		<div class="col-md-6">
			<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

			@error('password')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror
		</div>
	</div>

	<div class="form-group row">
		<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

		<div class="col-md-6">
			<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
		</div>
	</div>

	<div class="form-group row">
		<label for="is_super" class="col-md-4 col-form-label text-md-right">Role:</label>
		<div class="col-md-6 ">
			<select name="is_super" id="user_role" class="form-control">
				<option value="0">User</option>
				<option value="1">Superadmin</option>
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label for="room_id" class="col-md-4 col-form-label text-md-right">Room:</label>
		<div class="col-md-6" id="user_room">
			<select name="room_id" class="form-control">
				<option value="">All Rooms</option>
				@foreach ($rooms as $room)
				<option value="{{ $room->id }}">{{ $room->name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row mb-0">
		<div class="col-md-6 offset-md-4">
			<button type="submit" class="btn btn-primary">
				{{ __('Add user') }}
			</button>
		</div>
	</div>
</form>
<br><br>

<div class="form-group row bolder">
	<div class="col-md-2 pad-10">User</div>
	<div class="col-md-2 pad-10">Email</div>
	<div class="col-md-2 pad-10">Room</div>
	<div class="col-md-2 pad-10">Role</div>
	<div class="col-md-2 pad-10"></div>
</div>
<hr>
		@foreach($users as $user)
		<div class="form-group row pad-10">
			{!! Form::open(['route' => 'users.update', 'method' => 'put']) !!}
			<div class="col-md-2 pad-10">
				<input type="hidden" name="id" value="{{ $user->id }}">
				<input type="text" class="without-border" name="name" value="{{ $user->name }}">
			</div>
			<div class="col-md-2 pad-10">
				<input type="text" class="without-border" name="email" value="{{ $user->email }}">
			</div>
			<div class="col-md-2 pad-10">
				<select name="room_id" class="without-border">
					@foreach ($rooms as $room)
						@if ($user->is_super == '0')
							@if ($room->id == $user->room->id)
								<option value="{{ $room->id }}" selected>{{ $room->name }}</option>
							@else
								<option value="{{ $room->id }}">{{ $room->name }}</option>
							@endif
						@else
							<option value="">All rooms</option>
							<option value="{{ $room->id }}">{{ $room->name }}</option>
						@endif
					@endforeach
				</select>
			</div>
			<div class="col-md-2 pad-10">
				@if ($user->id != auth::user()->id)
					<select name="is_super" class="without-border">
						@if (getMyPermission($user->is_super) == 'superadmin')
							<option value="0">user</option>
							<option value="1" selected>superadmin</option>
						@else
							<option value="0" selected>user</option>
							<option value="1">superadmin</option>
						@endif
					</select>
				@endif
			</div>
			<div class="col-md-2 pad-10">
				<input type="submit" class="btn btn-primary space" value="Update">
				{!! Form::close() !!}

				@if ($user->id != auth::user()->id)
					{!! Form::open(['route' => 'users.delete', 'method' => 'delete']) !!}
					<input type="hidden" name="id" value="{{ $user->id }}">
					<input type="submit" class="btn btn-danger space" value="Delete"> 
					{!! Form::close() !!}
				@endif 
			</div>      

		</div>
		<hr>
		@endforeach

{{ $users->links() }}

@stop