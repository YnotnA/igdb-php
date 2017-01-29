<?php

namespace YnotnA\Igdb\Exception;

use Unirest\Response;

/**
 * Exception thrown when a 429 response is received, indicating you have made over 10000 requests on this API key today.
 *
 * @license MIT
 */
final class TooManyRequestsException extends \Exception
{
    public function __construct(Response $response, \Exception $previous = null)
    {
        parent::__construct(
            $response->body->message,
            $response->code,
            $previous
        );
    }
}
