<?php

namespace App\Service;

use Webmozart\Assert\Assert;
use App\Domain\Produce;
use App\Domain\Quantity;

class ProduceFormatter
{
    public function __construct(
        private string $unit
    ) {
        Assert::oneOf($unit, Quantity::VALID_UNITS, "Invalid unit: $unit");
    }

    public function format(Produce $produce): array {
        return [
            'id'   => $produce->getId(),
            'name' => $produce->getName(),
            'type' => $produce->getType(),
            'quantity' => $produce->getQuantity()->getValue($this->unit),
            'unit' => $this->unit,
        ];
    }
}
