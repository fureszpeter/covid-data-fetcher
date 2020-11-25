<?php

namespace CovidDataFetcher\ValueObject;

use JsonSerializable;
use UnexpectedValueException;

class NumericValue implements JsonSerializable
{
    private int $value;

    /**
     * @param mixed|null $value
     *
     * @throws UnexpectedValueException If value is not numeric
     */
    public function __construct($value)
    {
        if ($value === null) {
            throw new UnexpectedValueException();
        }

        $value = str_replace(' ', '', $value);

        if (!is_numeric($value)) {
            throw new UnexpectedValueException();
        }

        $this->value = (int)$value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
