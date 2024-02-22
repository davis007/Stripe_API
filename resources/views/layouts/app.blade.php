<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="{{ asset('css/main.css') }}?=newer">

	@yield('addStyle')
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container">
			<a class="navbar-brand" href="{{ url('/') }}">もへもへもー</a>
			<div class="ml-auto">
				<a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
				<a href="{{ route('register') }}" class="btn btn-secondary">register</a>
			</div>
		</div>
	</nav>

	<main class="container" style="margin-top:70px;">
	@if(session('msg'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('msg') }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif

	@yield('content')
	</main>

	<footer class="text-center mt-5">
		<a href={{ url('/') }}" class="text-center">
			Footer
		</a>
		<p class="text-center">@ 2023 運営委員会 All rights reserved.</p>
	</footer>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
	@yield('addSomething')
	<script>

		$(function() {
			setTimeout(function() {
				$('.alert').fadeOut();
			}, 5000);
		});

		$(function() {
			$('.confirm').click(function(){
				if(!confirm('本当に削除しますか？')){
					/* キャンセルの時の処理 */
					return false;
				}
			});
		});
	</script>
</body>
</html>
