@extends('layouts.app')
@section('title','Basics')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">Basics</div>

				<div class="card-body">
					@if(session('msg'))
						<div class="alert alert-success" role="alert">
							{{ session('msg') }}
						</div>
					@endif
					<ul>
						<li>基本情報:</li>
						<li>登録日: {{ $user->created_at->format('Y-m-d') }}</li>
						<li>email: {{ $user->email }}</li>
						<li>shop_code: {{ $user->shop_code }}</li>
						<li id="apiKey" onclick="copyToClipboard('{{ $user->api_key }}')">Api_Key: {{ $user->api_key }} <span class="badge badge-pill badge-primary">copy</span></li>
						<li>累計売上: {{ number_format($totalAmount) }}円</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('addSomething')
<style>
.badge {
	cursor: pointer;
}
</style>
<script>
	function copyToClipboard(text) {
		// テキストエリアを動的に作成
		var textarea = document.createElement("textarea");
		textarea.textContent = text;
		// スタイルを設定してページ上に表示されないようにする
		textarea.style.position = "fixed"; // ページのスクロールを避ける
		document.body.appendChild(textarea);
		textarea.select(); // テキストを選択
		try {
			// コピーを試みる
			document.execCommand("copy"); // コピー実行
			alert("APIキーをクリップボードにコピーしました: " + text);
		} catch (e) {
			console.warn("コピーに失敗しました。", e);
		}
		// 作成したテキストエリアを削除
		document.body.removeChild(textarea);
	}

	$(function () {
		$('.fconfirm').click(function () {
			if (!confirm('Keyを再生成すると現在のAPI_Keyは失われます。本当に再生成しますか？')) {
				/* キャンセルの時の処理 */
				return false;
			}
		});
	});

</script>

@endsection
