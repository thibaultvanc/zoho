<?php


namespace {
  use Organit\Zoho\Zoho;
  use Organit\Zoho\Invoice;





  class InvoiceTest extends TestCase
  {

    protected $org_id;
    protected $invoice_id;

    public function __construct()
    {
      $this->org_id = '634457325';
      $this->invoice_id = '';
    }

    /** @test */
    public function it_fetch_invoice_list()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $invoices = $PICAFLOR->invoices()->index();
      // dd($invoices);

      $this->assertTrue(count($invoices)>0);
    }



    /** @test */
    public function it_fetch_invoice_list_with_filter()
    {


      Auth::loginUsingId(1);


      $PICAFLOR = Zoho::organization()->init('634457325');
      //create a contact

      $creaContact = $PICAFLOR->contacts()->create([
        "contact_name"=>"contact " . str_random(6),
        "company_name"=>"Test ". str_random(6),
        "notes"=>"Created by PHPUNIT for testing"
      ]);



      //Ad 2 invoices


      $creaInvoice1 = $PICAFLOR->invoices()->create([
        "customer_id"=> $creaContact['data']->contact_id,
        "date"=> \Carbon::now()->format('Y-m-d'),
        "payment_terms_label"=> "Net 15",
        "line_items"=> [
          [
            "item_id"=> $PICAFLOR->items()->index()['data'][0]->item_id,
            "item_order"=> 1,
            "unit"=> "Nos",
            "quantity"=> rand(1,9),
            "discount"=> rand(0,10),
          ]

        ],
      ]);
      // dd('$creaInvoice1',$creaInvoice1 );
      $creaInvoice2 = $PICAFLOR->invoices()->create([
        "customer_id"=> $creaContact['data']->contact_id,
        "date"=> \Carbon::now()->format('Y-m-d'),
        "payment_terms_label"=> "Net 15",
        "line_items"=> [
          [
            "item_id"=> $PICAFLOR->items()->index()['data'][0]->item_id,
            "item_order"=> 1,
            "unit"=> "Nos",
            "quantity"=> rand(1,9),
            "discount"=> rand(0,10),
          ]

        ],
      ]);


      // filter1

      $params = array(
        'customer_id'=>$creaContact['data']->contact_id
      );

      $invoices = $PICAFLOR->invoices()->index(null, $params)['data'];

      //verify we have 2 invoices
      $this->assertTrue(count($invoices)==2);
      $this->assertTrue($invoices[0]->invoice_id == $creaInvoice2['data']->invoice_id);
      $this->assertTrue($invoices[1]->invoice_id == $creaInvoice1['data']->invoice_id);

      // filter2
      $params = array(
        'status'=>'draft',
        'customer_id'=>$creaContact['data']->contact_id
      );

      $invoices = $PICAFLOR->invoices()->index(null, $params)['data'];
      // dd('$invoices',$invoices );

      //verify we have 2 invoices status draft
      $this->assertTrue(count($invoices)==2);
      $this->assertTrue($invoices[0]->invoice_id == $creaInvoice2['data']->invoice_id);
      $this->assertTrue($invoices[1]->invoice_id == $creaInvoice1['data']->invoice_id);









      // //delete the 2 invoices
      $response = $PICAFLOR->invoices()->delete($creaInvoice1['data']->invoice_id);
      $this->assertEquals($response['code'],0);
      //
      // //delete the 2 invoices
      $response2 = $PICAFLOR->invoices()->delete($creaInvoice2['data']->invoice_id);
      $this->assertEquals($response2['code'],0);



      // delete the contact
      $response = $PICAFLOR->contacts()->delete($creaContact['data']->contact_id);
      $this->assertEquals($response['code'],0);

    }




    /** @test */
    public function it_fetch_invoice_object()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      // $invoice_id = $this->invoice_id;
      $invoice_id = $PICAFLOR->invoices()->index()['data'][0]->invoice_id;

      $org = $PICAFLOR->invoices()->get($invoice_id)['data'];
      // dd('$org',$org );


