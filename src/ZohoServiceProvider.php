<?php

namespace Organit\Zoho;

use Organit\Zoho\Zoho;
use Illuminate\Support\ServiceProvider;

class ZohoServiceProvider extends ServiceProvider
{




  public function boot()
  {
    include __DIR__ . '/routes.php';
    $this->loadViewsFrom(__DIR__ . '/Views', 'view');


    // $this->publishes([
    //     __DIR__ . '/Config' => base_path('config/')
    // ]);
  }


  public function register()
  {
      $this->app['zoho'] = $this->app->share(function($app){
        return new Zoho;
      });

  }


}
