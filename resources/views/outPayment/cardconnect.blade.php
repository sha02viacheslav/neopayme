@extends('outPayment.layouts.app')

@section('css')
  <!-- cardconnect-form-style -->
  <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/cardServices.css') }}">
@endsection

@section('content')
    <form action="{{URL::to('outpayment/cardconnect_payment_store')}}" method="post" id="payment-form">

        {{ csrf_field() }}

        <div class="cd-row">
            <div class="cd-title">
                <div class="cd-title-label">
                    @lang('message.dashboard.deposit.deposit-cardconnect-form.card-no')
                </div>
            </div>
            <div class="cd-field">
                <iframe id="tokenframe" name="tokenframe" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
        <div id="cc_error" class="cd-err-row hidden">
            Please enter a valid credit card number
        </div>
        <div class="cd-row">
            <div class="cd-title">
                <div class="cd-title-label">
                    Expiration <small>(mmyy)</small>
                </div>
            </div>
            <div class="cd-field">
                <input id="expiry" class="cd-input" name="expiry" type="text">
            </div>
        </div>
        <div class="cd-row">
            <div class="cd-title">
                <div class="cd-title-label">
                    @lang('message.dashboard.deposit.deposit-cardconnect-form.cvc')
                </div>
            </div>
            <div class="cd-field">
                <input id="cvvc" class="cd-input" name="cvvc" type="text">
            </div>
        </div>
        <div class="cd-button-container">
            <div class="cd-row button-row">
                <div id="myToken"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <button id="btn_submit" class="btn btn-cust float-left" style="margin-top:10px;" type="submit">
                    @lang('message.form.submit')
                </button>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script type="text/javascript">
        var DATA_TYPE;
        var TOKEN_PROPERTY;

        var cardServices = {
            init : function(){
                var self = this;

                //show the initial loading msg
                self.working(true, 'Loading...');

                //initially hide the error handling rows
                $(".cd-err-row").hide();

                //hide the token display element
                $("#myToken").hide();

                //add the listener for messages from the frame
                window.addEventListener('message', function(event) {
                    var token;
                    var mytoken;

                    //reset the token value
                    $("#myToken").text("");
                    $("#myToken").hide();

                    //create the token response from the data
                    token = self.parse(event.data);

                    //check for errors
                    if (token.validationError != undefined) {

                        //remove focus from other elements
                        $("#tokenframe").focus();

                        //show the error msg
                        $("#cc_error").show();

                        //remove the msg after about 3 seconds
                        setTimeout(function(){
                            $("#cc_error").fadeOut();
                        },3400);

                        //there's an issue... let the user know
                        //we also use this as a cover while the hosted iFrame
                        //loads and gets styled in the background
                        self.working(true, 'Issue...');

                        //reload the iframe
                        self.setFrame();
                        return false;
                    }

                    //evaluate the token against for validity
                    $("#myToken").text(token[TOKEN_PROPERTY]);
                    // $("#myToken").fadeIn();

                }, false);

                //this creates the frame
                self.setFrame();

                //sets the global handling for
                //proper token validation
                TOKEN_PROPERTY = self.scrubInput(self.getQueryVariable("tokenpropname", "message"), 30, "message", /^[0-9a-zA-Z]+$/);

                //simple example of ajax call to pass token
                //expiry date and cvvc to server
                $("#btn_submit").off("click").on("click", function(event){
                    event.preventDefault();
                    self.submitButton();
                });
            },
            ajax : function(data, url, success){
                var self = this;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: DATA_TYPE,
                    timeout: function(){
                        alert("Timeout");
                    },
                    success: function(data){

                        success(data);
                    }
                })
            },
            getQueryVariable : function(variable, defaultValue){
                var self = this;
                var query = window.location.search.substring(1);
                var vars = query.split("&");

                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    if (pair[0] == variable) {
                        return pair[1];
                    }
                }
                return defaultValue ? defaultValue : (false);
            },
            isNumeric : function(n){
                var self = this;
                return !isNaN(parseFloat(n)) && isFinite(n);
            },
            parse : function(data){
                var self = this;

                return JSON.parse(data);
            },
            scrubInput : function(input, maxlen, fallback, pattern){
                var self = this;

                if (input && input.length !== 0 && input.length <= maxlen) {
                    if(input.match(pattern)){
                        return input;
                    }
                }
                return fallback;
            },
            setFrame : function(){
                var self = this;

                //style the frame to match your form
                var style = '&css=input:focus{outline: none;}body{margin:0!important;}';
                style = style + 'input{margin: 0px; padding: 12px 10px; font-size: 15px;';
                style = style + 'font-family: arial; background-color: whitesmoke; border: none; width: 1000px;}';

                //create the querystr parameters to pass to the frame
                var opts = '?invalidinputevent=true';
                opts = opts + '&enhancedresponse=true';
                opts = opts + '&formatinput=true';
                opts = opts + style;

                //the complete url for the frame
                var url = 'https://fts.cardconnect.com:6443/itoke/ajax-tokenizer.html' + opts;

                //load the frame
                $("#tokenframe").attr("src", url);

                //keep the working element visible for another 300ms
                //as the frame finishes loading styles
                //you can adjust this time if it's not enough
                setTimeout(function(){
                    self.working(false);
                },300);
            },
            submitButton : function(){
                var self = this;
                var token = $.trim($("#myToken").text());
                var expiry = $.trim($("#expiry").val());
                var cvvc = $.trim($("#cvvc").val());

                //simple check for sample data (you will want to do more validation)
                // if ( token == '' || expiry == '' || cvvc == '') {
                //     alert("Please enter valid card data before submitting.")
                //     return false;
                // }

                var form = document.getElementById('payment-form');
                $('#payment-form').append('<input type="hidden" name="cardToken" value="' + token + '">');
                form.submit();

            },
            working : function(toggle, msg=''){
                var self = this;

                if (msg == '') {
                    msg='working...';
                }

                if (toggle == true) {
                    $("#_working")
                    .text(msg)
                    .show();
                } else {
                    $("#_working").fadeOut();
                }
            }
        }

        $(function(){
            cardServices.init();
        });

        DATA_TYPE = "json";
    </script>
@endsection