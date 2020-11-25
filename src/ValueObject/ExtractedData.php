<?php

declare(strict_types=1);

namespace CovidDataFetcher\ValueObject;

use DateTime;
use JsonSerializable;

class ExtractedData implements JsonSerializable
{
    private string $url;
    private DateTime $date;
    private SiteContent $data;
    private string $response;

    public function __construct(
        string $url,
        DateTime $date,
        SiteContent $data,
        string $response
    ) {
        $this->url = $url;
        $this->date = $date;
        $this->data = $data;
        $this->response = $response;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getData(): SiteContent
    {
        return $this->data;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
            'date' => $this->date->format(DATE_ATOM),
            'data' => $this->data,
        ];
    }
}
