@extends('layouts.app')
@section('title','Sales')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">Sales</div>

				<div class="card-body">
					@if(session('msg'))
						<div class="alert alert-success" role="alert">
							{{ session('msg') }}
						</div>
					@endif
					@if($sales->isNotEmpty())
					<table class="table text-white table-striped">
					@foreach($sales as $sale)
						<tr>
							<td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
							<td>{{ $sale->customer_id }}</td>
							<td>{{ $sale->amount }}</td>
							<td>
								<a href="#delete" class="btn btn-danger confirms">返金</a>
							</td>
						</tr>
					@endforeach
					</table>
					@else
					<p class="text-white">売上データがありません。</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
