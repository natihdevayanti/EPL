@extends('layouts.ecommerce')

@section('title')
    <title>Sign Up - Ecommerce</title>
@endsection

@section('content')
	<!--================Login Box Area =================-->
	<section class="login_box_area p_120">
		<div class="container">
			<div class="row">
				<div class="offset-md-3 col-lg-6">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

					<div class="login_form_inner">
						<h3>Sign Up</h3>
						<form class="row login_form" action="{{ route('customer.post_signup') }}" method="post" id="contactForm" oninput='passwordC.setCustomValidity(passwordC.value != password.value ? "Password tidak sama." : "")'>
							@csrf
							<div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Nama Lengkap" required>
                                <p class="text-danger">{{ $errors->first('customer_name') }}</p>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Alamat Email" required>
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <p class="text-danger">{{ $errors->first('password') }}</p>
							</div>
							<div class="col-md-12 form-group">
                                <input type="password" class="form-control" id="passwordC" name="passwordC" placeholder="Ketik Ulang Password" required>
                                <p class="text-danger">{{ $errors->first('password') }}</p>
							</div>
                            <div class="col-md-12 form-group">
								<button type="submit" value="submit" class="btn submit_btn">Sign Up</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection