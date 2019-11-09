@extends('outPayment.layouts.app')


@section('content')
  <!-- Tab panes -->
  <div class="tab-content text-left">
    <div class="tab-pane active" id="home">
      <div class="col-md-4 text-left">
        <div class='form-group trans_details'>
          <label for='exampleInputEmail1'> @lang('message.form.name') </label>
          <div class=''> {{$requestPayment->user->first_name}} {{$requestPayment->user->last_name}}
          </div>
        </div>
        <div class='form-group trans_details'>
          <label for='exampleInputEmail1'> @lang('message.form.email') </label>
          <div class=''> {{$requestPayment->email}} </div>
        </div>
        <div class='form-group trans_details'>
          <label for='exampleInputEmail1'> @lang('message.dashboard.left-table.transaction-id')
          </label>
          <div class=''> {{$requestPayment->uuid}} </div>
        </div>
        <div class='form-group trans_details'>
          <label for='exampleInputEmail1'> @lang('message.dashboard.left-table.details') </label>
          <div class='clearfix'></div>
          <div class='left '>
            @lang('message.dashboard.send-request.request.confirmation.requested-amount') </div>
          <div class='right '>
            {{moneyFormat($requestPayment->currency->symbol, formatNumber(abs($requestPayment->amount)))}}
          </div>
          <div class='clearfix'></div>
          <div class='left '> @lang('message.dashboard.left-table.fee') </div>
          <div class='right '>
            {{moneyFormat($requestPayment->currency->symbol, formatNumber($requestPayment->amount * (@$transfer_fee->charge_percentage/100) + @$transfer_fee->charge_fixed))}}
          </div>
          <div class='clearfix'></div>
          <hr />
          <div class='left '><strong> @lang('message.dashboard.left-table.total') </strong></div>
          <div class='right '>
            <strong>
              {{moneyFormat($requestPayment->currency->symbol, formatNumber(abs($requestPayment->amount + $requestPayment->amount * (@$transfer_fee->charge_percentage/100) + @$transfer_fee->charge_fixed)))}}
            </strong>
          </div>
          <div class='clearfix'></div>
        </div>

        <div class='form-group trans_details'>
          <label for='exampleInputEmail1'> @lang('message.dashboard.left-table.transferred.note')
          </label>
          <div class='act-detail-font'> {{$requestPayment->note}} </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="pull-right">
          <a href="#payment" data-toggle="tab" class="btn btn-primary">
            @lang('message.form.cancel')
          </a>
          <a href="#payment" data-toggle="tab" class="btn btn-primary">
            @lang('message.dashboard.left-table.request-to.accept')
          </a>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="payment">
      <!--- CardConnect GATEWAY START-->
      <form id="CardConnect" name="CardConnect" method="post" action="{{url('outpayment/cardconnect')}}" accept-charset="UTF-8">
        {{csrf_field()}}
        <div class="form-group">
          <label for='exampleInputEmail1'> @lang('message.dashboard.deposit.amount') </label>
          <input class="form-control" name="amount" value="{{ abs($requestPayment->amount) }}">
        </div>
        <div class='form-group trans_details'>
          <div class='clearfix'></div>
          <div class='clearfix'></div>
          <div class='left '> @lang('message.dashboard.left-table.fee') </div>
          <div class='right '>
            {{moneyFormat($requestPayment->currency->symbol, 
              formatNumber($requestPayment->amount * (@$transfer_fee->charge_percentage/100) + @$transfer_fee->charge_fixed))
            }}
          </div>
          <div class='clearfix'></div>
          <hr />
          <div class='left '><strong> @lang('message.dashboard.left-table.total') </strong></div>
          <div class='right '>
            <strong>
              {{moneyFormat($requestPayment->currency->symbol, formatNumber(abs($requestPayment->amount + $requestPayment->amount * (@$transfer_fee->charge_percentage/100) + @$transfer_fee->charge_fixed)))}}
            </strong>
          </div>
          <div class='clearfix'></div>
        </div>

        <input class="form-control" name="id" value="{{ $requestPayment->id }}" type="hidden">
        <input class="form-control" name="currency_id" value="{{ $requestPayment->currency_id }}" type="hidden">
        <input class="form-control" name="percentage_fee" value="{{ $requestPayment->amount * (@$transfer_fee->charge_percentage/100) }}"  type="hidden">
        <input class="form-control" name="fixed_fee" value="{{ @$transfer_fee->charge_fixed }}"  type="hidden">   
              
        <div class="row">
          <div class="col-md-12">
            <div class="bs-callout-warning">
              <p>@lang('message.express-payment-form.payment-agreement')</p>
            </div>
            <div class="pull-right">
              <a href="#home" data-toggle="tab" class="btn btn-default">@lang('message.express-payment-form.cancel')</a>
              <button type="submit" class="btn btn-primary">@lang('message.express-payment-form.go-to-payment')</button>
            </div>
          </div>
        </div>
      </form>
      <!--- CardConnect GATEWAY END-->
      
    </div>
  </div>
@endsection
