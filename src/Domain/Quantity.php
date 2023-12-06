<?php

namespace App\Domain;

use Webmozart\Assert\Assert;

class Quantity
{
    public const UNIT_GRAMS = 'g';
    public const UNIT_KILOGRAMS = 'kg';

    public const VALID_UNITS = [
        self::UNIT_GRAMS,
        self::UNIT_KILOGRAMS,
    ];

    private const MULTIPLIERS = [
        self::UNIT_GRAMS => 1,
        self::UNIT_KILOGRAMS => 1000,
    ];

    private int $value;

    public function __construct(float $value, string $unit) {
        $this->validateUnit($unit);
        $value *= self::MULTIPLIERS[$unit];
        Assert::integerish($value);
        $this->value = (int)$value;
    }

    public function getValue(string $convertTo = self::UNIT_GRAMS): float {
        $this->validateUnit($convertTo);
        return $this->value / self::MULTIPLIERS[$convertTo];
    }

    public function equals(self $quantity): bool {
        return $this->value == $quantity->getValue(self::UNIT_GRAMS);
    }

    public function add(self $quantity): self {
        $this->value = $this->getValue(self::UNIT_GRAMS) + $quantity->getValue(self::UNIT_GRAMS);
        return $this;
    }

    public function subtract(self $quantity): self {
        $diff = $this->getValue(self::UNIT_GRAMS) - $quantity->getValue(self::UNIT_GRAMS);
        Assert::greaterThanEq($diff, 0, 'Not enough quantity in stock');
        return new self($diff, self::UNIT_GRAMS);
    }

    private function validateUnit(string $unit): void {
        Assert::oneOf($unit, self::VALID_UNITS, 'Invalid unit: '.$unit);
    }
}
