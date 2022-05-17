<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return ResponseFormatter::error([
                'message' => 'You are not authorized to access this resource'
            ], 'Unauthorized', 401);
        }

        return parent::render($request, $exception);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
