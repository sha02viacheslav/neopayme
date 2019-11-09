<!DOCTYPE html>
<html lang="en">

<head>
  <title>@lang('message.express-payment-form.merchant-payment')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--css styles-->
  @include('outPayment.layouts.common.style')

  
  <script type="text/javascript">
    var SITE_URL = "{{url('/')}}";
  </script>
  <style>
    /* --- pay-method --- */
    .plan-card-group {
      display: block;
      margin: auto;
      width: 100%;
      align-items: center;
      justify-content: space-around;
      flex-wrap: wrap;
    }

    .radio-card {
      width: 100%;
      margin-bottom: 30px;
    }

    .radio-card label {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0;
      height: 140px;
      background: #f9f9f9;
      border: 1px solid #e4e4e4;
      color: #003d2e;
      border-radius: 5px;
      transition: all 0.2s ease-in-out;
    }

    .radio-card label:hover {
      cursor: pointer;
      background: #ececec;
    }

    .radio-card label:active {
      background: #ececec;
      color: #ccf5eb;
    }

    .radio-card input[type="radio"]:checked~label {
      background: rgba(127, 103, 170, 0.2);
      color: white;
      border: 1px solid #cac8c8;
    }

    .card-title {
      display: block;
      font-size: 20px;
      width: 100%;
    }

    .planes-radio {
      display: none;
    }

    #plan-finalizar {
      display: block;
      margin: auto;
      padding: 15px 25px;
      border: none;
      border-radius: 5px;
      background: rgba(79, 179, 110, 0.35);
      color: white;
      font-size: 16px;
      transition: all 0.5s;
    }

    #plan-finalizar:hover {
      cursor: pointer;
      background: #00c291;
      color: white;
    }

    #plan-finalizar:focus,
    #plan-finalizar:active {
      outline: none;
      background: rgba(79, 179, 110, 0.35);
    }

    #plan-finalizar:disabled {
      background: #ddd;
      cursor: default;
    }

    fieldset {
      border: none;
    }

    legend {
      padding: 10px;
      font-size: 24px;
      font-weight: 300;
    }

    .padding-10 {
      padding: 10px;
    }

    .padding-20 {
      padding: 20px;
    }

    .padding-35 {
      padding: 35px;
    }

    .radio-card .fee {
      background: #7f67aa none repeat scroll 0 0;
      color: #fff;
      font-size: 12px;
      font-weight: bold;
      letter-spacing: 1px;
      padding: 0px 10px;
      position: absolute;
      right: 15px;
      top: 0;
      z-index: 4;
      line-height: 25px;
    }

    /*logo -- css*/
    .setting-img {
      overflow: hidden;
      max-width: 100%;
    }

    .img-wrap-general-logo {
      /*width: 300px;*/
      overflow: hidden;
      margin: 5px;
      background: rgba(74, 111, 197, 0.9) !important;
      /*height: 100px;*/
      max-width: 100%;
    }

    .img-wrap-general-logo>img {
      max-width: 100%;
      height: auto !important;
      max-height: 100%;
      width: auto !important;
      object-fit: contain;
    }

    .left {
      float: left;
    }

    .right {
      float: right;
    }

    /*logo -- css*/
  </style>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-4 col-sm-4"></div>
      <div class="col-md-4 col-sm-4"></div>
      <div class="col-md-2 col-sm-4">
        <h2>@lang('message.footer.language')</h2>
        <div class="form-group">
          <select class="form-control" id="lang">
            @foreach (getLanguagesListAtFooterFrontEnd() as $lang)
            <option {{ Session::get('dflt_lang') == $lang->short_name ? 'selected' : '' }}
              value='{{ $lang->short_name }}'> {{ $lang->name }}
            </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default box-shadow" style="margin-top: 15px;">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12 text-center">
                <h4>You have recieved a payment request</h4>
              </div>
              <div class="col-md-12">
                @yield('content')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('outPayment.layouts.common.script')
  @yield('js')
  
</body>

</html>