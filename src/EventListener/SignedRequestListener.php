<?php
declare(strict_types=1);
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SignedRequestListener
{
    public function __construct(private string $secret) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // lepas auth dlu untuk development
        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        $timestamp = $request->headers->get('X-TIMESTAMP');
        $signature = $request->headers->get('X-SIGNATURE');

        if (!$timestamp || !$signature) {
            throw new AccessDeniedHttpException('Missing signature');
        }

        // Cek waktu maksimal 5 menit
        if (abs(time() - (int)$timestamp) > 300) {
            throw new AccessDeniedHttpException('Expired request');
        }

        $expectedSignature = hash_hmac('sha256', $timestamp, $this->secret);

        if (!hash_equals($expectedSignature, $signature)) {
            throw new AccessDeniedHttpException('Invalid signature');
        }
    }
}