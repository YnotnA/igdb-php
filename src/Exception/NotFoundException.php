<?php

namespace YnotnA\Igdb\Exception;

use Unirest\Response;

/**
 * Exception thrown when a 403 response is received, indicating an invalid API key.
 */
final class NotFoundException extends \Exception
{
    public function __construct(Response $response, \Exception $previous = null)
    {
        parent::__construct(
            'Url not found',
            $response->code,
            $previous
        );
    }
}
