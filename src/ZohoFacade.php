<?php

namespace Organit\Zoho;

use Illuminate\Support\Facades\Facade;

class ZohoFacade extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'zoho';
    }
}
