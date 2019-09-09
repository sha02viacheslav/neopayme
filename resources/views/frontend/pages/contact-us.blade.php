@extends('frontend.layouts.app')
@section('content')

    <!--Start banner Section-->
    <section class="contact-welcome-area">
    </section>
    <!--End banner Section-->

    <!--Start Section A -->
    <section style="padding-bottom: 20px;">
        <div class="container">
            <div style="display: flex; justify-content: center;">
                <div>
                    <div class="contact-list-box">
                        <h2><span>1</span> @lang('message.send-money.section-a.sub-section-1.title')</h2>
                        <p>@lang('message.send-money.section-a.sub-section-1.sub-title')</p>
                    </div>
                </div>
                <div>
                    <div class="contact-list-box" style="text-align: center;">
                        <h2><span>2</span></h2>
                        <p>@lang('message.send-money.section-a.sub-section-2.sub-title')</p>
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
