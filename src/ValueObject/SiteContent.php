<?php

namespace CovidDataFetcher\ValueObject;

use JsonSerializable;

class SiteContent implements JsonSerializable
{
    private int $infected;
    private int $healed;
    private int $lockDown;
    private int $samples;
    private int $died;

    public function __construct(
        int $infected,
        int $healed,
        int $lockDown,
        int $samples,
        int $died
    ){
        $this->infected = $infected;
        $this->healed = $healed;
        $this->lockDown = $lockDown;
        $this->samples = $samples;
        $this->died = $died;
    }

    public function jsonSerialize(): array
    {
        return [
            'infected' => $this->infected,
            'healed' => $this->healed,
            'lockDown' => $this->lockDown,
            'samples' => $this->samples,
            'died' => $this->died,
        ];
    }
}
