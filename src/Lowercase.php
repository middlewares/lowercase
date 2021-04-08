<?php
declare(strict_types = 1);

namespace Middlewares;

use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Lowercase implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var string The attribute name
     */
    private $attribute = null;

    /**
     * Set the attribute name to store client's IP address.
     */
    public function attribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Whether returns a 301 response to the new path.
     */
    public function redirect(ResponseFactoryInterface $responseFactory = null): self
    {
        $this->responseFactory = $responseFactory ?: Factory::getResponseFactory();

        return $this;
    }

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $this->lowercase($uri->getPath());

        if ($uri->getPath() !== $path) {
            if ($this->responseFactory) {
                return $this->responseFactory->createResponse(301)
                    ->withHeader('Location', (string) $uri->withPath($path));
            }

            if ($this->attribute !== null) {
                $request = $request->withAttribute($this->attribute, $uri->getPath());
            }

            $request = $request->withUri($uri->withPath($path));
        }

        return $handler->handle($request);
    }

    /**
     * Make the Path Lowercase.
     */
    private function lowercase(string $path): string
    {
        if ($path === '') {
            return '';
        }

        return mb_strtolower($path);
    }
}
