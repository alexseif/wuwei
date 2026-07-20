<?php

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\CorsSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsSubscriberTest extends TestCase
{
    private CorsSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->subscriber = new CorsSubscriber();
    }

    public function testGetSubscribedEvents(): void
    {
        $events = CorsSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::REQUEST, $events);
        $this->assertArrayHasKey(KernelEvents::RESPONSE, $events);
    }

    public function testOnKernelRequestHandlesPreflightOptions(): void
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/api/v1/temporal-blocks', 'OPTIONS');
        $request->headers->set('Origin', 'https://whatshouldidonow.alexseif.com');

        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);
        $this->subscriber->onKernelRequest($event);

        $response = $event->getResponse();
        $this->assertNotNull($response);
        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame('https://whatshouldidonow.alexseif.com', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertSame('GET, POST, PUT, PATCH, DELETE, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertStringContainsString('Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOnKernelRequestIgnoresNonApiRoutes(): void
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/admin', 'OPTIONS');
        $request->headers->set('Origin', 'https://whatshouldidonow.alexseif.com');

        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);
        $this->subscriber->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnKernelResponseAppendsCorsHeadersToApiResponse(): void
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/api/v1/temporal-blocks', 'GET');
        $request->headers->set('Origin', 'https://whatshouldidonow.alexseif.com');
        $response = new Response(json_encode(['data' => []]), 200, ['Content-Type' => 'application/json']);

        $event = new ResponseEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);
        $this->subscriber->onKernelResponse($event);

        $this->assertSame('https://whatshouldidonow.alexseif.com', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertSame('GET, POST, PUT, PATCH, DELETE, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
    }

    public function testOnKernelResponseSupportsLocalhostDevOrigins(): void
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/api/v1/temporal-blocks', 'GET');
        $request->headers->set('Origin', 'http://localhost:3000');
        $response = new Response('{}', 200);

        $event = new ResponseEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);
        $this->subscriber->onKernelResponse($event);

        $this->assertSame('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
    }
}
