@extends('layouts.app')
@section('title','Sales')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">Sales</div>

				<div class="card-body">
					@if(session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif
					<ul>
						<li>基本情報:</li>
						<li>email: {{ $user->email }}</li>
						<li>shop_code: {{ $user->shop_code }}</li>
						<li id="apiKey" onclick="copyToClipboard('{{ $user->api_key }}')">Api_Key: {{ $user->api_key }} <span class="badge badge-pill badge-primary">copy</span></li>
						<li>売上: 348,500円</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
