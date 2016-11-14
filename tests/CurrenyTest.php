<?php


namespace {
  use Organit\Zoho\Zoho;
  use Organit\Zoho\phpDocumentor\Reflection\Types\This;





  class CurrenyTest extends TestCase
  {


    /** @test */
    public function it_fetch_currency_list()
    {

      // die('111');
      Auth::loginUsingId(1);


      $PICAFLOR = Zoho::organization()->init('634457325');

      // dd($org->currencies());


      $PICAFLOR = Zoho::organization()->init('634457325');
      $currencies = $PICAFLOR->currencies()->index();


      $this->assertTrue(count($currencies)>0);
    }






    /** @test */
    public function it_fetch_currency_object()
    {

      Auth::loginUsingId(1);


      // $currency_id = $this->currency_id;
      $PICAFLOR = Zoho::organization()->init('634457325');
      $currency_id = $PICAFLOR->currencies()->index()['data'][0]->currency_id;

      $org = $PICAFLOR->currencies()->find($currency_id)->get()['data'];
      // dd('$org',$org );


      $this->assertTrue($org->currency_id==$currency_id);
    }



    /** @test */
    public function it_create_currency()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');
      // echo('strtoupper(str_random(3)) =====> '.strtoupper(str_random(3)) );

      $request =
      [
        "currency_code"=> "CAD",
        "currency_symbol"=> "$",
        "price_precision"=> 2,
        "currency_format"=> "1,234,567.89"
      ];


      // $crea = $PICAFLOR->currencies()->create($request)['data'];
      //
      // if ($crea['code'] == 9005) { // Le code devise « ... » existe déjà"
      //   $this->assertTrue($crea['code']==9005);
      //   # code...
      // }
      // else{
      //   $fetchIt = $PICAFLOR->currencies()->find($crea->currency_id)->get()['data'];
      //
      //   $this->assertTrue($crea->currency_id==$fetchIt->currency_id);
      //
      // }

    }


    // /** @test */
    // public function it_update_currency()
    // {
    //
    //   Auth::loginUsingId(1);
    //   $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //   $currency_id = $PICAFLOR->currencies()->index()['data']['data'][0]->currency_id;
    //   // $zoho = new ThisAPI;
    //
    //   // $newName = 'currency ' . str_random(9);
    //   $newcurrency_symbol = 'USD';
    //
    //   $request = [
    //     // "currency_code"=> $newcurrency_code,
    //     "price_precision"=> 2,
    //     // "price_precision"=> 2,
    //     // "currency_format"=> "1,234,567.89"
    //   ];
    //
    //
    //   $upd = $PICAFLOR->currencies()->update($currency_id,$request)['data'];
    //
    //
    //   $fetchIt = $PICAFLOR->currencies()->find($upd->currency_id)->get()['data'];
    //
    //   $this->assertTrue($upd->currency_name==$fetchIt->currency_name);
    // }
    //




    // /** @test */
    // public function it_delete_currency()
    // {
    //
    //   Auth::loginUsingId(1);
    //   $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //   //create a new currency
    //   $crea = $PICAFLOR->currencies()->create([
    //     "currency_name"=>"currency " . str_random(6),
    //     "company_name"=>"Bowman and Co",
    //   ]);
    //
    //
    //
    //   $response = $PICAFLOR->currencies()->delete($crea['data']->currency_id);
    //   // dd($response->code );
    //   $this->assertEquals($response['code'],0);
    //
    //
    //
    //
    //   // $fetchIt = $PICAFLOR->currencies()->get($crea->currency_id);
    //   // dd('$fetchIt',$fetchIt );
    //
    //   //
    //   // $this->assertTrue($upd->name==$fetchIt->name);
    // }







  }

}
