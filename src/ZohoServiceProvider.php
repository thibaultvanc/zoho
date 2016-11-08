<?php

namespace Organit\Zoho;


use Illuminate\Support\ServiceProvider;

class ZohoServiceProvider extends ServiceProvider
{




  public function boot()
  {
    include __DIR__ . '/routes.php';
    $this->loadViewsFrom(__DIR__ . '/Views', 'view');
  }


  public function register()
  {
      $this->app['zoho'] = $this->app->share(function($app){
        return new Zoho;
      });

  }


}
