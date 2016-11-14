<?php


namespace Organit\Zoho;

use Auth;
use Organit\Zoho\Zoho;
use Organit\Zoho\Organization;
use Organit\Zoho\Contact;
use Organit\Zoho\Currency;
use Organit\Zoho\Invoice;
use Organit\Zoho\Payment;
use Organit\Zoho\Account;
use GuzzleHttp\Client as Guzzle;

/**
*
*/
class Core
{


  public $zohoBooksApiVersion = "3";
  public $endPointUrl;
  public $apiKey;
  public $contactsUrl;
  public $estimatesUrl;
  public $invoicesUrl;
  public $recurringInvoicesUrl;
  public $creditNotesUrl;
  public $customerPaymentsUrl;
  public $expensesUrl;
  public $recurringExpensesUrl;
  public $billsUrl;
  public $vendorPaymentsUrl;
  public $bankAccountsUrl;
  public $bankTransactionsUrl;
  public $bankRulesUrl;
  public $chartOfAccountsUrl;
  public $journalsUrl;
  public $baseCurrencyAdjustmentUrl;
  public $projectsUrl;
  public $settingsUrl;
  public $currenciesUrl;

  public $URL_ZOHO_APIAUTHTOKEN = "https://accounts.zoho.com/apiauthtoken/nb/create";
  public $URL_BOOKS_ZOHO_ENDPOINT = "https://books.zoho.com/api/v3/";


  public $organization_id;
  public $organizationList;

  public $token_auto_regenerate = TRUE; // You can disable auto generate invalid token.
  public $token;

  public $guzzle;


  public function __construct()
  {




    $this->endPointUrl               = "https://books.zoho.com/api/v{$this->zohoBooksApiVersion}/";
    $this->taskUrl                   = $this->endPointUrl."tasks";
    $this->contactsUrl               = $this->endPointUrl."contacts";
    $this->estimatesUrl              = $this->endPointUrl."estimates";
    $this->invoicesUrl               = $this->endPointUrl."invoices";
    $this->recurringInvoicesUrl      = $this->endPointUrl."recurringinvoices";
    $this->creditNotesUrl            = $this->endPointUrl."creditnotes";
    $this->customerPaymentsUrl       = $this->endPointUrl."customerpayments";
    $this->expensesUrl               = $this->endPointUrl."expenses";
    $this->recurringExpensesUrl      = $this->endPointUrl."recurringexpenses";
    $this->billsUrl                  = $this->endPointUrl."bills";
    $this->vendorPaymentsUrl         = $this->endPointUrl."vendorpayments";
    $this->bankAccountsUrl           = $this->endPointUrl."bankaccounts";
    $this->bankTransactionsUrl       = $this->endPointUrl."banktransactions";
    $this->bankRulesUrl              = $this->endPointUrl."bankaccounts/rules";
    $this->chartOfAccountsUrl        = $this->endPointUrl."chartofaccounts";
    $this->journalsUrl               = $this->endPointUrl."journals";
    $this->baseCurrencyAdjustmentUrl = $this->endPointUrl."basecurrencyadjustment";
    $this->projectsUrl               = $this->endPointUrl."projects";
    $this->settingsUrl               = $this->endPointUrl."settings/preferences";
    $this->currenciesUrl             = $this->endPointUrl."settings/currencies";




    $this->guzzle = new \GuzzleHttp\Client();

    $this->email_id = Auth::user()->email;
    $this->password = Auth::user()->zoho_password;
    $this->token = (Auth::user()->zoho_token) ? Auth::user()->zoho_token : $this->GetToken($this->email_id, $this->password);



    if (session('organization_id') !== null) {
      // dd('session',session('organization_id'));
      # code...
    }


  }




  public function organization($id=null)
  {
    return new Organization($id);
  }

  public function contacts($id=null)
  {
    // dd($this);
    return new Contact($this->organization_id, $id);
  }

  public function payments($id=null)
  {
    return new Payment($this->organization_id, $id);
  }
  public function currencies($id=null)
  {
    return new Currency($this->organization_id, $id);
  }
  public function accounts($id=null)
  {
    return new Account($this->organization_id, $id);
  }

  // public function invoices($id=null)
  // {
  //   return new Invoice($id);
  // }
  //







  public function parse_ini_string_withCommentSharp($in)
  {
    $result = "";
    ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
    $result = parse_ini_string($in);
    return $result;
  }




  /**
  * Get token authentication.
  */
  public function GetToken($email_id, $password)
  {
    $result = "";

    $endpoint = $this->URL_ZOHO_APIAUTHTOKEN;
    //echo 'URL: ' . $endpoint  . '<br>';

    try
    {
      $result = $this->guzzle->request('POST',$endpoint, ['verify' => false, 'form_params' => ['SCOPE' => $this->scope, 'EMAIL_ID' => $email_id, 'PASSWORD' => $password]])->getBody();
    }
    catch (Exception $e)
    {
      $result = 'Exception : ' .  $e->getMessage() . "\n";
    }

    $result = $this->parse_ini_string_withCommentSharp($result);

    if($result['RESULT'])
    {
      $result = $result['AUTHTOKEN'];
      //echo "Genereted token: $result";
    }
    else
    {
      print_r($result);
      die();
    }

    return $result;
  }



  /**
  * Check authtoken validation an regenerate it.
  */
  public function isAuthtokenInvalidRegenerate($in)
  {
    $result = false;

    if (is_array($in) && $in->code == 14 && $this->token_auto_regenerate)
    {
      $this->token = $this->GetToken($email_id, $password);
      $result = true;
    }

    return $result;
  }




}




?>