      $this->assertTrue($org->invoice_id==$invoice_id);
    }



    /** @test */
    public function it_create_invoice()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      $request =
      [
        "customer_id"=> "402626000000101003",



        "date"=> \Carbon::now()->format('Y-m-d'),

        "payment_terms_label"=> "Net 15",


        "contact_persons"=> [
          "402626000000101005"
        ],
        "line_items"=> [
          [
            "item_id"=> "402626000000055773",
            "project_id"=> "",
            "expense_id"=> "",
            "salesorder_item_id"=> "",

            "name"=> "Hard Drive",
            "description"=> "500GB, USB 2.0 interface 1400 rpm, protective hard case.",
            "item_order"=> 1,
            "rate"=> 120.00,
            "unit"=> "Nos",
            "quantity"=> 4.00,
            "discount"=> 0.00,

          ]

        ],
        "allow_partial_payments"=> true,
        "custom_body"=> "",
        "custom_subject"=> "",
        "notes"=> "Thanks for your business.",
        "terms"=> "Terms and conditions apply.",
        "shipping_charge"=> 7.50,
        "adjustment"=> 15.50,
        "adjustment_description"=> "Adjustment"
      ];


      $crea = $PICAFLOR->invoices()->create($request)['data'];
      // dd('$crea',$crea );


      $fetchIt = $PICAFLOR->invoices()->get($crea->invoice_id)['data'];

      $this->assertTrue($crea->invoice_id==$fetchIt->invoice_id);
    }


    /** @test */
    public function it_update_invoice()
    {
      // die('11')

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $invoice_id = $PICAFLOR->invoices()->index()['data'][0]->invoice_id;
      // dd('$invoice_id',$invoice_id );

      // $zoho = new InvoicesAPI;

      // $newName = 'invoice ' . str_random(9);
      $newNotes = str_random(3).' '.str_random(6).' '.str_random(2);

      $request =   [
        "invoice_id"=> $invoice_id,
        "customer_id"=> "402626000000101003",



        "date"=> \Carbon::now()->format('Y-m-d'),

        "payment_terms_label"=> "Net 15",


        "contact_persons"=> [
          "402626000000101005"
        ],
        "line_items"=> [
          [
            "item_id"=> "402626000000055773",
            "project_id"=> "",
            "expense_id"=> "",
            "salesorder_item_id"=> "",

            "name"=> "Hard Drive",
            "description"=> "500GB, USB 2.0 interface 1400 rpm, protective hard case.",
            "item_order"=> 1,
            "rate"=> 120.00,
            "unit"=> "Nos",
            "quantity"=> 4.00,
            "discount"=> 0.00,

          ]

        ],
        "allow_partial_payments"=> true,
        "custom_body"=> "",
        "custom_subject"=> "",
        "notes"=> $newNotes,
        "terms"=> "Terms and conditions apply.",
        "shipping_charge"=> 7.50,
        "adjustment"=> 15.50,
        "adjustment_description"=> "Adjustment"
      ];


      $upd = $PICAFLOR->invoices()->update($invoice_id,$request);

      // dd('$upd',$upd );


      $fetchIt = $PICAFLOR->invoices()->get($upd['data']->invoice_id);

      $this->assertTrue($upd['data']->notes==$fetchIt['data']->notes);
    }

    /** @test */
    public function it_delete_invoice()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      //create a new invoice
      $crea = $PICAFLOR->invoices()->create([
        "customer_id"=> "402626000000101003",



        "date"=> \Carbon::now()->format('Y-m-d'),

        "payment_terms_label"=> "Net 15",


        "contact_persons"=> [
          "402626000000101005"
        ],
        "line_items"=> [
          [
            "item_id"=> "402626000000055773",
            "project_id"=> "",
            "expense_id"=> "",
            "salesorder_item_id"=> "",

            "name"=> "Hard Drive",
            "description"=> "500GB, USB 2.0 interface 1400 rpm, protective hard case.",
            "item_order"=> 1,
            "rate"=> 120.00,
            "unit"=> "Nos",
            "quantity"=> 4.00,
            "discount"=> 0.00,

          ]

        ],
        "allow_partial_payments"=> true,
        "custom_body"=> "",
        "custom_subject"=> "",
        "notes"=> "Thanks for your business.",
        "terms"=> "Terms and conditions apply.",
        "shipping_charge"=> 7.50,
        "adjustment"=> 15.50,
        "adjustment_description"=> "Adjustment"
      ]);



      $response = $PICAFLOR->invoices()->delete($crea['data']->invoice_id);
      // dd($response->code );
      $this->assertEquals($response['code'],0);




      // $fetchIt = $PICAFLOR->invoices()->get($crea->invoice_id);
      // dd('$fetchIt',$fetchIt );

      //
      // $this->assertTrue($upd->name==$fetchIt->name);
    }





  }

}
