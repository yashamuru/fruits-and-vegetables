<?php

namespace App\Tests\App\Domain;

use App\Domain\Produce;
use App\Domain\Quantity;
use PHPUnit\Framework\TestCase;

class ProduceTest extends TestCase
{
    public function testNewObject(): void {
        $q = new Quantity(1234,  Quantity::UNIT_GRAMS);
        $produce = new Produce(1, 'Apple', Produce::TYPE_FRUIT, $q);

        $this->assertEquals(1, $produce->getId());
    }

    /**
     * @dataProvider getInvalidTypes
     */
    public function testItValidatesTheType(string $invalidType): void {
        $this->expectException(\InvalidArgumentException::class);
        $q = new Quantity(1234,  Quantity::UNIT_GRAMS);
        new Produce(1, 'Apple', $invalidType, $q);
    }

    public function getInvalidTypes(): array {
        return [
            [''],
            ['Invalid'],
            [strtoupper(Produce::TYPE_FRUIT)],
        ];
    }
}
