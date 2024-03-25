@extends('layouts.app')
@section('title','Customers')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">Customers</div>
				@if(session('msg'))
					<div class="alert alert-success">
						{{ session('msg') }}
					</div>
				@endif

				<div class="card-body">
					@if(session('msg'))
						<div class="alert alert-success" role="alert">
							{{ session('msg') }}
						</div>
					@endif
					<form action="{{ url('create/customer') }}" method="post">
						@csrf
						<div class="form-group">
							<label for="name">名前</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="名前を入力してください">
						</div>
						<!-- メールアドレス入力フィールド -->
						<div class="form-group">
							<label for="mailaddress">メールアドレス</label>
							<input type="email" class="form-control" id="mailaddress" name="mailaddress" placeholder="メールアドレスを入力してください">
						</div>
						<!-- 送信ボタン -->
						<button type="submit" class="btn btn-primary">送信</button>
					</form>
					@if($cust)
						<table class="table text-white table-striped mt-5">
							@foreach($cust as $cus)
								<tr>
									<td>{{ $cus->name }}</td>
									<td>{{ $cus->email }}</td>
									<td>{{ $cus->created_at }}</td>
									<td>
										<a href="{{ url('customer/details/'.$cus->id) }}" class="btn btn-primary">詳細</a>
										<a href="{{ url('/payment/'.$cus->shopCode.'/2500/userPayment/'.$cus->customer_id) }}" class="btn btn-info" target="_blank">PayLink</a>
										@if($cus->customer_id)
											<a href="{{ url('delete/customer/'.$cus->customer_id) }}" class="btn btn-danger confirm ">削除</a>
										@endif
									</td>
								</tr>
							@endforeach
						</table>
					@else
						<p>顧客は登録されていません。</p>
					@endif
					{{ $cust->links('paginate.bootstrap-4') }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
