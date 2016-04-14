<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

/**
 * @SWG\Swagger(
 *     schemes={"http", "https"},
 *     host="myapi.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="My first laravel api based on the cinema",
 *         description="Thanks to this api you can find information about your favorite films... In ordrer to use it, you will need to add api_key parameter with its token.",
 *         termsOfService="http://www.esgi.fr/",
 *         @SWG\Contact(
 *             email="equipe8@esgi.fr"
 *         ),
 *         @SWG\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     consumes={"application/json"},
 *     produces={"application/json"},
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected function isValidToken(){
        
    }
}
