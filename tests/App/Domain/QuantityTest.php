<?php

namespace App\Tests\App\Domain;

use App\Domain\Quantity;
use PHPUnit\Framework\TestCase;

class QuantityTest extends TestCase
{
    public function testEquals(): void
    {
        $q1 = new Quantity(1234,  Quantity::UNIT_GRAMS);
        $q2 = new Quantity(1.234, Quantity::UNIT_KILOGRAMS);
        $this->assertTrue($q1->equals($q2));
    }

    /**
     * @dataProvider getSubtractData
     */
    public function testSubtract(int $expected, Quantity $q1, Quantity $q2): void
    {
        $this->assertEquals($expected, $q1->subtract($q2)->getValue(), 'Subtract '.$expected);
    }

    public static function getSubtractData(): array
    {
        return [
            [3500, new Quantity(5, Quantity::UNIT_KILOGRAMS), new Quantity(1.5, Quantity::UNIT_KILOGRAMS)],
            [12, new Quantity(15, Quantity::UNIT_GRAMS), new Quantity(3, Quantity::UNIT_GRAMS)],
            [150, new Quantity(2, Quantity::UNIT_KILOGRAMS), new Quantity(1850, Quantity::UNIT_GRAMS)],
        ];
    }

    /**
     * @dataProvider getAddData
     */
    public function testAdd(int $expected, Quantity $q1, Quantity $q2): void
    {
        $this->assertEquals($expected, $q1->add($q2)->getValue(), 'Add '.$expected);
    }

    public static function getAddData(): array
    {
        return [
            [6500, new Quantity(5, Quantity::UNIT_KILOGRAMS), new Quantity(1.5, Quantity::UNIT_KILOGRAMS)],
            [18, new Quantity(15, Quantity::UNIT_GRAMS), new Quantity(3, Quantity::UNIT_GRAMS)],
            [3850, new Quantity(2, Quantity::UNIT_KILOGRAMS), new Quantity(1850, Quantity::UNIT_GRAMS)],
        ];
    }

    /**
     * @dataProvider getInvalidUnits
     */
    public function testValidatesTheUnit($invalidInput): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Quantity(1, $invalidInput);
    }

    public function getInvalidUnits(): array
    {
        return [
            ['foo'],
            ['Invalid']
        ];
    }
}
