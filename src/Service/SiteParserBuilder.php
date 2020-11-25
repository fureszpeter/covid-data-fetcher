<?php

declare(strict_types=1);

namespace CovidDataFetcher\Service;

use CovidDataFetcher\Exception\ParserNotFoundException;
use CovidDataFetcher\Parser\CovidParser20200309;
use CovidDataFetcher\Parser\CovidParser20200331;
use CovidDataFetcher\Parser\CovidParser20200504;
use CovidDataFetcher\Parser\SiteParser;
use DateTime;

class SiteParserBuilder
{
    /**
     * @throws ParserNotFoundException
     */
    public function build(DateTime $date): SiteParser
    {
        $parsers = [
            new CovidParser20200309(),
            new CovidParser20200331(),
            new CovidParser20200504(),
        ];

        /** @var SiteParser $parser */
        foreach ($parsers as $parser) {
            if ($parser->canHandle($date)) {
                return $parser;
            }
        }

        throw new ParserNotFoundException(
            sprintf('Parser not found for date. [date: %s]', $date->format(DATE_ATOM))
        );
    }
}
