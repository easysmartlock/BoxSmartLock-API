<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Easy as EasyModel;

class Easy
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
            $e = EasyModel::find($id);
            if($e && $e->user_id = $user->id) {
                return $next($request);
            }
        }

        return response('Forbidden Lock!', 403);
    }
}
