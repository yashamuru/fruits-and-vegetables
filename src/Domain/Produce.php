<?php

namespace App\Domain;

use Webmozart\Assert\Assert;

class Produce
{
    public const TYPE_FRUIT = 'fruit';
    public const TYPE_VEGETABLE = 'vegetable';
    public const VALID_TYPES = [
        self::TYPE_FRUIT,
        self::TYPE_VEGETABLE,
    ];

    public function __construct(
        private int $id,
        private string $name,
        private string $type,
        private Quantity $quantity
    ) {
        Assert::oneOf($type, self::VALID_TYPES, "Invalid type: $type");
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return ucfirst($this->name);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function setQuantity(Quantity $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
