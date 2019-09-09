<?php
$socialList = getSocialLink();
$menusFooter = getMenuContent('Footer');
?>

<section class="contact" id="contact">
    <div class="contact-content">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    @if (request()->path() != 'merchant/payment')
                        <div class="quick-link">
                            <h2 style="margin-left: 16px">@lang('message.footer.related-link')</h2>
                            <ul>
                                <li class="nav-item"><a href="{{url('/')}}"
                                                        class="nav-link">@lang('message.home.title-bar.home')</a></li>
                                <li class="nav-item"><a href="{{url('/send-money')}}"
                                                        class="nav-link">@lang('message.home.title-bar.send')</a></li>
                                <li class="nav-item"><a href="{{url('/request-money')}}"
                                                        class="nav-link">@lang('message.home.title-bar.request')</a></li>
                                <li class="nav-item"><a href="{{url('/request-money')}}"
                                                        class="nav-link">@lang('message.home.title-bar.about')</a></li>
                                <li class="nav-item"><a href="{{url('/request-money')}}"
                                                        class="nav-link">@lang('message.home.title-bar.portfolio')</a></li>
                                <li class="nav-item"><a href="{{url('/request-money')}}"
                                                        class="nav-link">@lang('message.home.title-bar.contact')</a></li>
                                @if(!empty($menusFooter))
                                    @foreach($menusFooter as $footer_navbar)
                                        <li class="nav-item"><a href="{{url($footer_navbar->url)}}"
                                                                class="nav-link"> {{ $footer_navbar->name }}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-md-7 col-sm-7">
                    <div class="contact-detail">
                        <h2>@lang('message.footer.follow-us')</h2>
                        <div class="social-icons">
                            @if(!empty($socialList))
                                @foreach($socialList as $social)
                                    <a href="{{ $social->url }}">{!! $social->icon !!}</a>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-4 col-sm-4">
                    <form class="contact-form-area">
                        <h2>@lang('message.footer.language')</h2>
                        <div class="form-group">
                            <select class="form-control" id="lang">
                                @foreach (getLanguagesListAtFooterFrontEnd() as $lang)
                                    <option {{ Session::get('dflt_lang') == $lang->short_name ? 'selected' : '' }} value='{{ $lang->short_name }}'> {{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
</section>
