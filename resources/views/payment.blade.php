@extends('layouts.master')
<title>Continue to checkout</title>
{{-- @section('titleHere')
    Continue to Payment
@endsection --}}
<style>
    .pageCenter {
        margin: auto auto;
        padding: auto auto;
        /* padding-top:  20%; */
        /* padding-buttom:  10%; */
    }
    .center {
        display: block;
        margin: auto auto;
    }
    .logoImg {
        /* background-image: url("images/unique.png"); */
        width: 80px; padding: 5px; border-radius: 3px; background-color: #fff;
        box-shadow: 0px 4px 4px -4px rgba(0,0,0,0.75);
        -webkit-box-shadow: 0px 4px 4px -4px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px 4px 4px -4px rgba(0,0,0,0.75);
        margin-bottom: 10px;
        cursor: pointer;
        bor
    }
    .total {
        font-weight: 900;
        margin-top: 20px;
        max-width: 350px;
        margin: 20px auto;
        padding: 10px;
        max-height: 50px;
        color: #000;
        cursor: pointer;
    }

    .box {
    position: relative;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    -webkit-transition: all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
    transition: all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .box::after {
    content: "";
    border-radius: 5px;
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    opacity: 0;
    -webkit-transition: all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
    transition: all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .box:hover {
    -webkit-transform: scale(1.03, 1.03);
    transform: scale(1.03, 1.03);
  }

  .box:hover::after {
      opacity: 1;
  }
</style>

<div class="pageCenter">
    @section('content')
    <br><br>
    <div class="container">
        <div>
            <img src="/images/unique.png" alt="Ade Unique" class="logoImg">
            <h3>News | AdeUnique</h3>

        <div class="center">
            <br><br><hr>
            <div class="container">
                <div class="alert">
                    <h5>Subcription Data</h5>
                    Subscription Type: {{ $subscriptionType }} <br>
                    Subscription Duration: {{ $period }} {{ $period > 1 ? 'Months' : 'Month' }}<br>

                    <div class="total box">
                        <h3>Total Amount: {{ $priceInNaira }}</h3><br>
                    </div>
                </div>
                </div>
            </div>
              <p><i>..we bring you news with ease!</i></p>
        </div>


        <form method="POST" action="{{ route('pay') }}" accept-charset="UTF-8" class="form-horizontal" role="form">
                <div class="row" style="margin-bottom:40px;">
                  <div class="center col-lg-8 col-md-12 col-sm-12">
                <input type="hidden" name="email" value="{{ $email }}"> {{-- required --}}
                <input type="hidden" name="orderID" value="orderId">
                <input type="hidden" name="metadata" value="{{ json_encode($array = ['invoice_reference' => $invoice_reference, 'invoice_id' => $invoice_id]) }}" >
                  <input type="hidden" name="amount" value="{{ $totalPrice }}"> {{-- required in kobo --}}
                  <input type="hidden" name="quantity" value="1">
                  <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                  <input type="hidden" name="key" value="{{ config('paystack.secretKey') }}"> {{-- required --}}
                  {{ csrf_field() }}

                     {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> employ this in place of csrf_field only in laravel 5.0 --}}

                    <p>
                      <button class="btn btn-success btn-lg btn-block center" type="submit" value="Pay Now">
                      <i class="fa fa-plus-circle fa-lg"></i> Pay Now!
                      </button>
                    </p>
                  </div>
                </div>
        </form>

      </div>
    @endsection
</div>
