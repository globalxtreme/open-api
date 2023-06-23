<?php

namespace GlobalXtreme\OpenAPI\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class OpenAPIAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Client-Secret') || !$request->hasHeader('Client-ID') || !$request->hasHeader('Client-Name')) {
            errUnauthenticated("Your client id or name or secret does not exists!");
        }

        $clientName = $request->header('Client-Name');

        $credentials = config('open-api.credentials');
        if (!isset($credentials[$clientName])) {
            errUnauthenticated("Your client name invalid!");
        }

        $token = $this->decrypt($request);
        if (!$token) {
            errUnauthenticated("Your client id or or name or secret invalid!");
        }

        if (!Hash::check($credentials[$clientName]['key'], $token)) {
            errUnauthenticated("Your client secret invalid!");
        }

        return $next($request);
    }


    /** --- SUB FUNCTIONS --- */

    private function decrypt($request)
    {
        $clientId = $request->header('Client-ID');
        $clientName = $request->header('Client-Name');

        $clientSecret = $request->header('Client-Secret');
        $clientSecret = str_replace('_open:', '', $clientSecret);

        $token = base64_decode($clientSecret);

        $cipher = 'AES-128-CBC';

        $ivLen = openssl_cipher_iv_length($cipher);
        $iv = substr($token, 0, $ivLen);

        $token = str_replace($iv, '', $token);

        return openssl_decrypt($token, $cipher, config("open-api.credentials.$clientName.key") . $clientId, 0, $iv);
    }
}
