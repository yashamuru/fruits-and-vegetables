<?php

namespace App\Domain;

use Webmozart\Assert\Assert;

class Produce
{
    public const TYPE_FRUIT = 'fruit';
    public const TYPE_VEGETABLE = 'vegetable';

    public function __construct(
        private int $id,
        private string $name,
        private string $type,
        Quantity $quantity
    ) {
        Assert::oneOf($type, [self::TYPE_FRUIT, self::TYPE_VEGETABLE], "Invalid type: $type");
    }

    public function getId(): int
    {
        return $this->id;
    }
}
