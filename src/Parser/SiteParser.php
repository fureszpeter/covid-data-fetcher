<?php

declare(strict_types=1);

namespace CovidDataFetcher\Parser;

use CovidDataFetcher\Exception\SiteParserException;
use CovidDataFetcher\ValueObject\SiteContent;
use DateTimeImmutable;
use DateTimeInterface;

interface SiteParser
{
    /**
     * @throws SiteParserException
     */
    public function parse(string $content, string $sourceUrl = null): SiteContent;

    public function canParseContentStartDate(): ?DateTimeImmutable;

    public function canParseContentEndDate(): ?DateTimeImmutable;

    public function canHandle(DateTimeInterface $date): bool;
}
