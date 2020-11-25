<?php

declare(strict_types=1);

namespace CovidDataFetcher\Parser;

use CovidDataFetcher\Exception\SiteParserException;
use CovidDataFetcher\ValueObject\NumericValue;
use CovidDataFetcher\ValueObject\SiteContent;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom;

class CovidParser20200331 extends AbstractSiteParser
{
    public function canParseContentStartDate(): ?DateTimeImmutable
    {
        return new DateTimeImmutable('2020-03-31');
    }

    public function canParseContentEndDate(): ?DateTimeImmutable
    {
        return new DateTimeImmutable('2020-05-03');
    }

    public function parse(string $content, string $sourceUrl = null): SiteContent
    {
        $dom = new Dom();
        $client = new Client();
        $domDied = new Dom();

        try {
            $dom->loadStr($content);
            $died = (string) $client->request('GET', $sourceUrl.'elhunytak')->getBody();
            $domDied = $domDied->loadStr($died);

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
            $lockDown = new NumericValue($arrayIterator->current()->innerHtml());
            $arrayIterator->next();
            $samples = new NumericValue($arrayIterator->current()->innerHtml());

            $tr = $domDied->find('tr.views-row-last')->toArray()[0];
            $numberOfDeathCases = new NumericValue($tr->find('td.views-field-field-elhunytak-sorszam')->toArray()[0]->innerHtml());

            return new SiteContent(
                $infected->getValue(),
                $healed->getValue(),
                $lockDown->getValue(),
                $samples->getValue(),
                $numberOfDeathCases->getValue()
            );
        } catch (GuzzleException $exception) {
            throw $exception;
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
