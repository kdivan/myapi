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
 *         title="Ma premiere api laravel",
 *         description="This is a sample server Petstore server.  You can find out more about Swagger at <a href=""http://swagger.io"">http://swagger.io</a> or on irc.freenode.net, #swagger.  For this sample, you can use the api key ""special-key"" to test the authorization filters",
 *         termsOfService="http://helloreverb.com/terms/",
 *         @SWG\Contact(
 *             email="airskual@yahoo.com"
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
