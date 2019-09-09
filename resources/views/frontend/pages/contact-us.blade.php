@extends('frontend.layouts.app')
@section('content')

    <!--Start banner Section-->
    <section class="contact-welcome-area">
    </section>
    <!--End banner Section-->

    <!--Start Section A -->
    <section style="padding-bottom: 50px;">
        <div class="container">
            <div style="display: flex; justify-content: center;">
                <div>
                    <div class="contact-list-box">
                        <div class="contact-list-wrap">
                            <div class="contact-list-img">
                                <img src="{{ url('public/frontend/images/contact_phone1.png') }}" 
                                    class="img-responsive">
                            </div>
                            <p>(+1) 555 55 55</p>
                        </div>
                        <div class="contact-list-wrap">
                            <div class="contact-list-img">
                                <img src="{{ url('public/frontend/images/contact_phone2.png') }}" 
                                    class="img-responsive">
                            </div>  
                            <p>(+1) 555 55 55</p>
                        </div>
                        <div class="contact-list-wrap">
                            <div class="contact-list-img">
                                <img src="{{ url('public/frontend/images/contact_email.png') }}" 
                                    class="img-responsive">
                            </div>  
                            <p>user@example.com</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="contact-list-box" style="text-align: center;">
                        <div class="contact-location-img">
                            <img src="{{ url('public/frontend/images/contact_location.png') }}" 
                                class="img-responsive">
                        </div>
                        <p class="contact-location-description">Enter your recipient email address that won't be share with others and remain secured, then add an amount with currency to send securely.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Section A-->



@endsection
@section('js')
    <script>

    </script>
@endsection
