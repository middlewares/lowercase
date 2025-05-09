<?php

declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Lowercase;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class LowercaseTest extends TestCase
{
    /**
     * @return array<string[]>
     */
    public function lowercaseProvider(): array
    {
        return [
            ['/FoO/BaR', '/foo/bar'],
            ['/fOo/bAr', '/foo/bar'],
            ['/foo/bar', '/foo/bar'],
            ['/', '/'],
            ['', ''],
        ];
    }

    /**
     * @dataProvider lowercaseProvider
     */
    public function testLowercase(string $uri, string $result): void
    {
        $request = Factory::createServerRequest('GET', $uri);

        $response = Dispatcher::run([
            new Lowercase(),

            function ($request, $next) {
                echo $request->getUri();
            },
        ], $request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertEquals($result, (string) $response->getBody());
    }

    /**
     * @return array<string[]>
     */
    public function redirectProvider(): array
    {
        return [
            ['/FoO/BaR', '/foo/bar', '301'],
            ['/fOo/bAr', '/foo/bar', '301'],
            ['/foo/bar', '', '200'],
            ['/', '', '200'],
            ['', '', '200'],
        ];
    }

    /**
     * @dataProvider redirectProvider
     */
    public function testRedirect(string $uri, string $result, string $statusCode): void
    {
        $request = Factory::createServerRequest('GET', $uri);

        $response = Dispatcher::run([
            (new Lowercase())->redirect(),
        ], $request);

        $this->assertEquals($result, $response->getHeaderLine('location'));
        $this->assertEquals($statusCode, (string) $response->getStatusCode());
    }

    /**
     * @dataProvider redirectProvider
     */
    public function testCustomFactoryRedirect(string $uri, string $result, string $statusCode): void
    {
        $request = Factory::createServerRequest('GET', $uri);

        $response = Dispatcher::run([
            (new Lowercase())->redirect(Factory::getResponseFactory()),
        ], $request);

        $this->assertEquals($result, $response->getHeaderLine('location'));
        $this->assertEquals($statusCode, (string) $response->getStatusCode());
    }

    /**
     * @return array<string[]>
     */
    public function attributeProvider(): array
    {
        return [
            ['/FoO/BaR', '/FoO/BaR'],
            ['/fOo/bAr', '/fOo/bAr'],
            ['/foo/bar', ''],
        ];
    }

    /**
     * @dataProvider attributeProvider
     */
    public function testAttribute(string $uri, string $result): void
    {
        $request = Factory::createServerRequest('GET', $uri);

        $response = Dispatcher::run([
            (new Lowercase())->attribute('custom-attribute-name'),

            function ($request) {
                echo $request->getAttribute('custom-attribute-name');
            },
        ], $request);

        $this->assertEquals($result, (string) $response->getBody());
    }
}
