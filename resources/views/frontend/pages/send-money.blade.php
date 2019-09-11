@extends('frontend.layouts.app')
@section('content')

    <!--Start banner Section-->
    <section class="section-send-welcome">
        <div style="display: flex; justify-content: flex-end;">
            <div class="welcome-text ">
                <h1>@lang('message.send-money.banner.title')</h1>
                <h2>@lang('message.send-money.banner.sub-title')</h2>
            </div>
        </div>
        <img src="{{ url('public/frontend/images/welcome_round_bg.svg') }}" class="round-white-bottom">
    </section>
    <!--End banner Section-->

    <!--Start Section A -->
    <section class="section-send-01">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sec-title">
                        <h2>@lang('message.send-money.section-a.title')</h2>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-top: 50px;">
                <div class="col-md-4">
                    <div class="send-process">
                        <span>1</span>
                        <h2>@lang('message.send-money.section-a.sub-section-1.title') </h2>
                        <p>@lang('message.send-money.section-a.sub-section-1.sub-title')</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="send-process">
                        <span>2</span>
                        <h2> @lang('message.send-money.section-a.sub-section-2.title')</h2>
                        <p>@lang('message.send-money.section-a.sub-section-2.sub-title')</p>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="send-process">
                        <span>3</span>
                        <h2>@lang('message.send-money.section-a.sub-section-3.title')</h2>
                        <p>@lang('message.send-money.section-a.sub-section-3.sub-title')</p>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Section A-->

    <!--Start Section B -->
    <section class="section-send-B">
        <img src="{{ url('public/frontend/images/send_B_top.svg') }}" class="round-white-top">
        <div class="send-B1">
            <div class="round-bg">
                <img src="{{ url('public/frontend/images/section_send_B1.png') }}" 
                    class="img-responsive">
            </div>
            <div style="display: flex; align-items: center; justify-content: center; margin-top: 160px; margin-bottom: 250px;">            
                <div class="sec-title">
                    <h2>@lang('message.send-money.section-b.title')</h2>
                    <p>@lang('message.send-money.section-b.sub-title')</p>
                </div>
            </div>
        </div>
        <div class="send-B2" style="margin-top: -250px;">
            <div style="display: flex; align-items: center; justify-content: center; margin-top: 250px;">            
                <div class="sec-title">
                    <h2>@lang('message.send-money.section-c.title')</h2>
                    <p>@lang('message.send-money.section-c.sub-title')</p>
                </div>
            </div>
            <div class="round-bg">
                <img src="{{ url('public/frontend/images/section_send_B2.png') }}" 
                    class="img-responsive">
            </div>
        </div>
    </secction>

    <!--End Section B-->


@endsection
@section('js')
    <script>

    </script>
@endsection
