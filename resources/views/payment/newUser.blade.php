@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">決済ページ</div>

				<div class="card-body">
					<form id="payment-form" method="post" action="{{ url('payment/newUser') }}">
						@csrf
						<input type="hidden" name="code" value="{{ $shop->shop_code }}">

						<div class="form-group">
							<label for="name">お名前</label>
							<input type="text" id="name" class="form-control" required>
						</div>

						<div class="form-group">
							<label for="email">メールアドレス</label>
							<input type="email" id="email" class="form-control" required>
						</div>

						<div class="form-group">
							<label for="amount">購入価格</label>
							<input type="number" id="amount" value="{{ $amount }}" class="form-control" disabled>
						</div>

						<div class="form-group">
							<label for="card-element">クレジットカード情報を入力</label>
							<div id="card-element"></div>
						</div>

						<div class="form-group">
							<button type="submit" id="submit" class="btn btn-primary">支払う</button>
						</div>

						<div id="payment-message" class="hidden"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('addSomething')
<script src="https://js.stripe.com/v3/"></script>
<script>
	var stripe = Stripe('{{ env('STRIPE_KEY_TEST') }}');
	var elements = stripe.elements();
	var cardElement = elements.create('card');
	cardElement.mount('#card-element');

	var form = document.getElementById('payment-form');
	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		const {
			paymentMethod,
			error
		} = await stripe.createPaymentMethod(
			'card', cardElement, {
				billing_details: {
					name: document.getElementById('name').value,
					email: document.getElementById('email').value
				}
			}
		);

		if (error) {
			document.getElementById('payment-message').classList.remove('hidden');
			document.getElementById('payment-message').textContent = error.message;
		} else {
			fetch('/payment', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						payment_method_id: paymentMethod.id
					})
				})
				.then(response => {
					if (response.ok) {
						document.getElementById('payment-message').classList.remove('hidden');
						document.getElementById('payment-message').textContent = 'Payment successful!';
					} else {
						document.getElementById('payment-message').classList.remove('hidden');
						document.getElementById('payment-message').textContent = 'Payment failed!';
					}
				})
				.catch(error => {
					document.getElementById('payment-message').classList.remove('hidden');
					document.getElementById('payment-message').textContent = error.message;
				});
		}
	});

</script>
@endsection
