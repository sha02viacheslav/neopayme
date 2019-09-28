@extends('frontend.layouts.app')
@section('content')
    <!--Start banner Section-->
    <section class="section-request-welcome">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="welcome-text ">
                        <h1>@lang('message.request-money.banner.title',['br'=>'<br>'])</h1>
                        <h2>@lang('message.request-money.banner.sub-title')</h2>

                        @if(Auth::check() == false)
                            <a href="{{url('register')}}" class="iphone-btn">
                                @lang('message.request-money.banner.sign-up')
                            </a>
                            <p>@lang('message.request-money.banner.already-signed') <a href="{{url('login')}}">@lang('message.request-money.banner.login')</a> @lang('message.request-money.banner.request-money')</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <img src="{{ url('public/frontend/images/welcome_round_bg.svg') }}" class="round-white-bottom">
    </section>
    <!--End banner Section-->

    <!--Start Section A-->
    <section class="section-request-01">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sec-title">
                        <h2>@lang('message.request-money.section-a.title')</h2>
                        <p>@lang('message.request-money.section-a.sub-title',['br'=>'<br>'])</p>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-top: 50px;">
                <div class="col-md-4">
                    <div class="request-process">
                        <span>1</span>
                        <h2>@lang('message.request-money.section-a.sub-section-1.title') </h2>
                        <p>@lang('message.request-money.section-a.sub-section-1.sub-title') </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="request-process">
                        <span>2</span>
                        <h2> @lang('message.request-money.section-a.sub-section-2.title')</h2>
                        <p> @lang('message.request-money.section-a.sub-section-2.sub-title')</p>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="request-process">
                        <span>3</span>
                        <h2>@lang('message.request-money.section-a.sub-section-3.title')</h2>
                        <p>@lang('message.request-money.section-a.sub-section-3.sub-title')</p>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Section A-->

    <!--Start Section B-->
    <section class="section-request_02">
        <!-- <div class="container"> -->
        <div style="display: flex;">
            <div class="fill-remaining-space">
            </div>
            <div class="box-with-shape">
                <div class="shape-backgound">
                </div>
                <div class="sec-title-laptop">
                    <h2>@lang('message.request-money.section-b.title')</h2>
                    <p>@lang('message.request-money.section-b.sub-title')</p>
                </div>
            </div>
        </div>
        <img src="{{ url('public/frontend/images/welcome_round_bg.svg') }}" class="round-white-bottom">
        <!-- </div> -->
    </section>
    <!--End Section B-->

    <!--Start Section C -->
    <section class="section-request_03">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sec-title">
                        <h2>@lang('message.request-money.section-c.title')</h2>
                        <p>@lang('message.request-money.section-c.sub-title')</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Section C-->

    <!--Start Section D-->
    <section class="section-request_04">
        <img src="{{ url('public/frontend/images/request_04_round_bg.svg') }}" class="round-white-top">
    </section>
    <!--End Section D-->

@endsection
@section('js')
    <script>

    </script>
@endsection
