<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>決済確認</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h4>決済確認</h4>
					</div>
					<div class="card-body">
						<form action="{{ url('payorder/payment') }}" method="post">
							@csrf
							<input type="hidden" name="code" value="{{ $customer->shopCode }}">
							<input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
							<div class="form-group">
								<label for="amount">決済金額</label>
								<div class="input-group">
									<input type="text" class="form-control" id="amount" name="amount" value="{{ $amount }}" required>
									<div class="input-group-append">
										<span class="input-group-text" id="basic-addon2">円</span>
									</div>
								</div>

							</div>
							<div class="form-group">
								<label for="card-number">カード選択</label>
								@if($platC)
									<select name="card_id" class="form-control">
										@foreach($platC as $pl)
											<option value="{{ $pl->card_id }}">{{ $pl->brand.' **** **** ****'.$pl->last4 }}</option>
										@endforeach
									</select>
								@endif
							</div>
							<button type="submit" class="btn btn-primary">決済する</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
