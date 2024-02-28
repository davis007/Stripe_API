@extends('layouts.app')
@section('title','Create Customer')

@section('content')
<div class="text-white">
	<h2>顧客情報登録</h2>
	<form method="POST" action="{{ url('test/createCustomer') }}">
		@csrf
		<!-- 名前入力フィールド -->
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
</div>
@endsection
