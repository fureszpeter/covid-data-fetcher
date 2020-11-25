<?php

declare(strict_types=1);

namespace CovidDataFetcher\Exception;

use RuntimeException;
use Throwable;

class SiteParserException extends RuntimeException
{
    private $content;

    public function __construct(string $content, $message = '', $code = 0, Throwable $previous = null)
    {
        $this->content = $content;

        parent::__construct($message, $code, $previous);
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
