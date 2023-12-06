<?php

namespace App\Tests\App\Service;

use App\Service\ProduceFormatter;
use App\Domain\Produce;
use App\Domain\Quantity;
use PHPUnit\Framework\TestCase;

class ProduceFormatterTest extends TestCase
{
    /**
     * @dataProvider getTestFormat
     */
    public function testFormat(array $expected, string $unit, Produce $produce, string $message): void {
        $formatter = new ProduceFormatter($unit);
        $this->assertEquals($expected, $formatter->format($produce), $message);
    }

    public static function getTestFormat(): array {
        $produce = new Produce(1, 'Apple', Produce::TYPE_FRUIT, new Quantity(1234, Quantity::UNIT_GRAMS));

        return [
            [
                [
                    'id'   => 1,
                    'name' => 'Apple',
                    'type' => Produce::TYPE_FRUIT,
                    'quantity' => 1.234,
                    'unit' => Quantity::UNIT_KILOGRAMS,
                ],
                Quantity::UNIT_KILOGRAMS,
                $produce,
                "Format in kilograms"
            ],
            [
                [
                    'id'   => 1,
                    'name' => 'Apple',
                    'type' => Produce::TYPE_FRUIT,
                    'quantity' => 1234,
                    'unit' => Quantity::UNIT_GRAMS,
                ],
                Quantity::UNIT_GRAMS,
                $produce,
                "Format in grams"
            ],
        ];
    }

    public function testValidatesUnit(): void {
        $this->expectException(\InvalidArgumentException::class);
        $formatter = new ProduceFormatter('Invalid');
    }
}
