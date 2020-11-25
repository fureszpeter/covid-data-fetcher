<?php

namespace CovidDataFetcher\ValueObject;

class SiteContentV2 extends SiteContent
{
    private int $infectedPest;
    private int $diedVillage;
    private int $infectedVillage;
    private int $healedPest;
    private int $healedVillage;
    private int $diedPest;

    public function __construct(
        int $infectedPest,
        int $infectedVillage,
        int $healedPest,
        int $healedVillage,
        int $lockDown,
        int $samples,
        int $diedPest,
        int $diedVillage
    ) {
        parent::__construct(
            $infectedPest + $infectedVillage,
            $healedPest + $healedVillage,
            $lockDown,
            $samples,
            $diedPest + $diedVillage
        );

        $this->infectedPest = $infectedPest;
        $this->infectedVillage = $infectedVillage;
        $this->healedPest = $healedPest;
        $this->healedVillage = $healedVillage;
        $this->diedPest = $diedPest;
        $this->diedVillage = $diedVillage;
    }

    public function jsonSerialize(): array
    {
        $parent = parent::jsonSerialize();

        return array_merge(
            $parent,
            [
                'infectedPest' => $this->infectedPest,
                'infectedVillage' => $this->infectedVillage,
                'healedPest' => $this->healedPest,
                'healedVillage' => $this->healedVillage,
                'diedPest' => $this->diedPest,
                'diedVillage' => $this->diedVillage,
            ]
        );
    }
}
