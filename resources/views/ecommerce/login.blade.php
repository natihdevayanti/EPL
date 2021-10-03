@extends('layouts.ecommerce')

@section('title')
    <title>Login - Ivone Seafood Store</title>
@endsection

@section('content')
	<!--================Login Box Area =================-->
	<section class="login_box_area p_120">
		<div class="container">
			<div class="row">
				<div class="offset-md-3 col-lg-6">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

					<div class="login_form_inner">
						<h3>Log in</h3>
						<form class="row login_form" action="{{ route('customer.post_login') }}" method="post" id="contactForm">
							@csrf
							<div class="col-md-12 form-group">
								<input type="email" class="form-control" id="email" name="email" placeholder="Alamat Email">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
							</div>
							<div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" id="f-option2" name="remember">
									<label for="f-option2">Keep me logged in</label>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="btn submit_btn">Log In</button>
								<a href="#">Lupa Password?</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection