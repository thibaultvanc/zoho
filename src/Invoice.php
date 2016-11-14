<?php
namespace Organit\Zoho;



use Auth;
use Cache;
use GuzzleHttp\Client as Guzzle;
use Log;






class Invoice extends Core
{
  // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
  // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";

  private $entity = "invoices";
  private $scope = "ZohoBooks/booksapi";

  // protected $organization_id;
  // protected $email_id;
  // protected $password;
  // protected $token;
  // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
  //
  // protected $guzzle;

  protected $applyCredit=false;
  protected $invoice_id;
  protected $invoiceList;

  /**
  * Constructor.
  * $organization_id: Organisation ID.
  * $email_id: Address mail.
  * $password: Passe word.
  * $token: token if you wont use a unique token. Manage Auth Tokens: https://www.zoho.com/crm/help/api/using-authentication-token.html#Manage_Auth_Tokens
  */
  public function __construct($organization_id=null)
  {
    Parent::__construct();





    // $this->guzzle = new \GuzzleHttp\Client();

    $this->organization_id = $organization_id;

    // $this->email_id = Auth::user()->email;
    // $this->password = Auth::user()->zoho_password;
    // $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);
  }





  public function payments($filters=null)
  {


    // $request = [
    //   'customer_id'=>$this->contact->contact_id,
    // ];
    // return (new Payment($this->invoice_id))->index(null, $request);
    // return (new Payment())->setInvoiceId($this->invoice_id);
    return (new Payment($this->organization_id, $this->invoice_id))->index(null,$filters);
  }





  public function applyCredit($payment_id, $amount)
  {

    // dd('apply '. $amount . 'credit -from payment '.$payment_id.'-to invoice :'.$this->invoice_id);
    $result = "";

    $this->applyCredit = true;


    $post = [
      "invoice_payments"=> [
        [
          "payment_id"=> $payment_id,
          "amount_applied"=> $amount
        ],
      ],
      "apply_creditnotes"=> [
          // [
          //   "creditnote_id"=> "460000000029003",
          //   "amount_applied"=> $amount
          // ],
        ]
      ];

      // dd('$post',$post );


        $endpoint = $this->ComposePostPutURL();
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
          echo json_encode($post) . '<----------------------->';

          $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($post)]])->getBody();

          if($this->isAuthtokenInvalidRegenerate($result))
          {
            $endpoint = $this->ComposePostPutURL($id);
            // echo 'URL: ' . $endpoint  . '<br>';

            // Request using a new token
            $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($post)]])->getBody();
          }
        }
        catch (Exception $e)
        {
          $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        // return 'hello';
        $response =  json_decode($result);
        return ['data'=> $response->use_credits, 'code'=>$response->code, 'message'=>$response->message];



        //dd('invoice.php109 /applyCredit---> ',$response );





      }






      public function setInvoiceId($invoice_id)
      {
        $this->invoice_id = $invoice_id;
        return $this;
      }






      /**
      * Gets lists of invoices.
      * $filter_by: Filter invoices by any status or payment expected date.
      *             Allowed Values: Status.All, Status.Sent, Status.Draft, Status.OverDue, Status.Paid, Status.Void, Status.Unpaid, Status.PartiallyPaid, Status.Viewed
      *             and Date.PaymentExpectedDate.
      */
      public function index($filter_by=null, $filters=null)
      {
        // dd($filter_by)
        return  $this->get(null, $filter_by, $filters);
        // return ['data'=> $response->invoices];
        // return $list->invoices;

      }

      /**
      * Get invoice by id.
      */
      public function get($invoice_id, $filter_by=null, $filters=null)
      {

        // dd('$this->organization_id11',$this->organization_id );



        $result = "";

        $endpoint = $this->ComposeFullUrl($invoice_id, $filter_by, $filters);
        // echo 'URL: ' . $endpoint  . '<br>';
        // dd('$endpoint',$endpoint );

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


        $response =  json_decode($result);


        if ($invoice_id) {

          return ['data'=> $response->invoice, 'code'=>$response->code, 'message'=>$response->message];
          // return json_decode($result)->invoice;
        }

        return ['data'=> $response->invoices, 'code'=>$response->code, 'message'=>$response->message];
        // return json_decode($result);
      }

      /**
      * Create invoice.
      */
      public function create($updateInvoice)
      {
        $result = "";

        $endpoint = $this->ComposePostPutURL();
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
          // echo json_encode($updateInvoice) . '<br>';

          $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateInvoice)]])->getBody();

          if($this->isAuthtokenInvalidRegenerate($result))
          {
            $endpoint = $this->ComposePostPutURL($id);
            // echo 'URL: ' . $endpoint  . '<br>';

            // Request using a new token
            $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateInvoice)]])->getBody();
          }
        }
        catch (Exception $e)
        {
          $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        $response =  json_decode($result);

        return ['data'=> $response->invoice, 'code'=>$response->code, 'message'=>$response->message];
      }



      /**
      * Update invoice.
      */
      public function update($id, $updateInvoice)
      {
        $result = "";

        $endpoint = $this->ComposePostPutURL($id);
        // echo 'URL: ' . $endpoint  . '<br>';

        try
        {
          // echo json_encode($updateInvoice) . '<br>';

          $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateInvoice)]])->getBody();

          if($this->isAuthtokenInvalidRegenerate($result))
          {
            $endpoint = $this->ComposePostPutURL($id);
            // echo 'URL: ' . $endpoint  . '<br>';

            // Request using a new token
            $result = $this->guzzle->request('PUT',$endpoint, ['verify' => false, 'form_params' => ['JSONString' => json_encode($updateInvoice)]])->getBody();
          }
        }
        catch (Exception $e)
        {
          $result = 'Exception : ' .  $e->getMessage() . "\n";
        }

        $response =  json_decode($result);

        return ['data'=> $response->invoice, 'code'=>$response->code, 'message'=>$response->message];
      }



      /**
      * Delete invoice.
      */
      public function delete($invoice_id)
      {
        $result = "";

        $endpoint = $this->ComposeFullUrl($invoice_id);
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
      private function ComposeFullUrl($id=null, $filter_by=null, $filters=null)
      {
        //dd('$filters',$filters );

        // $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;
        $url = $this->invoicesUrl;

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
        if($filters)
        {
          $filter = '';
          foreach($filters as $key => $value){
            $filter = $filter.'&'.$key.'='.$value;
          }
          $url .= $filter;
        }
        // dd('$url', $url);
        return $url;
      }

      /**
      * Compose URL to Post or PUT data.
      */
      private function ComposePostPutURL($id=null, $ignore_auto_number_generation=false, $send=false)
      {
        $url = $this->invoicesUrl;

        if($id)
        {
          $url .=  "/$id/";
        }

        if ($this->applyCredit) {
          $url = $this->endPointUrl . 'invoices/'.$this->invoice_id.'/credits';
        }

        return $url .  '?organization_id=' . $this->organization_id . '&ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token ;
      }

      // /**
      //  * Parse authentication result to extract token.
      //  */
      // public function parse_ini_string_withCommentSharp($in)
      // {
      //     $result = "";
      //     ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
      //     $result = parse_ini_string($in);
      //     return $result;
      // }

    }
