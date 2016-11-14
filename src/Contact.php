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






class Contact extends Core
{
  // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
  // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";


  // private $contact;

  private $entity = 'contacts';
  private $scope = "ZohoBooks/booksapi";

  // protected $email_id;
  // protected $password;
  // protected $token;
  // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
  protected $contact;
  protected $contactList;
  // protected $guzzle;

  /**
  * Constructor.
  * $organization_id: Organisation ID.
  * $email_id: Address mail.
  * $password: Passe word.
  * $token: token if you wont use a unique token. Manage Auth Tokens: https://www.zoho.com/crm/help/api/using-authentication-token.html#Manage_Auth_Tokens
  */
  public function __construct($organization_id, $contact_id=null)
  {
    // die('123');
    Parent::__construct();
    $this->organization_id = $organization_id;
    $this->contact = $contact_id;
    // dd('$this->guzzle',$this->guzzle );
    // dd('------->',$this->organization_id);

    // $this->guzzle = new \GuzzleHttp\Client();

    // $this->email_id = Auth::user()->email;
    // $this->password = Auth::user()->zoho_password;
    // $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);

  }


  public function invoices()
  {
    $request = [
      'customer_id'=>$this->contact->contact_id,
    ];
    return (new Invoice($this->organization_id))->index(null, $request);
  }



  // public function get()
  // {
  //   // dd('111');
  //   // $endpoint = 'https://books.zoho.com/api/v3/contacts/'.$contact_id.'?authtoken=' . $this->token;
  //   $endpoint = 'https://books.zoho.com/api/v3/organizations?authtoken=' . $this->token;
  //   $response =  $this->guzzle->request('GET',$endpoint)->getBody();
  //
  //
  //
  //   return $response;
  // }



  // /**
  //  * Gets lists of contacts.
  //  * $filter_by: Filter contacts by any status or payment expected date.
  //  *             Allowed Values: ?
  //  *             and Date.PaymentExpectedDate.
  //  */

  public function index($filters=null)
  {


    $response =  $this->find(null, $filters);
    // return $list->contacts;
    // return $this->contactList;
    return ['data'=> $this->contactList];

  }

  /**
  * Get contact by id.
  */
  public function find($contact_id, $filter_by=null)
  {
    $result = "";

    $endpoint = $this->ComposeFullUrl($contact_id, $filter_by);
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


    if ($contact_id) {
      $this->contact=json_decode($result)->contact;
      return $this;
    }


    $this->contactList=json_decode($result)->contacts;


    return $this;
    // return json_decode($result);
  }
  /**
  * Get contact by id.
  */
  public function get()
  {
    // $contact = $this->find($contact_id, $filter_by);
    if ($this->contact) {
      return ['data'=> $this->contact];
      // return $this->contact;
    }

    if ($this->contactList) {
      // return $this->contactList;
      return ['data'=> $this->contactList];
    }


    // $response =  json_decode($result);

  }














  /**
  * Create contact.
  */
  public function create($updateContact)
  {
    $result = "";

    $endpoint = $this->ComposePostPutURL();
    // echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      //echo json_encode($updateContact) . '<br>';

      $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateContact)]])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposePostPutURL($id);
        //echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateContact)]])->getBody();
      }
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    $response =  json_decode($result);

    return ['data'=> $response->contact, 'code'=>$response->code, 'message'=>$response->message];
  }











  /**
  * Update contact.
  */
  public function update($id, $updateContact)
  {
    $result = "";

    $endpoint = $this->ComposePostPutURL($id);
    // echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      //echo json_encode($updateContact) . '<br>';

      $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateContact)]])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposePostPutURL($id);
        // echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateContact)]])->getBody();
      }
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    $response =  json_decode($result);

    return ['data'=> $response->contact, 'code'=>$response->code, 'message'=>$response->message];
  }









  /**
  * Delete contact.
  */
  public function delete($contact_id)
  {
    $result = "";

    $endpoint = $this->ComposeFullUrl($contact_id);
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









  public function personsFromContact($contact_id, $filter_by=null)
  {

    // dd('$contact_id',$contact_id );

    $result = "";

    $endpoint = $this->ComposeFullUrl($contact_id, $filter_by);
    //echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();

      if($this->isAuthtokenInvalidRegenerate($result))
      {
        $endpoint = $this->ComposeFullUrl($id, $filter_by);
        //echo 'URL: ' . $endpoint  . '<br>';

        // Request using a new token
        $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();
      }
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    return new Person($this->organization_id,json_decode($result)->contact->contact_id);
    // return json_decode($result)->contact;
  }






  /**
  * Compose full URL.
  */
  private function ComposeFullUrl($id=null, $filters=null)
  {
    // $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;
    // dd('$this->organization_id',$this->organization_id );


    $url = $this->contactsUrl;
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
    $url = $url = $this->contactsUrl;
    if($id)
    {
      $url .=  "/$id/";
    }


    return $url .  '?organization_id=' . $this->organization_id . '&ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token;// . '&organization_id=' . Config::get('settings.organization_id');
  }



}
