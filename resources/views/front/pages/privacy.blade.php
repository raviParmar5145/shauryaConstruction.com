@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item">Privacy Policy</li>
            </ol>
        </div>
    </div>
</section>

<!-- Privacy Policy Section -->
<section class="section-11">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Privacy Policy</h3>
                    </div>
                    <div class="card-body">
                        <p>Your privacy is important to us. It is shauryaConstruction's policy to respect your privacy regarding any information we may collect from you across our website, <a href="{{ route('front.home') }}">http://www.shauryaConstruction.com</a>, and other sites we own and operate.</p>
                        
                        <h4>Information We Collect</h4>
                        <p>We only collect information about you if we have a reason to do so – for example, to provide our services, to communicate with you, or to make our services better.</p>
                        
                        <h4>How We Use Your Information</h4>
                        <p>We use the information we collect in various ways, including to:</p>
                        <ul>
                            <li>Provide, operate, and maintain our website</li>
                            <li>Improve, personalize, and expand our website</li>
                            <li>Understand and analyze how you use our website</li>
                            <li>Develop new products, services, features, and functionality</li>
                        </ul>
                        
                        <h4>Sharing Your Information</h4>
                        <p>We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information unless we provide users with advance notice.</p>
                        
                        <h4>Security of Your Information</h4>
                        <p>We use administrative, technical, and physical security measures to help protect your personal information.</p>
                        
                        <h4>Changes to This Privacy Policy</h4>
                        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>
                        
                        <p>This Privacy Policy was last updated on <span class="font-weight-bold">July 3, 2024</span>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
