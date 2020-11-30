<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;

class RestrictsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $mode)
    {
        if (!$request->user()) {
            return response('', 403);
        }
        $user = $request->user();
        switch ($mode) {
            case 'unapproved':
                if (!$user->approved) {
                    return response([
                        'errors' => [
                            'approval' => ['Account not yet approved. Please try again later.'],
                        ],
                    ], 403);
                }
                break;
            case 'unconfirmed':
                if (!$user->confirmation->approved) {
                    return response([
                        'errors' => [
                            'confirmation' => ['Phone number not verified.'],
                        ],
                    ], 403);
                }
                break;
            default:
                throw new InvalidArgumentException('Unkown Mode');
                break;
        }
        return $next($request);
    }
}
