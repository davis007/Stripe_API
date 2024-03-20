@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="card bg-dark text-white">
				<div class="card-header">
					<h5 class="card-title">売上情報</h5>
				</div>
				<div class="card-body">
					<p class="card-text">総売上金額: {{ number_format($total_sales) }}円</p>
					<p class="card-text">総ユーザー数: {{ number_format($total_users) }}人</p>
				</div>
			</div>
		</div>
		<div class="col-md-6 mt-4 mt-md-0">
			<div class="card bg-dark text-white">
				<div class="card-header">
					<h5 class="card-title">お知らせ</h5>
				</div>
				<div class="card-body">
					<div class="alert alert-info">
						<strong>新機能リリース</strong>
						<p>新しい機能「カスタマイズ機能」をリリースしました。</p>
					</div>
					<div class="alert alert-info alert-persistent">
						<strong>新機能リリース</strong>
						<p>新しい機能「カスタマイズ機能」をリリースしました。</p>
					</div>
					<div class="alert alert-info alert-persistent">
						<strong>サーバーメンテナンス</strong>
						<p>4月1日(土) 0:00 ~ 6:00に、サーバーメンテナンスを実施します。</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
