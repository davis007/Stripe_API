@extends('layouts.app')
@section('title','Settings')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card bg-dark text-white">
				<div class="card-header">Settings</div>
				<table class="table text-white">
					<tr>
						<th class="col-2">email:</th>
						<td>{{ $user->email }}</td>
					</tr>
					<tr>
						<th>shopCode:</th>
						<td>{{ $user->shop_code }}</td>
					</tr>
					<tr>
						<th>Api_Key:</th>
						<td id="apiKey" onclick="copyToClipboard('{{ $user->api_key }}')">{{ $user->api_key }} <span class="badge badge-pill badge-primary">copy</span></td>
					</tr>
					<tr>
						<th>Api_Key再生成:</th>
						<td>
							<form action="{{ url('regenerateApiKey') }}" method="post">
							@csrf
							<button type="submit" class="btn btn-primary fconfirm">Keyを再生成</button>
							</form>
						</td>
					</tr>
				</table>
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
