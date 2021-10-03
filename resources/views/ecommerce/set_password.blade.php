@extends('layouts.ecommerce')

@section('title')
    <title>Buat Password - Ivone Seafood Store</title>
@endsection

@section('content')
	<!--================Login Box Area =================-->
	<section class="login_box_area p_120">
		<div class="container">
			<div class="row">
				<div class="offset-md-3 col-lg-6">
                    @if ($msg == 'success')
                    <div class="alert alert-success">Buat Password Baru</div>

					<div class="login_form_inner">
						<h3>Buat Password</h3>
						<form class="row login_form" action="{{ route('customer.set_password') }}" method="post" id="setPasswordForm">
							@csrf
							<div class="col-md-12 form-group">
								<input type="email" class="form-control" id="email_set" name="email" placeholder="Alamat Email" value="{{ $customer['email'] }}" readonly>
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" id="password_set" name="password" placeholder="Password" required>
							</div>
							<div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" id="f-option3" name="remember">
									<label for="f-option2">Keep me logged in</label>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="btn submit_btn">Log In</button>
							</div>
						</form>
					</div>
					@elseif ($msg == 'error')
                    <div class="alert alert-danger">Terjadi Kesalahan Verifikasi</div>
                    @endif
				</div>
			</div>
		</div>
	</section>
@endsection