<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Subtype;
use App\Country;
use App\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SubsController;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->subCtrl = new SubsController();
    }
    public function index($reference)
    {
        $invoice = Invoice::whereReference($reference)->first();
        return $invoice == null ? null : $this->formatOne($invoice);
    }

    public function all()
    {
        $invoiceS = Auth::User()->client->invoices;
        if (count($invoiceS) > 0 ) {
            foreach($invoiceS as $invoice) {
                $invoice = $this->formatOne($invoice);
            }
        }
        return $invoiceS;
    }

    public function formatOne($invoice)
    {
        $invoice->categories = explode("|", $invoice->categories);
        $countries = array(explode("|", $invoice->countries));
        // $invoice->expired = new Carbon('today') > $invoice->expiry ? true : false;
        foreach ($countries as $country) {
            $invoice->countries = Country::find($country);
        }
        $invoice->subtype;
        return $invoice;
    }

    public function store(Request $request)
    {
        $subtype = Subtype::find($request->subtype);
        $span = $request->span;

        if ($span <= 0 || is_nan($span)) {
            return response()->json(['success' => false]);
        }

        $price = $subtype->price * $span;
        $expiry = new Carbon($span. ' month');
        $client_id = Auth::User()->client->id;

        $countries = array();
        $categories = array();

        // Ensure that number of countries and categories selected are not more than subtype maximum
        for($i = 0; $i < $subtype->countrycount; $i++) {
            if ($i < count($request->countries)) {
                array_push($countries, $request->countries[$i]);
            } else {
                break;
            }
        }
        for($i = 0; $i < $subtype->categorycount; $i++) {
            if ($i < count($request->categories)) {
                array_push($categories, $request->categories[$i]);
            } else {
                break;
            }
        }

        $countries = implode("|", $countries);
        $categories = implode("|", $categories);

        // Determine if the sub is free => if yes, span must not exceed 3 months && No need for payment or invoice
        if ($subtype->price == 0) {
            $span = $request->span > 3 ? 3 : $request->span;
            $req = new Request([
                'client_id' => $client_id,
                'subtype_id' => $subtype->id,
                'countries' => $countries,
                'categories' => $categories,
                'span' => $span,
                'expiry' =>  new Carbon($span. ' month')
            ]);

            return $this->subCtrl->create($req);
        }

        $newInvoice = new Invoice();
        $newInvoice->client_id = $client_id;
        $newInvoice->subtype_id = $subtype->id;
        $newInvoice->price = $price;
        $newInvoice->span = $span;
        $newInvoice->countries = $countries;
        $newInvoice->categories = $categories;
        $newInvoice->reference = $request->reference;

        if ($newInvoice->save()) {
            return response()->json([
                'success' => true,
                'paynow' => true,
                'reference' => $request->reference,
                'invoices' => $this->all(),
                'invoiceToPay' => $this->formatOne($newInvoice)
            ], 201);
        }
    }

    public function update($id)
    {
        $invoice = Invoice::find($id);
        $invoice->paid = true;
        if ($invoice->update()) {
            $req = new Request([
                'client_id' => $invoice->client_id,
                'subtype_id' => $invoice->subtype_id,
                'invoice_id' => $invoice->id,
                'price' => $invoice->price,
                'span' => $invoice->span,
                'countries' => $invoice->countries,
                'categories' => $invoice->categories,
                'expiry' =>  new Carbon($invoice->span. ' month')
            ]);
            $done =  $this->subCtrl->store($req);
            if ($done['success']) {
                return redirect('https://api4news.adeunique.com/#/subscriptions/paid');
            } else {
                echo 'Something went wrong. Please query the transaction on your subscription page';
            }
        }
    }

    public function destroy($id)
    {
        if (Invoice::destroy($id)) {
            return $this->returner(200, $this->all());
        } else {
            return $this->returner(500, $this->all());
        }
    }

    public function returner($code, $invoices) {
        return response()->json(['success' => true, 'invoices' => $invoices], $code);
    }
}
