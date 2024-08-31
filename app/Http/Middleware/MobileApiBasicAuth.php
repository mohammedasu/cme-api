<?php

namespace App\Http\Middleware;

use Closure;
use App\Constants\Constants;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class MobileApiBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $AUTH_USER = config('app.MOBILE_API_USERNAME');
        $AUTH_PASS = config('app.MOBILE_API_PASSWORD');

        header('Cache-Control: no-cache, must-re validate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            $response = ['username'=>$_SERVER['PHP_AUTH_USER'],'password'=>$_SERVER['PHP_AUTH_PW']];
            return ApiResponse::failureResponse('Unauthorized Request.',$response,Constants::UNAUTHORIZED_RESPONSE_CODE);
        }
        return $next($request);
    }
}
