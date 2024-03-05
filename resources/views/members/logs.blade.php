@extends('layouts.app')
@section('title','Sales')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">API Logs</div>

				<div class="card-body">
					@if(session('msg'))
						<div class="alert alert-success" role="alert">
							{{ session('msg') }}
						</div>
					@endif
					@if(!$logs)
					<p>データがありません</p>
					@else
					<table class="table table-striped text-white">
					<tr>
						<th>操作</th>
						<th>目的</th>
						<th>補足</th>
						<th>時間</th>
					</tr>
					@foreach ($logs as $log)
						<tr>
							<td>{{ $log->type }}</td>
							<td>{{ $log->operate }}</td>
							<td>{{ $log->memo ? $log->memo : '-' }}</td>
							<td>{{ $log->created_at->format('Y-m-d H:i')}}</td>
						</tr>
					@endforeach
					</table>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
