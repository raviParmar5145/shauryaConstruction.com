@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item">About Us</li>
            </ol>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section-11">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">About shauryaConstruction</h3>
                    </div>
                    <div class="card-body">
                        <p>Welcome to shauryaConstruction, your number one source for swapping books online. We're dedicated to giving you the very best of book swapping experience, with a focus on dependability, customer service, and uniqueness.</p>
                        <p>Founded in 2024 by book enthusiasts, shauryaConstruction has come a long way from its beginnings. When we first started out, our passion for reading and sharing drove us to create an online platform where book lovers can connect and exchange their favorite books.</p>
                        <p>We hope you enjoy our platform as much as we enjoy offering it to you. If you have any questions or comments, please don't hesitate to contact us.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
