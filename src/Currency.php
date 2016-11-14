<?php
namespace Organit\Zoho;



use Auth;
use App\User;
use Cache;

use Organit\Zoho\Core;
use Organit\Zoho\Person;
use Organit\Zoho\Invoice;
use Log;
use Config;






class Currency extends Core
{
  // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
  // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";


  // private $currency;

  // private $entity = 'currencies';
  // private $scope = "ZohoBooks/booksapi";

  // protected $email_id;
  // protected $password;
  // protected $token;
  // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
  protected $currency;
  protected $currencyList;
  // protected $guzzle;

  /**
  * Constructor.
  * $organization_id: Organisation ID.
  * $email_id: Address mail.
  * $password: Passe word.
  * $token: token if you wont use a unique token. Manage Auth Tokens: https://www.zoho.com/crm/help/api/using-authentication-token.html#Manage_Auth_Tokens
  */
  public function __construct($organization_id, $currency_id=null)
  {
    // die('123');
    Parent::__construct();
    $this->organization_id = $organization_id;

    $this->currency = $currency_id;
    // dd('$this->guzzle',$this->guzzle );
    // dd('------->',$this->organization_id);

    // $this->guzzle = new \GuzzleHttp\Client();

    // $this->email_id = Auth::user()->email;
    // $this->password = Auth::user()->zoho_password;
    // $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);

  }


  // /**
  //  * Gets lists of currencies.
  //  * $filter_by: Filter currencies by any status or payment expected date.
  //  *             Allowed Values: ?
  //  *             and Date.PaymentExpectedDate.
  //  */

  public function index($filters=null)
  {


    $list =  $this->find(null, $filters);
    // return $list->currencies;

    return ['data'=> $this->currencyList];



  }

  /**
  * Get currency by id.
  */
  public function find($currency_id, $filter_by=null)
  {
    $result = "";

    $endpoint = $this->ComposeFullUrl($currency_id, $filter_by);
    // echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposeFullUrl($id, $filter_by);
        //echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();

        dd($result);
      }

      // dd('1111');
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }


    if ($currency_id) {
      $this->currency=json_decode($result)->currency;
      return $this;
    }


    $this->currencyList=json_decode($result)->currencies;
    return $this;
    // return json_decode($result);
  }





  /**
  * Get currency by id.
  */
  public function get()
  {
    // $currency = $this->find($currency_id, $filter_by);
    if ($this->currency) {
      return ['data'=>$this->currency];
    }

    if ($this->currencyList) {
      // return $this->currencyList;
      return ['data'=>$this->currencyList];
    }


    // return json_decode($result);
  }














  /**
  * Create currency.
  */
  public function create($updateCurrency)
  {
    $result = "";

    // dd('$updateCurrency',$updateCurrency );


    $endpoint = $this->ComposePostPutURL();
    // echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      //echo json_encode($updateCurrency) . '<br>';

      $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateCurrency)]])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposePostPutURL($id);
        //echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateCurrency)]])->getBody();
      }
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    $response =  json_decode($result);

    return ['data'=> $response->currency, 'code'=>$response->code, 'message'=>$response->message];

  }











  /**
  * Update currency.
  */
  public function update($id, $updateCurrency)
  {
    $result = "";

    $endpoint = $this->ComposePostPutURL($id);
    // echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      //echo json_encode($updateCurrency) . '<br>';

      $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateCurrency)]])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposePostPutURL($id);
        // echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateCurrency)]])->getBody();
      }
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    $response =  json_decode($result);

    return ['data'=> $response->currency, 'code'=>$response->code, 'message'=>$response->message];
  }









  /**
  * Delete currency.
  */
  public function delete($currency_id)
  {
    $result = "";

    $endpoint = $this->ComposeFullUrl($currency_id);
    // echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      $result = $this->guzzle->request('DELETE',$endpoint, ['verify' => false])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposePostPutURL($id);
        // echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('DELETE',$endpoint, ['verify' => false])->getBody();
      }
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    $response =  json_decode($result);
    // dd('$response',$response );

    return ['code'=>$response->code, 'message'=>$response->message];
  }










  /**
  * Compose full URL.
  */
  private function ComposeFullUrl($id=null, $filters=null)
  {
    // $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;
    // dd('$this->organization_id',$this->organization_id );


    $url = $this->currenciesUrl;
    // dd('$url',$url );


    if($id)
    {
      $url .=  "/$id/";
    }

    $url .= '?authtoken=' . $this->token . '&organization_id=' . $this->organization_id;

    if($filters)
    {
      $filter = '';
      foreach($filters as $key => $value){
        $filter = $filter.'&'.$key.'='.$value;
      }
      $url .= $filter;
    }
    // dd('$url',$url );

    return $url;
  }

  /**
  * Compose URL to Post or PUT data.
  */
  private function ComposePostPutURL($id=null, $ignore_auto_number_generation=false, $send=false)
  {
    // $url = $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;
    $url = $url = $this->currenciesUrl;
    if($id)
    {
      $url .=  "/$id/";
    }


    return $url .  '?organization_id=' . $this->organization_id . '&ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token;// . '&organization_id=' . Config::get('settings.organization_id');
  }



}
