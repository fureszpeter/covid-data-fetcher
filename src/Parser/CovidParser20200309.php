<?php

declare(strict_types=1);

namespace CovidDataFetcher\Parser;

use CovidDataFetcher\Exception\SiteParserException;
use CovidDataFetcher\ValueObject\NumericValue;
use CovidDataFetcher\ValueObject\SiteContent;
use DateTimeImmutable;
use Exception;
use PHPHtmlParser\Dom;

class CovidParser20200309 extends AbstractSiteParser
{
    public function canParseContentEndDate(): ?DateTimeImmutable
    {
        return new DateTimeImmutable('2020-03-30');
    }

    public function parse(string $content, string $sourceUrl = null): SiteContent
    {
        $dom = new Dom();

        try {
            $dom->loadStr($content);

            $divCollection = $dom->find('div.diagram-a');
            if (0 === $divCollection->count()) {
                throw new SiteParserException(
                    $content,
                    sprintf('div.diagram-a not found in content, parser failed.]')
                );
            }

            /** @var Dom\Node\HtmlNode $divNode */
            $divNode = $divCollection->toArray()[0];
            $spans = $divNode->getParent()->getParent()->getParent()->getParent()->find('span.number');

            $arrayIterator = $spans->getIterator();
            $infected = new NumericValue($arrayIterator->current()->innerHtml());
            $arrayIterator->next();
            $healed = new NumericValue($arrayIterator->current()->innerHtml());
            $arrayIterator->next();
            $died = new NumericValue($arrayIterator->current()->innerHtml());
            $arrayIterator->next();
            $lockDown = new NumericValue($arrayIterator->current()->innerHtml());
            $arrayIterator->next();
            $samples = new NumericValue($arrayIterator->current()->innerHtml());

            return new SiteContent(
                $infected->getValue(),
                $healed->getValue(),
                $lockDown->getValue(),
                $samples->getValue(),
                $died->getValue()
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
