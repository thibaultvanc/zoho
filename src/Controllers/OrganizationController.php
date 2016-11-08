<?php

namespace Organit\Zoho\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class OrganizationController extends Controller
{

  function index(Request $request)
  {
    $var = 'testing Pachage Creation';
    return view('view::index', compact('var'));
  }

  
}
