@extends('layouts.app')
@section('title','Customers')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">Customers</div>

				<div class="card-body">
					@if(session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif
					<table class="table text-white table-striped">
					@foreach ($cust as $cus)
						<tr>
							<td>{{ $cus->name }}</td>
							<td>{{ $cus->email }}</td>
							<td>{{ $cus->created_at }}</td>
							<td>
								<a href="" class="btn btn-primary">詳細</a>
								<a href="" class="btn btn-danger confirm ">削除</a>
							</td>
						</tr>
					@endforeach
					</table>
					{{ $cust->links('paginate.bootstrap-4') }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
