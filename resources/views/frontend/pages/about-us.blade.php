@extends('frontend.layouts.app')
@section('content')

    <!--Start banner Section-->
    <section class="about-welcome-area">
        <div class="overlay-dark"></div>
        <div class="container">
            <div class="row" style="justify-content: center;">
                <div class="col-md-10">
                    <div class="about-text">
                        <h1>@lang('message.about-us.banner.title')</h1>
                        <h3>@lang('message.about-us.banner.sub-title')</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End banner Section-->



@endsection
@section('js')
    <script>

    </script>
@endsection
