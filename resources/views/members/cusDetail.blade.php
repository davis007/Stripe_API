@extends('layouts.app')
@section('title','Basics')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header"><h2 class="h4">ユーザー情報詳細</h2></div>
				<div class="card-body">
					@if(session('msg'))
						<div class="alert alert-success" role="alert">
							{{ session('msg') }}
						</div>
					@endif

					<!-- 個別データ -->
					<div class="row mb-3">
						<div class="col-md-3">Name:</div>
						<div class="col-md-9">{{ $cus->name }}</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">Customer ID:</div>
						<div class="col-md-9">{{ $cus->customer_id }}</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">Shop Code:</div>
						<div class="col-md-9">{{ $cus->shopCode }}</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">Email:</div>
						<div class="col-md-9">{{ $cus->email }}</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">Created_at:</div>
						<div class="col-md-9">{{ $cus->created_at->format('Y-m-d H:i') }}</div>
					</div>

					<!-- 繰り返しデータ -->
					<h2 class="h4 mt-5">購入履歴</h2>
					<table class="table table-striped table-dark">
						<thead>
							<tr>
								<th>Payment ID</th>
								<th>Customer ID</th>
								<th>Amount</th>
								<th>Created At</th>
							</tr>
						</thead>
						<tbody>
							@foreach($pay as $payd)
								<tr>
									<td>{{ $payd->payment_log }}</td>
									<td>{{ $payd->customer_id }}</td>
									<td>{{ number_format($payd->amount) }}円</td>
									<td>{{ $payd->created_at->format('Y-m-d H:i') }}</td>
									<td><a href="{{ url('sales/refund/'.$payd->payment_log) }}" class="btn btn-danger confirms">返金</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('addSomething')
@endsection
