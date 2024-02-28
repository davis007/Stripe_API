@extends('layouts.app')
@section('title','Make Card Token')

@section('content')
<div class="content text-white">

	<div class="col-6 card bg-dark">
		<div class="card-header"><h2>Payment</h2></div>
		<div class="card-body">
			<form id="card-form" action="{{ url('test/payment') }}" method="POST" lang="ja">
				@csrf

				<!-- 登録のみ / 決済のラジオボタン -->
				<div class="form-group">
					<label>登録オプション</label>
					<div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="registration_option" id="registrationOnly" value="registrationOnly" checked>
							<label class="form-check-label" for="registrationOnly" value="regist_only">登録のみ</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="registration_option" id="payment" value="payment">
							<label class="form-check-label" for="payment">決済</label>
						</div>
					</div>
				</div>

				<!-- ユーザー名 -->
				<div class="form-group">
					<label for="user_name">ユーザー名</label>
					<input type="text" class="form-control" id="user_name" name="user_name" placeholder="user_name">
				</div>
				<!-- メールアドレス -->
				<div class="form-group">
					<label for="mailaddress">メールアドレス</label>
					<input type="text" class="form-control" id="mailaddress" name="mailaddress" placeholder="email">
				</div>
				<!-- 決済金額入力 -->
				<div class="form-group">
					<label for="payment_amount">決済金額 (JPY)</label>
					<input type="number" class="form-control" id="payment_amount" name="amount" placeholder="金額を入力(円)">
				</div>

				<div>
					<label for="card_number">カード番号</label>
					<div id="card-number" class="form-control"></div>
				</div>

				<div>
					<label for="card_expiry">有効期限</label>
					<div id="card-expiry" class="form-control"></div>
				</div>

				<div>
					<label for="card-cvc">セキュリティコード</label>
					<div id="card-cvc" class="form-control"></div>
				</div>

				<div id="card-errors" class="text-danger"></div>

				<button class="mt-3 btn btn-primary ">支払い</button>
			</form>
		</div>
	</div>

</div>

@endsection
@section('addSomething')
<script src="https://js.stripe.com/v3/"></script>
<script>
	const stripe = Stripe('{{ env('STRIPE_KEY_TEST') }}');
	const elements = stripe.elements();

	var cardNumber = elements.create('cardNumber');
	cardNumber.mount('#card-number');
	cardNumber.on('change', function (event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});

	var cardExpiry = elements.create('cardExpiry');
	cardExpiry.mount('#card-expiry');
	cardExpiry.on('change', function (event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});

	var cardCvc = elements.create('cardCvc');
	cardCvc.mount('#card-cvc');
	cardCvc.on('change', function (event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});

	var form = document.getElementById('card-form');
	form.addEventListener('submit', function (event) {
		event.preventDefault();
		var errorElement = document.getElementById('card-errors');
		if (event.error) {
			errorElement.textContent = event.error.message;
		} else {
			errorElement.textContent = '';
		}

		stripe.createToken(cardNumber).then(function (result) {
			if (result.error) {
				errorElement.textContent = result.error.message;
			} else {
				stripeTokenHandler(result.token);
			}
		});
	});

	function stripeTokenHandler(token) {
		var form = document.getElementById('card-form');
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);
		form.submit();
	}

</script>

@endsection
