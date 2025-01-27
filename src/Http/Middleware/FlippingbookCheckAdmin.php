<?php

namespace Flippingbook\Http\Middleware;

use Closure;
use Flippingbook\Helpers\FlippingbookHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FlippingbookCheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if(FlippingbookHelper::isFlippingbookAdmin()) {
            return $next($request);
        }

        return redirect('/')
            ->withErrors(['email' => __('flippingbook::flippingbook.FLIPPINGBOOK_ERRORS_AUTHENTICATION_ERROR')]);
    }
}
