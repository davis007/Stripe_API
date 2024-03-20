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
					<h4 class="mt-4">月別集計</h4>
					<table class="table text-white table-striped">
						<thead>
							<tr>
								<th>月</th>
								<th>合計金額</th>
							</tr>
						</thead>
						<tbody>
							@foreach($monthlySummaries as $summary)
								<tr>
									<td>{{ $summary->month }}月</td>
									<td>{{ number_format($summary->total_amount) }}円</td>
								</tr>
							@endforeach
						</tbody>
					</table>


					@if($sales->isNotEmpty())
						<table class="table text-white table-striped">
							@foreach($sales as $sale)
								<tr>
									<td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
									<td>{{ common::cusName($sale->customer_id) }}</td>
									@if(is_numeric($sale->amount))
									<td>{{ number_format($sale->amount) }}円</td>
									<td>
										<a href="{{ url('sales/refund/'.$sale->payment_log) }}" class="btn btn-danger confirms">返金</a>
									</td>
									@else
									<td>{{ $sale->amount }}</td>
									<td>
										-
									</td>
									@endif

								</tr>
							@endforeach
						</table>
						{{ $sales->links('paginate.bootstrap-4') }}
					@else
						<p class="text-white">売上データがありません。</p>
					@endif


				</div>
			</div>
		</div>
	</div>
</div>
@endsection
