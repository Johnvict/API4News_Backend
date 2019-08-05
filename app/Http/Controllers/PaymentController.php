<?php

namespace App\Http\Controllers;

use App\User;
use App\Payment;
use App\Exchangerate;

use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Paystack;
use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InvoiceController;
use App\Invoice;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->invoiceCtrl = new InvoiceController();
        $this->Paystack = new Paystack();
    }
    public function getInvoice($reference)
    {
        $invoice = $this->invoiceCtrl->index($reference);

        if ($invoice == null) {
            return response()->json(['error' => 'invoice not found']);
        }
        if ($invoice->paid == true) {
            return response()->json(['error' => 'you have paid this invoice already']);
        }

        /**
         * @param Price
         * Note that the price set here is still in Dollars
         * We should therefore make findings on how Paystack does international payment
         * Meanwhile, we would convert the payment to Naira , then Kobo too
         */

        //  return $invoice->price;
        $email = $invoice->client->user->email;
        $totalPrice = $invoice->price * 100;
        return view('payment')->with([
            "subscriptionType" => $invoice->subtype->title,
            'totalPrice' => $totalPrice,
            'invoice_reference' => $invoice->reference,
            'priceInNaira' => $invoice->price,
            'invoice_id' => $invoice->id,
            'period' => $invoice->span,
            'email' => $email,
            'orderId' => $invoice->id,
        ]);
    }

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {
        $this->Paystack = new Paystack();
        return $this->Paystack->getAuthorizationUrl()->redirectNow($request);
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        // $trxref = "8T91Ghtjml369PtLNmeXCWFkH";
        // $reference = "8T91Ghtjml369PtLNmeXCWFkH";
        $paymentDetails = $this->Paystack->getPaymentData();

        if ($paymentDetails['status'] == true && $paymentDetails['data']['status'] == 'success') {
            $clientId = Invoice::find($paymentDetails['data']['metadata']['invoice_id'])->client->id;

            $paid = new Payment();
            $paid->invoice_id = $paymentDetails['data']['metadata']['invoice_id'];
            $paid->client_id = $clientId;
            $paid->amount = $paymentDetails['data']['amount'];
            $paid->charges = $paymentDetails['data']['fees'];
            $paid->reference = $paymentDetails['data']['reference'];
            $paid->receipId = $paymentDetails['data']['id'];
            $paid->customerId = $paymentDetails['data']['customer']['id'];
            $paid->currency = $paymentDetails['data']['currency'];
            $paid->custumerEmail = $paymentDetails['data']['customer']['email'];

            if($paid->save()) {
                $invoiceCtrl = new InvoiceController();
                return $invoiceCtrl->update($paid->invoice_id);
            }
            return response()->json(['paid' => $paid]);
        }

        // $complete = TransactionController::confirmPayment($paymentDetails);
            $complete = true;
        // dd($paymentDetails);

        return response()->json($paymentDetails);

        if ($complete == true) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }

    public function pricing() {
        $CurrencyController = new CurrencyController();
        $price = Exchangerate::find(1);
        $currentTime = time();

        if ($price == null) {
            $price = $CurrencyController->exchangeRate();
        }

        $diff = $currentTime - $price->sourceTime;

        if ($diff >= 3600) {
            $price = $CurrencyController->exchangeRate();
            $usd = $price;
        } else {
            $usd = $price->usd;
        }
        return $usd;
    }

}
