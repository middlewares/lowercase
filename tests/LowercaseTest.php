<?php

namespace Middlewares\Tests;

use Middlewares\Lowercase;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class LowercaseTest extends TestCase
{
    public function testLowercase()
    {
        $request = Factory::createServerRequest('GET', '/');

        $response = Dispatcher::run([
            new Lowercase(),
        ], $request);

        $this->assertSame(200, $response->getStatusCode());
    }
}
