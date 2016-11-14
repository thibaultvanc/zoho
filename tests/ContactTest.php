<?php


namespace {
  use Organit\Zoho\Zoho;
  use Organit\Zoho\Contact;





  class ContactTest extends TestCase
  {


    /** @test */
    public function it_fetch_contact_list()
    {

      // die('111');
      Auth::loginUsingId(1);


      // $PICAFLOR = Zoho::organization()->init('634457325');

      // dd($org->contacts());


      $PICAFLOR = Zoho::organization()->init('634457325');
      $contacts = $PICAFLOR->contacts()->index()['data'];


      $this->assertTrue(count($contacts)>0);
    }




    /** @test */
    public function it_fetch_contact_list_with_filters()
    {

      Auth::loginUsingId(1);



      $params = [
                  // 'status'=>'active',
                  'contact_name'=>'SebastienTest2',
                  // 'contact_id'=>'402626000000115347', //marche pas
                ];
      $PICAFLOR = Zoho::organization()->init('634457325');
      $contacts = $PICAFLOR->contacts()->index($params)['data'];
      // dd($contacts);

      $this->assertTrue(count($contacts)>0);
    }





    /** @test */
    public function it_fetch_contact_object()
    {

      Auth::loginUsingId(1);


      // $contact_id = $this->contact_id;
      $PICAFLOR = Zoho::organization()->init('634457325');
      $contact_id = $PICAFLOR->contacts()->index()['data'][0]->contact_id;

      $org = $PICAFLOR->contacts()->find($contact_id)->get()['data'];
      // dd('$org',$org );


      $this->assertTrue($org->contact_id==$contact_id);
    }

    /** @test */
    public function it_create_contact()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $request =
      [
        "contact_name"=>"contact " . str_random(6),
        "company_name"=>"Bowman and Co",
        "payment_terms"=>15,
        "payment_terms_label"=>"Net 15",
        "website"=>"www.bowmanfurniture.com",
        "billing_address"=> [
          "address"=>"4900 Hopyard Rd, Suite 310",
          "city"=>"Pleasanton",
          "state"=>"CA",
          "zip"=>"94588",
          "country"=>"USA",
          "fax"=>"+1-925-924-9600"
          ]
        ,
        "shipping_address"=>[
          "address"=>"Suite 125, McMillan Avenue",
          "city"=>"San Francisco",
          "state"=>"CA",
          "zip"=>"94134",
          "country"=>"USA",
          "fax"=>"+1-925-924-9600"
        ],
        "contact_persons"=>[
          [
            "salutation"=>"Mr.",
            "first_name"=>"Will",
            "last_name"=>"Smith",
            "email"=>"willsmith@bowmanfurniture.com",
            "phone"=>"+1-925-921-9201",
            "mobile"=>"+1-4054439562",
            "is_primary_contact"=>true
          ],
          [
            "salutation"=>"Mr.",
            "first_name"=>"Peter",
            "last_name"=>"Parker",
            "email"=>"peterparker@bowmanfurniture.com",
            "phone"=>"+1-925-929-7211",
            "mobile"=>"+1-4054439760"
          ]
        ],
        "notes"=>"Payment option : Through check"
      ];


      $crea = $PICAFLOR->contacts()->create($request)['data'];
      // dd('$PICAFLOR->contacts()->find($crea->contact_id)->get()',$PICAFLOR->contacts()->find($crea->contact_id)->get() );

      $fetchIt = $PICAFLOR->contacts()->find($crea->contact_id)->get()['data'];

      $this->assertTrue($crea->contact_id==$fetchIt->contact_id);
    }


    /** @test */
    public function it_update_contact()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $contact_id = $PICAFLOR->contacts()->index()['data'][0]->contact_id;
      // $zoho = new ContactsAPI;

      // $newName = 'contact ' . str_random(9);
      $newName = 'contact ' . rand(1000000,9000000);

