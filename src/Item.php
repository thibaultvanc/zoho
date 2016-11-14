<?php
namespace Organit\Zoho;



use Auth;
use Cache;
use GuzzleHttp\Client as Guzzle;
// use Organit\Organization;
use Log;






class Item extends Core
{
    // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
    // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";

    private $entity = "items";
    private $scope = "ZohoBooks/booksapi";


    public $itemList;
    public $item;

    // protected $organization_id;
    // protected $email_id;
    // protected $password;
    // protected $token;
    // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
    //
    // protected $guzzle;

    /**
     * Constructor.
     * $organization_id: Organisation ID.
     * $email_id: Address mail.
     * $password: Passe word.
     * $token: token if you wont use a unique token. Manage Auth Tokens: https://www.zoho.com/crm/help/api/using-authentication-token.html#Manage_Auth_Tokens
     */
    public function __construct($organization_id)
    {
        Parent::__construct();


        // $this->guzzle = new \GuzzleHttp\Client();
        // dd('$organization',$organization );

        $this->organization_id = $organization_id;

        // $this->email_id = Auth::user()->email;
        // $this->password = Auth::user()->zoho_password;
        // $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);
    }

    /**
     * Gets lists of invoices.
     * $filter_by: Filter invoices by any status or payment expected date.
     *             Allowed Values: Status.All, Status.Sent, Status.Draft, Status.OverDue, Status.Paid, Status.Void, Status.Unpaid, Status.PartiallyPaid, Status.Viewed
     *             and Date.PaymentExpectedDate.
     */
    public function index($filter_by=null)
    {
      // dd($filter_by);
        $this->get(null, $filter_by);
        // return ;
        return ['data'=> $this->itemList];

    }

    /**
     * Get invoice by id.
     */
    public function get($item_id, $filter_by=null)
    {

        //dd('variable','variable' );


        $result = "";

        $endpoint = $this->ComposeFullUrl($item_id, $filter_by);
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
            $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposeFullUrl($id, $filter_by);
                // echo 'URL: ' . $endpoint  . '<br>';

                // Request using a new token
                $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();
            }
        }
        catch (Exception $e)
        {
            $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        $response =  json_decode($result);

        if ($item_id) {
          $this->item = $response->item;
          return ['data'=> $response->item, 'code'=>$response->code, 'message'=>$response->message];
          // return json_decode($result)->item;
        }

        // return json_decode($result);

        $this->itemList = $response->items;
        return ['data'=> $response->items, 'code'=>$response->code, 'message'=>$response->message];

        // return ['data'=> $response->items, 'code'=>$response->code, 'message'=>$response->message];
    }




    /**
     * Create invoice.
     */
    public function create($updateItem)
    {
        $result = "";

        $endpoint = $this->ComposePostPutURL();
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
            // echo json_encode($updateItem) . '<br>';

            $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateItem)]])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposePostPutURL($id);
                // echo 'URL: ' . $endpoint  . '<br>';

                // Request using a new token
                $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateItem)]])->getBody();
            }
        }
        catch (Exception $e)
        {
            $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        $response =  json_decode($result);

        return ['data'=> $response->item, 'code'=>$response->code, 'message'=>$response->message];
    }



    /**
     * Update invoice.
     */
    public function update($id, $updateItem)
    {
        $result = "";

        $endpoint = $this->ComposePostPutURL($id);
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
            // echo json_encode($updateItem) . '<br>';

            $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateItem)]])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposePostPutURL($id);
                // echo 'URL: ' . $endpoint  . '<br>';

                // Request using a new token
                $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateItem)]])->getBody();
            }
        }
        catch (Exception $e)
        {
            $result = 'Exception : ' .  $e->getMessage() . "\n";
        }
        $response =  json_decode($result);

        return ['data'=> $response->item, 'code'=>$response->code, 'message'=>$response->message];
        // return json_decode($result)->item;
    }



    /**
     * Delete invoice.
     */
    public function delete($item_id)
    {
        $result = "";

        $endpoint = $this->ComposeFullUrl($item_id);
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

        return ['code'=>$response->code, 'message'=>$response->message];
    }





    /**
     * Compose full URL.
     */
    private function ComposeFullUrl($id=null, $filter_by=null)
    {

        // dd('$this->organization_id',$this->organization_id );


        $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;

        if($id)
        {
            $url .=  "/$id/";
        }

        $url .= '?authtoken=' . $this->token . '&organization_id=' . $this->organization_id;

        if($filter_by)
        {
          // dd($filter_by);
            $url .=  '&filter_by=' . $filter_by;
        }
        // dd('$url', $url);
        return $url;
    }

    /**
     * Compose URL to Post or PUT data.
     */
    private function ComposePostPutURL($id=null, $ignore_auto_number_generation=false, $send=false)
    {
        $url = $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;

        if($id)
        {
            $url .=  "/$id/";
        }

        return $url .  '?organization_id=' . $this->organization_id . '&ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token ;
    }

    /**
     * Parse authentication result to extract token.
     */
    // private function parse_ini_string_withCommentSharp($in)
    // {
    //     $result = "";
    //     ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
    //     $result = parse_ini_string($in);
    //     return $result;
    // }

}
