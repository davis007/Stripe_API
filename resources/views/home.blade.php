@extends('layouts.app')
@section('title','HOME')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card bg-dark text-white">
				<div class="card-header">{{ __('Dashboard') }}</div>

				<div class="card-body">
					@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif

					ログインしました。
					<form action="{{ route('logout') }}" method="POST">
					@csrf
					<button type="submit" class="btn btn-primary">ログアウト</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
