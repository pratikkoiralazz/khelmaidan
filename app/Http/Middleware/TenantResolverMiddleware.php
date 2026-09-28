<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Venue;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantResolverMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $baseDomain = config('app.base_domain', 'khelmaidan.com');

        $venue = null;

        if (str_ends_with($host, '.' . $baseDomain)) {
            $slug = str_replace('.' . $baseDomain, '', $host);
            $venue = Venue::where('slug', $slug)->where('is_active', true)->first();
        } else {
            $venue = Venue::where('custom_domain', $host)->where('is_active', true)->first();
        }

        if (!$venue) {
            return response()->json(['error' => 'Venue domain not found or inactive.'], Response::HTTP_NOT_FOUND);
        }

        app()->instance(Venue::class, $venue);
        $request->attributes->set('tenant', $venue);

        return $next($request);
    }
}