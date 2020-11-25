<?php

declare(strict_types=1);

namespace CovidDataFetcher\Parser;

use CovidDataFetcher\Exception\SiteParserException;
use CovidDataFetcher\ValueObject\NumericValue;
use CovidDataFetcher\ValueObject\SiteContentV2;
use DateTimeImmutable;
use Exception;
use PHPHtmlParser\Dom;

class CovidParser20200504 extends AbstractSiteParser
{
    public function canParseContentStartDate(): ?DateTimeImmutable
    {
        return new DateTimeImmutable('2020-05-04');
    }

    public function parse(string $content, string $sourceUrl = null): SiteContentV2
    {
        $dom = new Dom();

        try {
            $dom->loadStr($content);

            $infectedPest = new NumericValue($dom->find('div#api-fertozott-pest')->toArray()[0]->innerHtml());
            $infectedVillage = new NumericValue($dom->find('div#api-fertozott-videk')->toArray()[0]->innerHtml());
            $healedPest = new NumericValue($dom->find('div#api-gyogyult-pest')->toArray()[0]->innerHtml());
            $healedVillage = new NumericValue($dom->find('div#api-gyogyult-videk')->toArray()[0]->innerHtml());
            $diedPest = new NumericValue($dom->find('div#api-elhunyt-pest')->toArray()[0]->innerHtml());
            $diedVillage = new NumericValue($dom->find('div#api-elhunyt-videk')->toArray()[0]->innerHtml());
            $lockDown = new NumericValue($dom->find('#api-karantenban')->toArray()[0]->innerHtml());
            $sampling = new NumericValue($dom->find('#api-mintavetel')->toArray()[0]->innerHtml());

            return new SiteContentV2(
                $infectedPest->getValue(),
                $infectedVillage->getValue(),
                $healedPest->getValue(),
                $healedVillage->getValue(),
                $lockDown->getValue(),
                $sampling->getValue(),
                $diedPest->getValue(),
                $diedVillage->getValue()
            );
        } catch (Exception $exception) {
            throw new SiteParserException(
                $content,
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
