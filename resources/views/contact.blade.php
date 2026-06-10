@extends('layouts.app')

@section('content')

<style>
    body { background-color: #fdfaf5; }
    
    .contact-header {
        background: linear-gradient(rgba(94, 25, 41, 0.9), rgba(94, 25, 41, 0.9)), url('{{ asset("images/banner1.png") }}');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        text-align: center;
        color: #fff;
        margin-bottom: 50px;
    }
    
    .contact-header h1 { font-weight: 700; letter-spacing: 2px; margin-bottom: 10px; }
    .contact-header p { color: #c59d5f; font-size: 16px; font-style: italic; }

    .contact-wrapper {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(94, 25, 41, 0.05);
        border: 1px solid #f5ebe9;
        overflow: hidden;
        margin-bottom: 60px;
    }

    .contact-info-box {
        background: #5E1929;
        color: #fdfaf5;
        padding: 50px 40px;
        height: 100%;
    }
    
    .contact-info-box h3 { color: #c59d5f; font-weight: 600; margin-bottom: 30px; letter-spacing: 1px; }
    .info-item { display: flex; align-items: flex-start; margin-bottom: 25px; }
    .info-icon { font-size: 24px; margin-right: 15px; line-height: 1; }
    .info-text h6 { font-size: 16px; margin-bottom: 5px; font-weight: 600; color: #fff; }
    .info-text p { font-size: 14px; color: #d8c3c8; margin: 0; line-height: 1.6; }

    .contact-form-box { padding: 50px 40px; }
    .contact-form-box h3 { color: #5E1929; font-weight: 700; margin-bottom: 10px; }
    .contact-form-box p { color: #888; font-size: 14px; margin-bottom: 30px; }

    .form-label { font-weight: 600; color: #333; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control { border: 1px solid #e8d5d1; border-radius: 8px; padding: 12px 15px; font-size: 14px; transition: 0.3s; background: #fafbfc; }
    .form-control:focus { border-color: #c59d5f; box-shadow: 0 0 0 0.2rem rgba(197, 157, 95, 0.15); background: #fff; }
    
    .btn-send {
        background: linear-gradient(135deg, #c59d5f, #e5c07b);
        color: #fff;
        border: none;
        padding: 14px 30px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 10px;
    }
    .btn-send:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(197, 157, 95, 0.4); color: #fff; }

    /* 🔥 NEW: Mobile Responsive Fixes 🔥 */
    @media(max-width: 768px) {
        .contact-header {
            padding: 50px 15px;
            margin-bottom: 30px;
        }
        .contact-header h1 {
            font-size: 26px;
        }
        .contact-wrapper {
            margin-bottom: 30px;
            border-radius: 12px;
        }
        /* Mobile me form ko upar aur details ko neeche karne ke liye order change kiya gaya hai */
        .order-mobile-first {
            order: 1;
        }
        .order-mobile-second {
            order: 2;
        }
        .contact-form-box { 
            padding: 30px 20px; 
        }
        .contact-info-box { 
            padding: 30px 20px; 
        }
        .contact-info-box h3, .contact-form-box h3 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 20px;
        }
    }
</style>

<div class="contact-header">
    <div class="container">
        <h1>Get In Touch</h1>
        <p>We would love to hear from you</p>
    </div>
</div>

<div class="container">
    <div class="contact-wrapper">
        <div class="row g-0">
            
            <div class="col-lg-7 order-mobile-first">
                <div class="contact-form-box">
                    <h3>Send us a Message</h3>
                    <p>Have a question about an order, our products, or anything else? Fill out the form below and we will get back to you.</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; background: #e6f4ea; border-color: #c3e6cb; color: #155724;">
                            ✅ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="E.g. Issue with my order #1234" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="5" placeholder="How can we help you today?" required></textarea>
                        </div>

                        <button type="submit" class="btn-send">Send Message</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5 order-mobile-second">
                <div class="contact-info-box">
                    <h3>Contact Information</h3>
                    
                    <div class="info-item">
                        <div class="info-icon">📍</div>
                        <div class="info-text">
                            <h6>Our Boutique</h6>
                            <p>Agra, Uttar Pradesh<br>India - 282001</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">📞</div>
                        <div class="info-text">
                            <h6>Phone Number</h6>
                            <p>+91-XXXXXXXXXX<br>Mon to Sat, 10am to 7pm</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">✉️</div>
                        <div class="info-text">
                            <h6>Email Address</h6>
                            <p>support@shringar.net<br>We reply within 24 hours</p>
                        </div>
                    </div>

                    <div style="margin-top: 40px;">
                        <h6 style="color: #c59d5f; margin-bottom: 15px; font-weight: 600;">Follow Us</h6>
                        <div class="d-flex gap-3">
                            <a href="#" style="color: #fff; font-size: 20px; text-decoration: none;">📘</a>
                            <a href="https://www.instagram.com/styledbyshringar" target="_blank" style="color: #fff; font-size: 20px; text-decoration: none;">📸</a>
                            <a href="#" style="color: #fff; font-size: 20px; text-decoration: none;">🐦</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection