<?php

// use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseTransactions;



namespace {
  // use Kris\LaravelFormBuilder\Events\AfterFieldCreation;
  // use Kris\LaravelFormBuilder\Events\AfterFormCreation;
  // use Kris\LaravelFormBuilder\Form;
  // use Kris\LaravelFormBuilder\FormBuilder;
  // use Kris\LaravelFormBuilder\FormHelper;
  use Organit\Zoho\Zoho;

  use Organit\Zoho\Payment;





  class PaymentsTest extends TestCase
  {

    // protected $organization_id;
    // protected $invoice_id;
    //
    //
    // public function __construct($organization_id, $invoice_id=null)
    // {
    //   //\Auth::loginUsingId(1);
    //
    //   $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //
    //   $this->organization_id = $organization_id;
    //   $this->invoice_id = $invoice_id;
    //   // $this->invoice_id = $PICAFLOR->invoices()->index()[0];
    //
    //   // dd('$this->invoice_id',$this->invoice_id );
    //
    // }
    //









    /** @test */
    public function it_fetch_payment_list()
    {
      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      // Methode 1 from nothing
      $filters = [
        // 'customer_id'=>$creaContact->contact_id
      ];
      $payments = $PICAFLOR->payments()->index(null,$filters);
      $this->assertEquals(0, $payments['code']);


    }


    // Methode 1 from nothing



    /** @test */
    public function it_fetch_payment_list_by_custommer()
    {
      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      //create a contact
      $contactCreated = Parent::zoho()->createContact()['data'];


      //create an invoice
      $invoiceCreated = Parent::zoho()->createInvoice(['customer_id'=>$contactCreated->contact_id])['data'];

      //take a currency
      $currency = $PICAFLOR->getByID('634457325')->currency_id ;
      // $currency = $PICAFLOR->currencies()->index()['data'][0]->currency_id;

      //create a bank account
      $accountCreated = Parent::zoho()->createAccount(['customer_id'=>$contactCreated->contact_id, 'currency_id'=>$currency])['data'];




      $paymentCreated = Parent::zoho()
            ->createPayment(['customer_id'=>$contactCreated->contact_id,
                              'invoice_id'=>$invoiceCreated->invoice_id,
                              'currency_id'=>$currency,
                              'amount'=>$invoiceCreated->total,
                              'account_id'=>$accountCreated->account_id]
                            )['data'];





      /********/
      $filters = [
        'customer_id'=>$contactCreated->contact_id
      ];
      $payments = $PICAFLOR->payments()->index(null,$filters);
      $this->assertCount(1, $payments['data']);
      /********/

      // die('++paymesntsTest 118++++++++');




      $response = $PICAFLOR->payments()->delete($paymentCreated->payment_id);
      $this->assertEquals($response['code'],0);



      //delete account
      $response = $PICAFLOR->accounts()->delete($accountCreated->account_id);
      $this->assertEquals($response['code'],0);



      //delete invoice
      $response = $PICAFLOR->invoices()->delete($invoiceCreated->invoice_id);
      $this->assertEquals($response['code'],0);


      //delete contact
      $response = $PICAFLOR->contacts()->delete($contactCreated->contact_id);
      $this->assertEquals($response['code'],0);
    }




    /** @test */
    public function it_fetch_payment_list_by_invoice()
    {
      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');







      //create a contact
      $contactCreated = Parent::zoho()->createContact()['data'];


      //create an invoice
      $invoiceCreated = Parent::zoho()->createInvoice(['customer_id'=>$contactCreated->contact_id])['data'];
      // echo "---------invoice--->".$invoiceCreated->invoice_id.'<------';

      //take a currency
      $currency = $PICAFLOR->getByID('634457325')->currency_id ;
      // $currency = $PICAFLOR->currencies()->index()['data'][0]->currency_id;

      //create a bank account
      $accountCreated = Parent::zoho()->createAccount(['customer_id'=>$contactCreated->contact_id, 'currency_id'=>$currency])['data'];




      $paymentCreated = Parent::zoho()
                                    ->createPayment(['customer_id'=>$contactCreated->contact_id,
                                                      'invoice_id'=>$invoiceCreated->invoice_id,
                                                      'currency_id'=>$currency,
                                                      'account_id'=>$accountCreated->account_id,
                                                      'amount'=>$invoiceCreated->total
                                                    ]
                                                    )['data'];

                            // dd('__HERE__');

      //apply credit
      $credit = $PICAFLOR->invoices($invoiceCreated->invoice_id)
                         ->applyCredit($paymentCreated->payment_id,$invoiceCreated->total);
                // ->test();


      // dd('$credit',$credit );


      $this->assertEquals(0, $credit['code']);

      // // check if related to invoice
      //   //fetch th invoice -> verify if its PartiallyPaid or paid
      //   $tetchedInvoice = $PICAFLOR->invoices()->get($invoiceCreated->invoice_id)['data'];
      //   dd('$tetchedInvoice',$tetchedInvoice );

      // $this->assertEquals();

      //check if payment is related on the invoice




      /********/

      $filtered = $PICAFLOR->invoices($invoiceCreated->invoice_id)->payments();
      // dd('$filtered',$filtered );



      // $payments = $PICAFLOR->payments()->index(null,$filters);
      $this->assertCount(1, $filtered['data']);
      /********/

      // die('++paymesntsTest 118++++++++');




      $response = $PICAFLOR->payments()->delete($paymentCreated->payment_id);
      $this->assertEquals($response['code'],0);



      //delete account
      $response = $PICAFLOR->accounts()->delete($accountCreated->account_id);
      $this->assertEquals($response['code'],0);



      //delete invoice
      $response = $PICAFLOR->invoices()->delete($invoiceCreated->invoice_id);
      $this->assertEquals($response['code'],0);


      //delete contact
      $response = $PICAFLOR->contacts()->delete($contactCreated->contact_id);
      $this->assertEquals($response['code'],0);
    }











    //
    // /** @test */
    // public function it_fetch_payment_list()
    // {
    //   Auth::loginUsingId(1);
    //   $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //   //create a contact
    //   $creaContact = $PICAFLOR->contacts()->create([
    //     "contact_name"=>"contact " . str_random(6),
    //     "company_name"=>"Test ". str_random(6),
    //     "notes"=>"Created by PHPUNIT for testing"
    //   ]);
    //
    //
    //   //create invoices
    //   $creaInvoice1 = $this->createAnInvoice(['organization_id'=>$this->org_id,'customer_id'=>$creaContact->contact_id]);
    //
    //   // create a payment for invoice
    //   $creaPayment1 = Zoho::payments()->create([
    //     'date'=>\Carbon::now()->format('Y-m-d'),
    //     'customer_id'=>$creaContact->contact_id,
    //     'invoice_id'=>$creaInvoice1->invoice_id,
    //     'amount'=>$creaInvoice1->total
    //   ]);
    //
    //   // dd('$creaInvoice1',$creaInvoice1 );
    //
    //
    //
    //   // Methode 2 from an invoice
    //   $filters = [
    //     //'customer_id'=>$creaContact->contact_id
    //   ];
    //   // dd('$this->invoice_id ---1',$creaInvoice1->invoice_id );
    //
    //   $creaPayment2 = $PICAFLOR->invoices($creaInvoice1->invoice_id)->payments();
    //
    //
    //   dd('$creaPayment2', $creaPayment2);
    //   dd($creaPayment2['data']);
    //   dd($creaPayment2['code']);
    //
    //   $this->assertTrue(count($payments)>0);
    //
    //
    //
    //
    //
    //   // Methode 1 from nothing
    //   $filters = [
    //     'customer_id'=>$creaContact->contact_id
    //   ];
    //   $payments = Zoho::payments()->index(null,$filters);
    //   $this->assertCount(1, $payments['data']);
    //
    //   //remove
    // }

    //
    //
    // /** @test */
    // public function it_fetch_payment_object()
    // {
    //
    //   Auth::loginUsingId(1);
    // $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //
    //   // $payment_id = $this->payment_id;
    //   $payment_id = $PICAFLOR->payments()->index()[0]->payment_id;
    //
    //   $org = $PICAFLOR->payments()->get($payment_id);
    //   // dd('$org',$org );
    //
    //
    //   $this->assertTrue($org->payment_id==$payment_id);
    // }
    //
    // /** @test */
    // public function it_create_payment()
    // {
    //
    //   Auth::loginUsingId(1);
    // $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //
    //   $request = [
    //                 'currency_code'=>'gg',
    //                 'name'=>str_random(6),
    //                 'rate'=>120,
    //               ];
    //
    //
    //   $crea = $PICAFLOR->payments()->create($request);
    //
    //   $fetchIt = $PICAFLOR->payments()->get($crea->payment_id);
    //
    //   $this->assertTrue($crea->payment_id==$fetchIt->payment_id);
    // }
    //
    //
    // /** @test */
    // public function it_update_payment()
    // {
    //
    //   Auth::loginUsingId(1);
    // $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //   $payment_id = $PICAFLOR->payments()->index()[0]->payment_id;
    //   // $zoho = new ItemsAPI;
    //
    //   // $newName = 'payment ' . str_random(9);
    //   $newName = 'payment ' . rand(1000000,9000000);
    //
    //   $request = [
    //                 'currency_code'=>'gg',
    //                 'name'=>$newName,
    //                 'rate'=>120,
    //               ];
    //
    //
    //   $upd = $PICAFLOR->payments()->update($payment_id,$request);
    //
    //
    //   $fetchIt = $PICAFLOR->payments()->get($upd->payment_id);
    //
    //   $this->assertTrue($upd->name==$fetchIt->name);
    // }
    //
    /** @test */
    public function it_delete_payment()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      //create a contact
      $contactCreated = Parent::zoho()->createContact()['data'];


      //create an invoice
      $invoiceCreated = Parent::zoho()->createInvoice(['customer_id'=>$contactCreated->contact_id])['data'];

      //take a currency
      $currency = $PICAFLOR->getByID('634457325')->currency_id ;
      // $currency = $PICAFLOR->currencies()->index()['data'][0]->currency_id;

      //create a bank account
      $accountCreated = Parent::zoho()->createAccount(['customer_id'=>$contactCreated->contact_id, 'currency_id'=>$currency])['data'];




      $paymentCreated = Parent::zoho()
            ->createPayment(['customer_id'=>$contactCreated->contact_id,
                              'invoice_id'=>$invoiceCreated->invoice_id,
                              'amount'=>$invoiceCreated->total,
                              'currency_id'=>$currency,
                              'account_id'=>$accountCreated->account_id]
                            )['data'];

      /********/
      $response = $PICAFLOR->payments()->delete($paymentCreated->payment_id);
      $this->assertEquals($response['code'],0);
      /********/



      //delete account
      $response = $PICAFLOR->accounts()->delete($accountCreated->account_id);
      $this->assertEquals($response['code'],0);



      //delete invoice
      $response = $PICAFLOR->invoices()->delete($invoiceCreated->invoice_id);
      $this->assertEquals($response['code'],0);


      //delete contact
      $response = $PICAFLOR->contacts()->delete($contactCreated->contact_id);
      $this->assertEquals($response['code'],0);





    }







  }

}
