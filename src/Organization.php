<?php
namespace Organit\Zoho;

use Auth;
use App\User;
use Cache;
// use GuzzleHttp\Client as Guzzle;
use Organit\Zoho\Core;
use Organit\Zoho\Item;
use Organit\Zoho\Invoice;
use Log;

// use App\Models\Plan;




class Organization extends Core
{
    // private $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
    // private $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";

    private $entity = "organizations";
    private $scope = "ZohoBooks/booksapi";

    // protected $email_id;
    // protected $password;
    // protected $token;
    // protected $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.

    // public $guzzle;

    //public $organization_id;


    public function __construct()
    {


        Parent::__construct();
        // $this->guzzle = new Guzzle();

        // session('organization_id', $id);
// dd('session',session('organization_id') );


      // dd($user);



        // dd($this->email_id ,
        //   $this->password ,
        //   $this->token);
    }


    public function init($organization_id)
    {
      $this->organization_id = $organization_id;
      session(['organization_id' => $organization_id]);
      return $this;
    }






    public function items()
    {
      // dd('$this->organization_id',$this->organization_id );
      // dd($this->getByID($this->organization_id));
      return new Item($this->organization_id);
    }



    public function invoices($invoice_id=null)
    {
      // dd('$invoice_id',$invoice_id );
      //
      // dd('(new Invoice($this->organization_id))->setInvoiceId($invoice_id)',(new Invoice($this->organization_id))->setInvoiceId($invoice_id) );

      // if ($invoice_id) {
      //   return (new Invoice($this->organization_id))->setInvoiceId($invoice_id);
      // }
      // return (new Invoice($this->organization_id));
      return (new Invoice($this->organization_id))->setInvoiceId($invoice_id);
    }





    public function get()
    {
      // dd('111');
      // $endpoint = 'https://books.zoho.com/api/v3/items/'.$item_id.'?authtoken=' . $this->token;
      $endpoint = 'https://books.zoho.com/api/v3/organizations?authtoken=' . $this->token;
      $response =  $this->guzzle->request('GET',$endpoint)->getBody();

      $this->organizationList = json_decode($response)->organizations;

      return $this;
    }

















    // /**
    //  * Gets lists of invoices.
    //  * $filter_by: Filter invoices by any status or payment expected date.
    //  *             Allowed Values: Status.All, Status.Sent, Status.Draft, Status.OverDue, Status.Paid, Status.Void, Status.Unpaid, Status.PartiallyPaid, Status.Viewed
    //  *             and Date.PaymentExpectedDate.
    //  */
    // public function index($filter_by=null)
    // {
    //     return $this->get(null, $filter_by);
    // }

    /**
     * Get invoice by id.
     */
    public function getByID($organization_id, $filter_by=null)
    {

      // dd('$organization_id',$organization_id );

        $result = "";

        $endpoint = $this->ComposeFullUrl($organization_id, $filter_by);
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

        return json_decode($result)->organization;
    }





    /**
     * Compose full URL.
     */
    private function ComposeFullUrl($id=null, $filter_by=null)
    {
        $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;

        if($id)
        {
            $url .=  "/$id/";
        }

        $url .= '?authtoken=' . $this->token ;

        if($filter_by)
        {
            $url .  '?filter_by=' . $filter_by;
        }

        return $url;
    }

    /**
     * Compose URL to Post or PUT data.
     */
    // private function ComposePostPutURL($id=null, $ignore_auto_number_generation=false, $send=false)
    // {
    //     $url = $url = $this->URL_BOOKS_ZOHO_ENDPOINT . $this->entity;
    //
    //     if($id)
    //     {
    //         $url .=  "/$id/";
    //     }
    //
    //     return $url .  '?ignore_auto_number_generation=' . $ignore_auto_number_generation . '&send=' . $send .'&authtoken=' . $this->token . '&organization_id=' . $this->organization_id;
    // }

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
