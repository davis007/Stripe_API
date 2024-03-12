@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">ポイント購入: {{ number_format('25000') }} (1ポイント=1円)</div>

				<div class="card-body">
					<form id="payment-form">
						<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('購入ポイント') }}</label>

							<div class="col-md-6">
								<input id="email" type="text" class="form-control" value="10,000 pt (1ポイント=1円)" readonly>
							</div>
						</div>

						<div class="form-group row">
							<label for="card-element" class="col-md-4 col-form-label text-md-right">{{ __('クレジットカード') }}</label>

							<div class="col-md-6">
								<div id="card-element"></div>
								<div id="card-errors" role="alert"></div>
							</div>
						</div>

						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button type="submit" class="btn btn-primary" id="card-button" data-secret="hogehogehogehoge">
									{{ __('購入する') }}
								</button>
							</div>
							<div class="col-md-6">
								<p>購入金額: 10,000円</p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
	var stripe = Stripe('{{ env('STRIPE_KEY_TEST') }}');
	var elements = stripe.elements();
	var cardElement = elements.create('card');
	cardElement.mount('#card-element');

	var form = document.getElementById('payment-form');
	var cardButton = document.getElementById('card-button');
	var clientSecret = cardButton.dataset.secret;

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		cardButton.disabled = true;

		const { setupIntent, error } = await stripe.confirmCardPayment(
			clientSecret, {
				payment_method: {
					card: cardElement,
					billing_details: {
						email: document.getElementById('email').value
					}
				}
			}
		);

		if (error) {
			cardButton.disabled = false;
			document.getElementById('card-errors').textContent = error.message;
		} else {
			// Handle successful payment
			window.location.replace('{{ url('/success') }}');
		}
	});
</script>
@endpush
