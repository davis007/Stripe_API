@extends('layouts.app')
@section('title','Make Card Token')

@section('content')
<div class="content text-white">
	{{ env('STRIPE_TEST_SECRET') }}
	<form method="POST" action="{{ url('test/payment') }}">
		@csrf
		<div class="form-group">
			<label for="number">カード番号</label>
			<input type="text" class="form-control" id="number" placeholder="1234 5678 9012 3456" value="4242424242424242">
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="cardExpirationMonth">有効期限（月）</label>
					<select class="form-control" id="cardExpirationMonth" name="exp_month">
						<option value="">月を選択</option>
						<option value="1">01</option>
						<option value="2">02</option>
						<option value="3">03</option>
						<option value="4">04</option>
						<option value="5" selected>05</option>
						<option value="6">06</option>
						<option value="7">07</option>
						<option value="8">08</option>
						<option value="9">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="cardExpirationYear">有効期限（年）</label>
					<select class="form-control" id="cardExpirationYear" name="exp_year">
						<option value="">年を選択</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
						<option value="2026">2026</option>
						<option value="2027">2027</option>
						<option value="2028" selected>2028</option>
						<option value="2029">2029</option>
						<option value="2030">2030</option>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="cardCVC">CVC</label>
			<input type="text" class="form-control" id="cardCVC" placeholder="CVC" name="cvc" value="314">
		</div>
		<button type="submit" class="btn btn-primary">支払う</button>
	</form>
</div>

@endsection
