# Covid statistics data fetcher from official hungarian site

## Example usage in a Laravel Command

## Install 

```bash
composer require furesz/covid-data-checker
```

Get the data for `2020-03-10`

```php
<?php

use CovidDataFetcher\Service\WaybackService;

require_once __DIR__ . '/vendor/autoload.php';

$service = new WaybackService(
    new \GuzzleHttp\Client(),
    new \CovidDataFetcher\Service\SiteParserBuilder()
);

$result = $service->extractSiteDataForDate(new DateTimeImmutable('2020-03-10'));

echo json_encode($result, JSON_PRETTY_PRINT);
```

Result looks like: 

```json
{
    "url": "http:\/\/web.archive.org\/web\/20200310114715\/https:\/\/koronavirus.gov.hu\/",
    "date": "2020-03-10T11:47:15+00:00",
    "data": {
        "infected": 12,
        "healed": 0,
        "lockDown": 67,
        "samples": 362,
        "died": 0
    }
}
```