      $request = [
        'currency_code'=>'gg',
        'name'=>$newName,
        'rate'=>120,
      ];


      $upd = $PICAFLOR->contacts()->update($contact_id,$request)['data'];


      $fetchIt = $PICAFLOR->contacts()->find($upd->contact_id)->get()['data'];

      $this->assertTrue($upd->contact_name==$fetchIt->contact_name);
    }

    /** @test */
    public function it_delete_contact()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      //create a new contact
      $crea = $PICAFLOR->contacts()->create([
        "contact_name"=>"contact " . str_random(6),
        "company_name"=>"Bowman and Co",
      ]);
      // dd('$crea',$crea );



      $response = $PICAFLOR->contacts()->delete($crea['data']->contact_id);
      // dd($response->code );
      $this->assertEquals($response['code'],0);




      // $fetchIt = $PICAFLOR->contacts()->get($crea->contact_id);
      // dd('$fetchIt',$fetchIt );

      //
      // $this->assertTrue($upd->name==$fetchIt->name);
    }





    /** @test */
    public function it_fetch_the_invoices_for_a_given_contact()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      //create a new contact
      $creaContact = $PICAFLOR->contacts()->create([
        "contact_name"=>"contactaaaaa " . str_random(6),
        "company_name"=>"Bowman and Co",
      ]);
      // dd('$creaContact',$creaContact->contact_id );



      //dd('contactTest l 228 --->$PICAFLOR->items()',$PICAFLOR->items()->organization_id );



      $itemList = $PICAFLOR->items()->index()['data'];

      // dd('$itemList',$itemList );



      $creaInvoice1 = $PICAFLOR->invoices()->create([
      "customer_id"=> $creaContact['data']->contact_id,
      "date"=> \Carbon::now()->format('Y-m-d'),
      "payment_terms_label"=> "Net 15",
     "line_items"=> [
        [
          "item_id"=> $itemList[0]->item_id,
          "unit"=> "Nos",
          "quantity"=> rand(0,5),
        ]

      ],
        ]);

      $creaInvoice2 = $PICAFLOR->invoices()->create([
      "customer_id"=> $creaContact['data']->contact_id,
      "date"=> \Carbon::now()->format('Y-m-d'),
      "payment_terms_label"=> "Net 15",
     "line_items"=> [
        [
          "item_id"=> $itemList[1]->item_id,
          "unit"=> "Nos",
          "quantity"=> rand(0,5),
        ]

      ],
        ]);
      $creaInvoice3 = $PICAFLOR->invoices()->create([
      "customer_id"=> $creaContact['data']->contact_id,
      "date"=> \Carbon::now()->format('Y-m-d'),
      "payment_terms_label"=> "Net 15",
     "line_items"=> [
        [
          "item_id"=> $itemList[2]->item_id,
          "unit"=> "Nos",
          "quantity"=> rand(0,5),
        ]

      ],
        ]);


      $response = $PICAFLOR->contacts()->find($creaContact['data']->contact_id)->invoices();
      // dd('$itemList' );

      $this->assertEquals(count($response),3);


      // //delete the 3 invoices
      $response = $PICAFLOR->invoices()->delete($creaInvoice1['data']->invoice_id);
      $this->assertEquals($response['code'],0);
      //

      $response2 = $PICAFLOR->invoices()->delete($creaInvoice2['data']->invoice_id);
      $this->assertEquals($response2['code'],0);

      $response3 = $PICAFLOR->invoices()->delete($creaInvoice3['data']->invoice_id);
      $this->assertEquals($response3['code'],0);



      // delete the contact
      $response = $PICAFLOR->contacts()->delete($creaContact['data']->contact_id);
      $this->assertEquals($response['code'],0);


      // $fetchIt = $PICAFLOR->contacts()->get($crea->contact_id);
      // dd('$fetchIt',$fetchIt );

      //
      // $this->assertTrue($upd->name==$fetchIt->name);
    }





  }

}
