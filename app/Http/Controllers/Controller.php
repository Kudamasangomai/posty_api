<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

  
/**
* @OA\Info( title="Post Api Docs ", version="1.0.0",
* @OA\Contact( email="Kudam775@gmail.com"), )
* @OA\SecurityScheme(type="http",securityScheme="bearerAuth",scheme="bearer", bearerFormat="JWT",
* )
*/

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
