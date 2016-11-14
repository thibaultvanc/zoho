<?php


namespace {
  use Organit\Zoho\Zoho;
  use Organit\Zoho\Account;





  class AccountTest extends TestCase
  {


    /** @test */
    public function it_fetch_account_list()
    {

      // die('111');
      Auth::loginUsingId(1);


      $PICAFLOR = Zoho::organization()->init('634457325');

      // dd($org->accounts());


      $PICAFLOR = Zoho::organization()->init('634457325');
      $accounts = $PICAFLOR->accounts()->index()['data'];


      $this->assertTrue(count($accounts)>0);
    }






    /** @test */
    public function it_fetch_account_object()
    {

      Auth::loginUsingId(1);


      // $account_id = $this->account_id;
      $PICAFLOR = Zoho::organization()->init('634457325');
      $account_id = $PICAFLOR->accounts()->index()['data'][0]->account_id;
      // dd('$PICAFLOR->accounts()->find($account_id)',$PICAFLOR->accounts()->find($account_id)->get() );

      $org = $PICAFLOR->accounts()->find($account_id)->get()['data'];
      // dd('$org',$org );


      $this->assertTrue($org->account_id==$account_id);
    }

    /** @test */
    public function it_create_account()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $request =
      [
        "account_name"=>"Account". str_random(6),
        "account_type"=>"bank",
        "account_number"=>str_random(12),
        "currency_id"=>$PICAFLOR->getByID('634457325')->currency_id,
        "description"=>"details.",
        "bank_name"=>str_random(6) . ' Bank',
        "routing_number"=>"123456789",
        "is_primary_account"=>false,
        "is_paypal_account"=>true,
        "paypal_email_address"=>"johnsmith@zilliuminc.com"
      ];


      $crea = $PICAFLOR->accounts()->create($request)['data'];
      // dd('$PICAFLOR->accounts()->find($crea->account_id)->get()',$PICAFLOR->accounts()->find($crea->account_id)->get() );

      $fetchIt = $PICAFLOR->accounts()->find($crea->account_id)->get()['data'];

      $this->assertTrue($crea->account_id==$fetchIt->account_id);
    }


    /** @test */
    public function it_update_account()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $account_id = $PICAFLOR->accounts()->index()['data'][0]->account_id;
      // $zoho = new AccountsAPI;

      // $newName = 'account ' . str_random(9);
      $newName = 'account ' . rand(1000000,9000000);

      $request = [
        "account_name"=>"Account". str_random(6),
        "account_type"=>"bank",
        "account_number"=>str_random(12),
        "currency_id"=>$PICAFLOR->getByID('634457325')->currency_id,
        "description"=>"details.",
        "bank_name"=>str_random(6) . ' Bank',
        "routing_number"=>"123456789",
        "is_primary_account"=>false,
        "is_paypal_account"=>true,
        "paypal_email_address"=>"johnsmith@zilliuminc.com"
      ];


      $upd = $PICAFLOR->accounts()->update($account_id,$request)['data'];


      $fetchIt = $PICAFLOR->accounts()->find($upd->account_id)->get()['data'];

      $this->assertTrue($upd->account_name==$fetchIt->account_name);
    }



    //
    // /** @test */
    // public function it_delete_account()
    // {
    //
    //   Auth::loginUsingId(1);
    //   $PICAFLOR = Zoho::organization()->init('634457325');
    //
    //   //create a contact
    //   $contactCreated = Parent::zoho()->createContact()['data'];
    //
    //   $currency = $PICAFLOR->getByID('634457325')->currency_id ;
    //
    //   //create a new account
    //   $accountCreated = Parent::zoho()->createAccount(['customer_id'=>$contactCreated->contact_id, 'currency_id'=>$currency])['data'];
    //
    //
    //
    //   $response = $PICAFLOR->accounts()->delete($accountCreated->account_id);
    //   // dd($response->code );
    //   $this->assertEquals($response['code'],0);
    //
    //
    //   //delete account
    //   $response = $PICAFLOR->accounts()->delete($accountCreated->account_id);
    //   $this->assertEquals($response['code'],0);
    //
    //
    //
    //
    //   //delete contact
    //   $response = $PICAFLOR->contacts()->delete($contactCreated->contact_id);
    //   $this->assertEquals($response['code'],0);
    //
    //
    //   // $fetchIt = $PICAFLOR->accounts()->get($crea->account_id);
    //   // dd('$fetchIt',$fetchIt );
    //
    //   //
    //   // $this->assertTrue($upd->name==$fetchIt->name);
    // }
    //








  }

}
