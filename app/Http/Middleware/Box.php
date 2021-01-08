<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Box as BoxModel;

class Box
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user() && $request->has('id')) {
            $id = $request->input('id');
            $user = $request->user();
            $box = BoxModel::find($id);
            if($box && $box->user_id = $user->id) {
                return $next($request);
            }
        }

        return response('Forbidden Box!', 403);
    }
}
