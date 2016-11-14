<?php
namespace Organit\Zoho;



use Auth;
use App\User;
use Cache;
use GuzzleHttp\Client as Guzzle;
use Organit\Zoho\Core;
use Log;
use Config;






class Person extends Core
{
    // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
    // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";


    // private $contact;

    // private $entity = described bellow;
    private $scope = "ZohoBooks/booksapi";

    // protected $email_id;
    // protected $password;
    // protected $token;
    // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
    //
    // protected $guzzle;
    protected $person_id;

    /**
     * Constructor.
     * $organization_id: Organisation ID.
     * $email_id: Address mail.
     * $password: Passe word.
     * $token: token if you wont use a unique token. Manage Auth Tokens: https://www.zoho.com/crm/help/api/using-authentication-token.html#Manage_Auth_Tokens
     */
    public function __construct($organization_id,$person_id)
    {

        Parent::__construct();
        // dd('$person_id',$person_id );

        // $this->guzzle = new \GuzzleHttp\Client();
        $this->organization_id = $organization_id;

        $this->contact_id = $person_id;
        // $this->email_id = Auth::user()->email;
        // $this->password = Auth::user()->zoho_password;
        // $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);

    }




    // public function get()
    // {
    //   // dd('111');
    //   // $endpoint = 'https://books.zoho.com/api/v3/contacts/'.$person_id.'?authtoken=' . $this->token;
    //   $endpoint = 'https://books.zoho.com/api/v3/organizations?authtoken=' . $this->token;
    //   $response =  $this->guzzle->request('GET',$endpoint)->getBody();
    //
    //
    //
    //   return $response;
    // }



    // /**
    //  * Gets lists of transactions.
    //  * $filter_by: Filter transactions by any status or payment expected date.
    //  *             Allowed Values: ?
    //  *             and Date.PaymentExpectedDate.
    //  */

    public function index($filter_by=null)
    {
      // dd($filter_by);
        return  $this->get(null, $filter_by);
        // dd($list);
        // return $list->contact_persons;

    }

    /**
     * Get transaction by id.
     */
    public function get($person_id, $filter_by=null)
    {

      // dd('$person_id',$person_id );



        $result = "";

        $endpoint = $this->ComposeFullUrl($person_id, $filter_by);
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

        $response = json_decode($result);
        if ($person_id) {
          return ['data'=> $response->contact_person, 'code'=>$response->code, 'message'=>$response->message];
        }
        return ['data'=> $response->contact_persons, 'code'=>$response->code, 'message'=>$response->message];
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
            // echo json_encode($updateContact) . '<br>';

            $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateContact)]])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposePostPutURL($id);
                // echo 'URL: ' . $endpoint  . '<br>';

                // Request using a new token
                $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateContact)]])->getBody();
            }
        }
        catch (Exception $e)
        {
            $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        $response =  json_decode($result);

        return ['data'=> $response->contact_person, 'code'=>$response->code, 'message'=>$response->message];
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
            // echo json_encode($updateContact) . '<br>';

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

        return ['data'=> $response->contact_person, 'code'=>$response->code, 'message'=>$response->message];
    }



    /**
     * Delete contact.
     */
    public function delete($person_id)
    {
        $result = "";

        $endpoint = $this->ComposePostPutURL($person_id);
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
            $result = $this->guzzle->request('DELETE',$endpoint, ['verify' => false])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposePostPutURL($person_id);
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
    private function ComposeFullUrl($id=null, $filter_by=null)
    {
        $url = $this->URL_BOOKS_ZOHO_ENDPOINT .'contacts/'.$this->contact_id.'/contactpersons';

        if($id)
        {
            $url .=  "/$id/";
        }

        $url .= '?authtoken=' . $this->token . '&organization_id=' . $this->organization_id;

        if($filter_by)
        {
            $url .  '?filter_by=' . $filter_by;
        }
        // dd($url);

        return $url;
    }

    /**
     * Compose URL to Post or PUT data.
     */
    private function ComposePostPutURL($id=null, $ignore_auto_number_generation=false, $send=false)
    {
        $url = $this->URL_BOOKS_ZOHO_ENDPOINT .'contacts/contactpersons';

        if($id)
        {
            $url .=  "/$id/";
        }

        return $url .  '?organization_id=' . $this->organization_id . '&ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token;// . '&organization_id=' . Config::get('settings.organization_id');
    }



}
