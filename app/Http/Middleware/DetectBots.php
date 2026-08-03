<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectBots
{
    /**
     * Known bot user agents
     */
    protected $botPatterns = [
        // Search engine bots (allowed)
        'googlebot',
        'bingbot',
        'slurp',
        'duckduckbot',
        'baiduspider',
        'yandexbot',
        'facebot',
        'facebookexternalhit',

        // Malicious/scraper bots (blocked)
        'scrapy',
        'curl',
        'wget',
        'python-requests',
        'phantomjs',
        'headless',
        'selenium',
        'webdriver',
        'bot.htm',
        'bot.php',
        'crawler',
        'spider',
        'scraper',
    ];

    /**
     * Allowed search engine bots
     */
    protected $allowedBots = [
        'googlebot',
        'bingbot',
        'slurp',
        'duckduckbot',
        'baiduspider',
        'yandexbot',
        'facebot',
        'facebookexternalhit',
    ];

    /**
     * Suspicious patterns in requests
     */
    protected $suspiciousPatterns = [
        'eval(',
        'base64_decode',
        'exec(',
        'system(',
        'shell_exec',
        '../',
        '..\\',
        '<script',
        'javascript:',
        'onerror=',
        'onload=',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower($request->userAgent() ?: '');
        $ip = $request->ip() ?: '127.0.0.1';

        // Check for missing user agent (suspicious)
        if (empty($userAgent)) {
            Log::warning('Bot detected: Empty user agent', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
            ]);

            // Block if trying to post/modify data without user agent
            if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
                return response()->json([
                    'error' => 'Invalid request'
                ], 403);
            }
        }

        // Detect bot
        $isBot = false;
        $botType = null;
        foreach ($this->botPatterns as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                $isBot = true;
                $botType = $pattern;
                break;
            }
        }

        if ($isBot) {
            // Check if it's an allowed search engine bot
            $isAllowed = false;
            foreach ($this->allowedBots as $allowed) {
                if (str_contains($userAgent, $allowed)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                Log::warning('Malicious bot detected', [
                    'bot_type' => $botType,
                    'user_agent' => $userAgent,
                    'ip' => $ip,
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);

                // Block malicious bots from POST/PUT/DELETE/PATCH
                if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
                    return response()->json([
                        'error' => 'Access denied'
                    ], 403);
                }

                // Rate limit GET requests from bots
                $request->attributes->set('is_bot', true);
            } else {
                // Allow search engine bots with logging
                Log::info('Search engine bot access', [
                    'bot_type' => $botType,
                    'ip' => $ip,
                    'url' => $request->fullUrl(),
                ]);
            }
        }

        // Check for suspicious patterns in request
        $requestData = array_merge(
            $request->all(),
            [$request->fullUrl()]
        );

        foreach ($requestData as $key => $value) {
            if (is_string($value)) {
                foreach ($this->suspiciousPatterns as $pattern) {
                    if (str_contains(strtolower($value), $pattern)) {
                        Log::alert('Suspicious request pattern detected', [
                            'pattern' => $pattern,
                            'field' => $key,
                            'ip' => $ip,
                            'user_agent' => $userAgent,
                            'url' => $request->fullUrl(),
                        ]);

                        return response()->json([
                            'error' => 'Invalid request detected'
                        ], 400);
                    }
                }
            }
        }

        // Check for excessive request frequency (simple bot detection)
        $cacheKey = 'bot_check:' . $ip;
        $requests = cache()->get($cacheKey, 0);

        if ($requests > 100) { // More than 100 requests per minute
            Log::warning('Possible bot: High request frequency', [
                'ip' => $ip,
                'requests_per_minute' => $requests,
                'user_agent' => $userAgent,
            ]);

            return response()->json([
                'error' => 'Too many requests'
            ], 429);
        }

        cache()->put($cacheKey, $requests + 1, 60);

        return $next($request);
    }
}
