<?php

declare(strict_types=1);

namespace CovidDataFetcher\Service;

use CovidDataFetcher\Exception\GetMetadataException;
use CovidDataFetcher\ValueObject\ExtractedData;
use DateTime;
use DateTimeImmutable;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;

class WaybackService
{
    private const PROVIDER_URI = 'http://archive.org/';
    private const TARGET_SITE = 'koronavirus.gov.hu';

    /** @var ClientInterface */
    private $client;

    /** @var SiteParserBuilder */
    private $siteParserBuilder;

    public function __construct(
        ClientInterface $client,
        SiteParserBuilder $siteParserBuilder
    ) {
        $this->client = $client;
        $this->siteParserBuilder = $siteParserBuilder;
    }

    /**
     * @throws GuzzleException
     * @throws GetMetadataException
     */
    public function extractSiteDataForDate(DateTimeImmutable $date): ExtractedData
    {
        $decodedContent = $this->getMetaDataByDate($date);

        $closestUrl = $decodedContent['archived_snapshots']['closest']['url'] ?? null;
        $dateOfSnapshot = $decodedContent['archived_snapshots']['closest']['timestamp'] ?? null;

        if (null === $closestUrl || null === $dateOfSnapshot) {
            throw new GetMetadataException(
                sprintf(
                    'Not able to get metadata from archive.org. [site: %s, date: %s]',
                    self::TARGET_SITE,
                    $date->format(DATE_ATOM)
                )
            );
        }

        $siteContentResponse = $this->client->request('GET', $closestUrl);
        $siteContent = (string) $siteContentResponse->getBody();

        $siteParser = $this->siteParserBuilder->build(new DateTime($dateOfSnapshot));
        $result = $siteParser->parse($siteContent, $closestUrl);

        return new ExtractedData(
            $closestUrl,
            DateTime::createFromFormat('YmdHis', $dateOfSnapshot),
            $result,
            $siteContent
        );
    }

    /**
     * @throws GuzzleException
     */
    protected function getMetaDataByDate(DateTimeImmutable $date): array
    {
        $dateString = $date->format('Ymd120000');

        $response = $this->client->request(
            'GET',
            sprintf(
                self::PROVIDER_URI.'wayback/available?url=%s&timestamp=%s',
                self::TARGET_SITE,
                $dateString
            )
        );

        $body = (string) $response->getBody();

        return Utils::jsonDecode($body, true);
    }
}
