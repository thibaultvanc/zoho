<?php

namespace Organit\Zoho;


use Auth;



/**
 *
 */
class ZohoCore
{

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
      echo 'URL: ' . $endpoint  . '<br>';

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
          echo "Genereted token: $result";
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
