<?php

namespace YnotnA\Igdb\Exception;

use Unirest\Response;

/**
 * Exception thrown when an Request Error.
 */
final class BadRequestException extends \Exception
{
    public function __construct(Response $response, \Exception $previous = null)
    {
        if (isset($response->body->message)) {
            $message = $response->body->message;
        } else {
            $message = $response->body[0]->error[0];
        }
        parent::__construct(
            $message,
            $response->code,
            $previous
        );
    }
}
