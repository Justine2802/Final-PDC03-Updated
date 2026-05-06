<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RenterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'renter') {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $status = $user->id_verification_status;

        // Profile not submitted yet — send to the form
        if ($status === 'none') {
            return redirect()->route('profile.complete');
        }

        // Profile submitted but not yet approved — hold at waiting page
        if ($status === 'pending') {
            return redirect()->route('profile.pending');
        }

        // Profile was rejected — send back to re-submit
        if ($status === 'rejected') {
            return redirect()->route('profile.complete');
        }

        // $status === 'verified' — allow through
        return $next($request);
    }
}
