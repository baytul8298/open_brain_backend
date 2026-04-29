<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records every authenticated request to the audit log.
 * Skips static assets and health-check endpoints.
 */
class AuditLogMiddleware
{
    private const SKIP_PATHS = [
        'up',
        '_debugbar',
        'favicon.ico',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated users
        if (! $request->user()) {
            return $response;
        }

        $path = $request->path();

        foreach (self::SKIP_PATHS as $skip) {
            if (str_starts_with($path, $skip)) {
                return $response;
            }
        }

        // Only log mutating actions or explicit read audit paths
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            AuditLog::create([
                'user_id'    => $request->user()->id,
                'action'     => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload'    => $this->sanitizePayload($request->except(['password', 'password_confirmation', 'current_password', '_token'])),
                'status_code' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }

    private function sanitizePayload(array $data): ?string
    {
        try {
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return null;
        }
    }
}
