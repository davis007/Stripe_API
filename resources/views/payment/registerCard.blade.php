<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>クレジットカード登録</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<script src="https://js.stripe.com/v3/"></script>
</head>
<body>

	<div class="container mt-5">
		@if(session('msg'))
			<div class="alert alert-success">
				{{ session('msg') }}
			</div>
		@endif
		<h2 class="h4">クレジットカード登録</h2>
		<form action="{{ route('registCard') }}" method="post" id="payment-form">
			@csrf
			<input type="hidden" name="code" value="{{ $shop->shop_code }}">
			<input type="hidden" name="cust" value="{{ $plt->plat_id }}">

			<div class="form-group">
				<label for="name">名前</label>
				<input type="text" class="form-control" id="name" name="name" value="{{ $cus->name }}" required>
			</div>

			<div class="form-group">
				<label for="email">メールアドレス</label>
				<input type="email" class="form-control" id="email" name="email" value="{{ $cus->email }}" required>
			</div>

			<!-- Stripeのカード情報入力欄: 分割表示 -->
			<div class="form-row">
				<label for="card-number-element">
					カード番号
				</label>
				<div id="card-number-element" class="form-control">
					<!-- Stripe Card Number Element will be inserted here -->
				</div>
			</div>

			<div class="form-row">
				<label for="card-expiry-element">
					有効期限
				</label>
				<div id="card-expiry-element" class="form-control">
					<!-- Stripe Card Expiry Element will be inserted here -->
				</div>
			</div>

			<div class="form-row">
				<label for="card-cvc-element">
					CVC
				</label>
				<div id="card-cvc-element" class="form-control">
					<!-- Stripe Card CVC Element will be inserted here -->
				</div>
			</div>


			<button class="btn btn-primary mt-4" type="submit">支払う</button>
		</form>
	</div>

	<script>
		var stripe = Stripe('{{ env('STRIPE_KEY_TEST') }}');
		var elements = stripe.elements();

		// Custom styling can be passed to options when creating an Element.
		var style = {
			base: {
				// Add your base input styles here. For example:
				fontSize: '16px',
				color: '#32325d',
			},
		};

		// Create an instance of the card Element for each piece of card information
		var cardNumber = elements.create('cardNumber', {
			style: style
		});
		var cardExpiry = elements.create('cardExpiry', {
			style: style
		});
		var cardCvc = elements.create('cardCvc', {
			style: style
		});

		// Mount the Elements to the DOM
		cardNumber.mount('#card-number-element');
		cardExpiry.mount('#card-expiry-element');
		cardCvc.mount('#card-cvc-element');

		// Handle form submission
		var form = document.getElementById('payment-form');
		form.addEventListener('submit', function (event) {
			event.preventDefault();

			stripe.createToken(cardNumber).then(function (result) {
				if (result.error) {
					// Inform the user if there was an error
					console.log(result.error.message);
				} else {
					// Send the token to your server
					stripeTokenHandler(result.token);
				}
			});
		});

		// Submit the form with the token ID
		function stripeTokenHandler(token) {
			var form = document.getElementById('payment-form');
			var hiddenInput = document.createElement('input');
			hiddenInput.setAttribute('type', 'hidden');
			hiddenInput.setAttribute('name', 'stripeToken');
			hiddenInput.setAttribute('value', token.id);
			form.appendChild(hiddenInput);

			// Submit the form
			form.submit();
		}

	</script>

</body>
</html>
