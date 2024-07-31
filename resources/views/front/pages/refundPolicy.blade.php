@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item">Refund Policy</li>
            </ol>
        </div>
    </div>
</section>

<!-- Refund Policy Section -->
<section class="section-11">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Refund Policy</h3>
                    </div>
                    <div class="card-body">
                        <p>Welcome to shauryaConstruction's Refund Policy!</p>

                        <p>Our Refund Policy outlines the circumstances under which shauryaConstruction will issue refunds for purchases made on our website.</p>

                        <h4>1. Eligibility for Refund</h4>
                        <p>To be eligible for a refund, you must meet certain criteria specified in the terms of purchase for each product or service offered on our platform.</p>

                        <h4>2. How to Request a Refund</h4>
                        <p>To request a refund, please contact our customer support team via email at <a href="mailto:customer@shauryaConstruction.com">customer@shauryaConstruction.com</a>. You may be required to provide details regarding your purchase and the reason for your refund request.</p>

                        <h4>3. Refund Processing</h4>
                        <p>Once your refund request is received and approved, we will process your refund within a certain number of days, depending on the payment method used and any applicable policies.</p>

                        <h4>4. Contact Us</h4>
                        <p>If you have any questions about our Refund Policy, please contact us:</p>
                        <ul>
                            <li>Email: <a href="mailto:customer@shauryaConstruction.com">customer@shauryaConstruction.com</a></li>
                            <li>Phone: +91 7874728723</li>
                        </ul>

                        <p>We reserve the right to amend this Refund Policy at any time. Any changes will be effective immediately upon posting on this page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
