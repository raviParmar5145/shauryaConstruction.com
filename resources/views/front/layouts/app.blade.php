<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Book Swap</title>

	<!-- Favicon -->
	<link rel="icon" href="{{ asset('admin-assets/img/AdminDeshLogo.jpg') }}" type="image/x-icon">
	
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />

	<meta property="og:locale" content="en_AU" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="" />
	<meta property="fb:app_id" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="" />
	<meta property="og:image:height" content="" />
	<meta property="og:image:alt" content="" />

	<meta name="twitter:title" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:image:alt" content="" />
	<meta name="twitter:card" content="summary_large_image" />
	

	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="#" />

	<!-- summernote box style -->
	<link rel="stylesheet" href="{{ asset('admin-assets/plugins/summernote/summernote.min.css') }}">
	<!-- Dropzone CSS -->
	<link rel="stylesheet" href="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.css') }}">
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2/css/select2.min.css') }}">

	<!-- Date timepicker CSS -->
	<link rel="stylesheet" href="{{ asset('admin-assets/css/datetimepicker.css') }}">

	<link rel="stylesheet" href="{{ asset('front-assets/css/custom.css') }}">

	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body data-instant-intensity="mousedown">

<div class="bg-light top-header">        
	<div class="container">
		<div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
			<div class="col-lg-4 logo">
				<a href="{{ route('front.shop') }}" class="text-decoration-none">
					<span class="h4 text-uppercase text-primary bg-secondary px-2">Shaurya</span>
					<span class="h4 text-uppercase text-primary bg-primary log px-2 ml-n1">Construction
						{{-- <img class="headLogo" src="{{ asset('front-assets/images/web-logo.png') }}" alt="" /> --}}
					</a>
			</div>
			<div class="col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
				@if (Auth::check())

				{{-- {{ Auth::user()->role }}
				{{ auth()->user()->role }} --}}
				@if (auth()->check() && auth()->user()->role == 0)
                    <a href="{{ route('seller.index') }}" class="nav-link text-warning">Product List</a>
                    {{-- <a href="#" class="nav-link text-success">Books List</a> --}}
                @endif

					<a href="{{ route('account.profile') }}" class="nav-link text-dark">My Account</a>
				@else
					<a href="{{ route('account.login') }}" class="nav-link text-dark">Login/Register</a>
					
				@endif
				<form action="{{ route('front.shop')}}" method="get">					
					<div class="input-group">
						<input value="{{ Request::get('search') }}" type="text" placeholder="Search For Books" class="form-control" name="search">
						<button type="submit" class="input-group-text">
							<i class="fa fa-search"></i>
					  	</button>
					</div>
				</form>
			</div>		
		</div>
	</div>
</div>

