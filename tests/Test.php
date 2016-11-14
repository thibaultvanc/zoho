<?php




namespace Organit\Zoho\Tests;

use Organit\Zoho\Zoho;
use Auth;
use Faker\Generator as Fake;


class Test
{

  public $organization_id = '634457325';
  protected $fake;

  public function __construct()
  {
    $this->fake = \Faker\Factory::create();



  }



  public function createContact($params=null)
  {
    Auth::loginUsingId(1);

    $PICAFLOR = Zoho::organization()->init($this->organization_id);

    return $creaContact = $PICAFLOR->contacts()->create([
          "contact_name"=>$this->fake->name,
          "company_name"=>$this->fake->company,
          "notes"=>"Created by ORGANIT for testing"
        ]);
  }




  public function createItem($params=null)
  {
    Auth::loginUsingId(1);

    $PICAFLOR = Zoho::organization()->init($this->organization_id);

    $request = [
                  // 'currency_code'=>'gg',
                  'name'=> 'item' . $this->fake->lastName,
                  'rate'=>rand(1,200),
                ];


    return $PICAFLOR->items()->create($request)['data'];
  }






  public function createAccount($params)
  {



    Auth::loginUsingId(1);

    $PICAFLOR = Zoho::organization()->init($this->organization_id);

    return $creaAccount = $PICAFLOR->accounts()->create([
      "account_name"=>"Account". str_random(6),
      "account_type"=>"bank",
      "account_number"=>$this->fake->bankAccountNumber,
      "currency_id"=>$params['currency_id'],
      "description"=>"Salary details.",
      "bank_name"=>$this->fake->lastName . ' Bank',
      "routing_number"=>"123456789",
      "is_primary_account"=>false,
      "is_paypal_account"=>true,
      "paypal_email_address"=>"johnsmith@zilliuminc.com"

    ]);
  }


  public function createPayment($params)
  {

    // dd('$params',$params );


    Auth::loginUsingId(1);

    $PICAFLOR = Zoho::organization()->init($this->organization_id);

    $payment =  $PICAFLOR->payments()->create([
      "customer_id"=>$params['customer_id'],
      "invoice_id"=>$params['invoice_id'],
      "currency_id"=>$params['currency_id'],
      "account_id"=>$params['account_id'],

      "payment_mode"=> "Cash",
      "description"=> "",
      "date"=> \Carbon::now()->format('Y-m-d'),
      "reference_number"=> $this->fake->bankAccountNumber,
      "exchange_rate"=> 1.00,
      "amount"=> $params['amount'],
      "bank_charges"=> 0.00,

    ]);


    //register credits
    // $PICAFLOR->invoices($params['invoice_id'])->applyCredit($payment['data']->payment_id,$params['amount']);

    return $payment;

  }




  public function createInvoice($params)
  {

    Auth::loginUsingId(1);

    $PICAFLOR = Zoho::organization()->init($this->organization_id);

    // dd('$PICAFLOR->items()->index()',$PICAFLOR->items()->index()['data'][0] );
    // dd('--->',$PICAFLOR->items()->index()['data'][0] ->item_id );


    return $PICAFLOR->invoices()->create([
      "customer_id"=> $params['customer_id'] ,
      "date"=> \Carbon::now()->format('Y-m-d'),
      "payment_terms_label"=> "Net 15",
      "line_items"=> [
        [
          "item_id"=> $this->createItem()->item_id,
          "item_order"=> 1,
          "unit"=> "Nos",
          "quantity"=> rand(1,9),
          "discount"=> rand(0,10),
        ]

      ],
    ]);
  }


}
