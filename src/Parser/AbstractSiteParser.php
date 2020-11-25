<?php

declare(strict_types=1);

namespace CovidDataFetcher\Parser;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

abstract class AbstractSiteParser implements SiteParser
{
    public function canParseContentStartDate(): ?DateTimeImmutable
    {
        return null;
    }

    public function canParseContentEndDate(): ?DateTimeImmutable
    {
        return null;
    }

    public function canHandle(DateTimeInterface $date): bool
    {
        $subject = new DateTime($date->format('Y-m-d'));
        if (
            (null === $this->canParseContentStartDate() || $this->canParseContentStartDate() <= $subject)
            && (null === $this->canParseContentEndDate() || $this->canParseContentEndDate() >= $subject)
        ) {
            return true;
        }

        return false;
    }
}