<header class="bg-dark">
	<div class="container">
		<nav class="navbar navbar-expand-xl" id="navbar">
			<a href="{{ route('front.home') }}" class="text-decoration-none mobile-logo">
				{{-- <span class="h2 text-uppercase text-primary bg-dark">Book</span>
				<span class="h2 text-uppercase text-white px-2">Swap<span> --}}
					<img class="headLogo" src="{{ asset('front-assets/images/web-logo.png') }}" alt="" />
			</a>
			<button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      			<!-- <span class="navbar-toggler-icon icon-menu"></span> -->
				  <i class="navbar-toggler-icon fas fa-bars"></i>
    		</button>
    		<div class="collapse navbar-collapse" id="navbarSupportedContent">
      			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
        			@if (getCategories()->isNotEmpty())
                        @foreach (getCategories() as $category)
                            <li class="nav-item dropdown">
                                <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $category->name }}
                                </button>
                                @if ($category->sub_category->isNotEmpty())
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        @foreach ($category->sub_category as $subCategory)
                                            <li><a class="dropdown-item nav-link" href="{{ route('front.shop',[$category->slug,$subCategory->slug]) }}">{{ $subCategory->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                               
                            </li>
                        @endforeach
                    @endif					
      			</ul>      			
      		</div>   
			<div class="right-nav py-0">
				<a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
					<i class="fas fa-shopping-cart text-primary"></i>					
				</a>
			</div> 		
      	</nav>
  	</div>
</header>




<main>
	@yield('content')
</main>



<footer class="bg-dark mt-5">
    <div class="container pb-5 pt-3">
        <div class="row">

			<div class="col-md-3">
				<div class="footer-card">
					<h3>Shaurya</h3>
					<p> "shauryaConstruction" is a platform designed for book enthusiasts to exchange and share their favorite reads. It facilitates book swapping and provides a community for discussing and discovering new books.
					</p>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Get In Touch</h3>
					<p>
						<a href="tel:+917874728723"><img class="footerIcon" src="{{ asset('front-assets/footerImage/mobile.png') }}" alt="" /> +91 7874728723 </a>
						<a href="tel:+917283931539"><img class="footerIcon" src="{{ asset('front-assets/footerImage/mobile.png') }}" alt="" /> +91 7283931539 </a>
						<a href="mailto:customer@shauryaConstruction.com"><img class="footerIcon" src="{{ asset('front-assets/footerImage/Mail2.png') }}" alt="" /> customer@shauryaConstruction.com </a>
						<img class="footerIcon" src="{{ asset('front-assets/footerImage/Address.png') }}" alt="" /> 6/7 I, Shivshakti Tenament, <br>Shanti-nagar, Chitra.
						Landmark: Behind Press Quatar; Bhavnager, Gujarat.
					</p>
				</div>
			</div>
			
            
            <div class="col-md-3">
                <div class="footer-card">
                    <h3>Useful Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}" title="About"><img class="footerIcon" src="{{ asset('front-assets/footerImage/Aboutus.png') }}" alt="" />About</a></li>
                        <li><a href="{{ route('contact') }}" title="Contact Us"><img class="footerIcon" src="{{ asset('front-assets/footerImage/contactUs.png') }}" alt="" />Contact Us</a></li>                        
                        <li><a href="{{ route('privacy') }}" title="Privacy"><img class="footerIcon" src="{{ asset('front-assets/footerImage/Privacy.png') }}" alt="" />Privacy</a></li>
                        <li><a href="{{ route('termsConditions') }}" title="Terms & Conditions"><img class="footerIcon" src="{{ asset('front-assets/footerImage/Tnc.png') }}" alt="" />Terms & Conditions</a></li>
                        <li><a href="{{ route('refundPolicy') }}" title="Refund Policy"><img class="footerIcon" src="{{ asset('front-assets/footerImage/refundPolicy.png') }}" alt="" />Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="footer-card">
                    <h3>My Account</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('account.login') }}" title="Login"><img class="footerIcon" src="{{ asset('front-assets/footerImage/Login.png') }}" alt="" />Login</a></li>
                        <li><a href="{{ route('account.register') }}" title="Register"><img class="footerIcon" src="{{ asset('front-assets/footerImage/Register.png') }}" alt="" />Register</a></li>
                        <li><a href="{{ route('account.orders') }}" title="My Orders"><img class="footerIcon" src="{{ asset('front-assets/footerImage/myOrder.png') }}" alt="" />My Orders</a></li>                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="copy-right text-center">
                        <p>Copyright © 2024 shauryaConstruction</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>




<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>

<!-- Summernote JS -->
<script src="{{ asset('admin-assets/plugins/summernote/summernote.min.js') }}"></script>
<!-- Dropzone JS -->
<script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
<!-- Select2 JS -->
<script src="{{ asset('admin-assets/plugins/select2/js/select2.min.js') }}"></script>

<!-- Date time picker JS -->
<script src="{{ asset('admin-assets/js/datetimepicker.js') }}"></script>

<script src="{{ asset('front-assets/js/custom.js') }}"></script>
<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$(document).ready(function() {
	$(".summernote").summernote({
		height:250
	});
});

function addToCart(id) {
	$.ajax({
		url: '{{ route("front.addToCart") }}',
		type:'post',
		data: {id:id},
		dataType: 'json',
		success: function(response) {
			if (response.status == true) {
				window.location.href="{{ route('front.cart') }}";
			} else {
				alert(response.message);
			}
		}
	});
}
</script>

@yield('customJs')
</body>
</html>