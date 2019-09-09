    <section class="welcome-area image-bg">
        <!-- <div class="overlay-banner"> </div>
        <div class="overlay-text"> </div> -->
        <div class="container">
            <div class="row">
                <div class="col-md-8">

                    @include('frontend.layouts.common.alert')

                    <div class="welcome-text">
                        <h1>@lang('message.home.banner.title')</h1> 
                        <h2>@lang('message.home.banner.description',['br'=>'</br>'])</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>