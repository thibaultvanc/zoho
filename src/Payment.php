<?php
namespace Organit\Zoho;



use Auth;
use App\User;
use Cache;
use GuzzleHttp\Client as Guzzle;
use Organit\Zoho\Core;
use Log;
use Config;






class Payment extends Core
{
    // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
    // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";


    private $invoice;

    // private $entity = described bellow;
    private $scope = "ZohoBooks/booksapi";

    // protected $email_id;
    // protected $password;
    // protected $token;
    // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
    //
    // protected $guzzle;
    protected $invoice_id;

    /**
     * Constructor.
     * $organization_id: Organisation ID.
     * $email_id: Address mail.
     * $password: Passe word.
     * $token: token if you wont use a unique token. Manage Auth Tokens: https://www.zoho.com/crm/help/api/using-authentication-token.html#Manage_Auth_Tokens
     */
    public function __construct($organization_id, $invoice_id=null)
    {

        Parent::__construct();

        $this->guzzle = new \GuzzleHttp\Client();
        $this->organization_id = $organization_id;

        $this->invoice_id = $invoice_id;
        // dd('$this->invoice_id',$this->invoice_id );


        $this->email_id = Auth::user()->email;
        $this->password = Auth::user()->zoho_password;
        $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);


    }



    public function setInvoiceId($invoice_id)
    {
      $this->invoice_id = $invoice_id;
      return $this;
    }







    // /**
    //  * Gets lists of transactions.
    //  * $filter_by: Filter transactions by any status or payment expected date.
    //  *             Allowed Values: ?
    //  *             and Date.PaymentExpectedDate.
    //  */

    public function index($filter_by=null, $filters=null)
    {

      // dd($this->invoice_id);


        $response =  $this->get(null, $filter_by, $filters);

        if ($this->invoice_id) {
          $data = $response->payments;
        }else {
          $data = $response->customerpayments;
        }

        return ['message'=>$response->message, 'code'=>$response->code, 'data'=>$data];
    }



    /**
     * Get transaction by id.
     */
    public function get($transaction_id, $filter_by=null, $filters=null)
    {

        $result = "";

        $endpoint = $this->ComposeFullUrl($transaction_id, $filter_by, $filters);
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
            $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposeFullUrl($id, $filter_by, $filters);
                // echo 'URL: ' . $endpoint  . '<br>';

                // Request using a new token
                $result = $this->guzzle->request('GET',$endpoint, ['verify' => false])->getBody();
            }
        }
        catch (Exception $e)
        {
            $result = 'Exception : ' .  $e->getMessage() . "\n";
        }


        return json_decode($result);
    }








    public function create($updateTransaction)
    {


        $result = "";

        $endpoint = $this->ComposePostPutURL();
        // echo "<br><hr>";
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
            // echo json_encode($updateTransaction) . '<br>';

            $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateTransaction)]])->getBody();

            if($this->isAuthtokenInvalidRegenerate($result))
            {
                $endpoint = $this->ComposePostPutURL($id);
                // echo 'URL: ' . $endpoint  . '<br>';

                // Request using a new token
                $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateTransaction)]])->getBody();
            }
        }
        catch (Exception $e)
        {
            $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        $response =  json_decode($result);

        return ['data'=> $response->payment, 'code'=>$response->code, 'message'=>$response->message];
    }





      /**
      * Delete payment.
      */
      public function delete($payment_id)
      {
        $result = "";

        $endpoint = $this->ComposeFullUrl($payment_id);
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
    private function ComposeFullUrl($id=null, $filter_by=null, $filters=null)
    {



        $url = $this->customerPaymentsUrl;
        if ($this->invoice_id) {
          $url = $this->URL_BOOKS_ZOHO_ENDPOINT .'invoices/'.$this->invoice_id.'/payments';
        }

        if($id)
        {
            $url .=  "/$id/";
        }

        $url .= '?authtoken=' . $this->token . '&organization_id=' . $this->organization_id;

        if($filter_by)
        {
            $url .  '?filter_by=' . $filter_by;
        }
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
        // $url = $this->URL_BOOKS_ZOHO_ENDPOINT .'customerpayments';
        $url = $this->customerPaymentsUrl;

        if($id)
        {
            $url .=  "/$id/";
        }

        return $url .  '?organization_id=' . $this->organization_id . '&ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token;// . '&organization_id=' . Config::get('settings.organization_id');
    }



}
