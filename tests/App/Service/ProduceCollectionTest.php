<?php

namespace App\Tests\App\Service;

use App\Service\ProduceCollection;
use App\Domain\Produce;
use App\Domain\Quantity;
use PHPUnit\Framework\TestCase;

class ProduceCollectionTest extends TestCase
{
    /**
     * @dataProvider getAddData
     */
    public function testAdd(Produce $expected, Produce $newItem, string $prefix): void
    {
        $produceCollection = $this->getCollection();

        $produceCollection->add($newItem);
        $this->assertProduceItem($produceCollection, $expected, $prefix);
    }

    public static function getAddData(): array
    {
        $cucumber = new Produce(4, 'Cucumber', Produce::TYPE_VEGETABLE, new Quantity(3, Quantity::UNIT_KILOGRAMS));

        return [
            [
                new Produce(1, 'apple', Produce::TYPE_FRUIT, new Quantity(2.6, Quantity::UNIT_KILOGRAMS)),
                new Produce(1, 'apple', Produce::TYPE_FRUIT, new Quantity(1.6, Quantity::UNIT_KILOGRAMS)),
                "Add to existing item"
            ],
            [
                clone($cucumber),
                $cucumber,
                "Add new item" //We don't restrict the type of produce in the collection here
            ],
        ];
    }

    public function testRemove(): void
    {
        $produceCollection = $this->getCollection();

        $produceCollection->remove(2);
        $this->assertEmpty($produceCollection->list()[2] ?? null, "Removed item is not in collection");
        $this->assertCount(2, $produceCollection->list(), "Collection has now 2 items");
    }

    public function testRemoveValidatesForExistingItem(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $produceCollection = $this->getCollection();
        $produceCollection->remove(4);
    }

    public function getCollection(): ProduceCollection
    {
        $produceCollection = new ProduceCollection();

        $apple = new Produce(1, 'apple', Produce::TYPE_FRUIT, new Quantity(1, Quantity::UNIT_KILOGRAMS));
        $pear = new Produce(2, 'pear', Produce::TYPE_FRUIT, new Quantity(2, Quantity::UNIT_KILOGRAMS));
        $pineapple = new Produce(3, 'pineapple', Produce::TYPE_FRUIT, new Quantity(8700, Quantity::UNIT_GRAMS));

        return $produceCollection
            ->add($apple)
            ->add($pear)
            ->add($pineapple);
    }

    private function assertProduceItem(ProduceCollection $collection, Produce $expected, string $prefix)
    {
        $id = $expected->getId();

        $list = $collection->list();
        $this->assertNotEmpty($list[$expected->getId()], $prefix."exists in collection");
        $produce = $list[$expected->getId()];

        $this->assertEquals($expected->getId(),   $produce->getId(),   $prefix.'matches on ID');
        $this->assertEquals($expected->getName(), $produce->getName(), $prefix.'matches on Name');
        $this->assertEquals($expected->getType(), $produce->getType(), $prefix.'matches on Type');
        $this->assertTrue($expected->getQuantity()->equals($produce->getQuantity()), $prefix.'matches on Quantity');
    }
}
