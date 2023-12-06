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
    public function testAdd(Produce $expected, Produce $newItem, string $prefix): void {
        $produceCollection = $this->getCollection();

        $produceCollection->add($newItem);
        $this->assertProduceItem($produceCollection, $expected, $prefix);
    }

    public static function getAddData(): array {
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

    public function testRemove(): void {
        $produceCollection = $this->getCollection();

        $produceCollection->remove(2);
        $this->assertEmpty($produceCollection->getById(2), "Removed item is not in collection");
        $this->assertCount(2, $produceCollection->list(), "Collection has now 2 items");
    }

    public function testRemoveDoesNothingForNonExistingItem(): void {
        $produceCollection = $this->getCollection();
        $produceCollection->remove(4);
        $this->assertCount(3, $produceCollection->list(), "Collection has still 3 items");
    }

    public function testSubtract(): void {
        $produceCollection = $this->getCollection();

        $produceCollection->subtract(3, new Produce(3, 'pineapple', Produce::TYPE_FRUIT, new Quantity(700, Quantity::UNIT_GRAMS)));
        $this->assertProduceItem($produceCollection, new Produce(3, 'pineapple', Produce::TYPE_FRUIT, new Quantity(8, Quantity::UNIT_KILOGRAMS)), "Subtract from existing item");
    }

    public function testSubtractValidatesForExistingItem(): void {
        $this->expectException(\InvalidArgumentException::class);
        $produceCollection = $this->getCollection();
        $produceCollection->subtract(4, new Produce(4, 'Cucumber', Produce::TYPE_VEGETABLE, new Quantity(3, Quantity::UNIT_KILOGRAMS)));
    }

    public function testList(): void {
        $produceCollection = $this->getCollection();
        $this->assertCount(3, $produceCollection->list(), "Collection has 3 items");
    }

    /**
     * @dataProvider getSearchData
     */
    public function testSearch(int $expected, ?string $term, string $message): void {
        $produceCollection = $this->getCollection();
        $this->assertCount($expected, $produceCollection->search($term), $message);
    }

    public static function getSearchData(): array {
        return [
            [2, 'Apple', "2 items matching 'Apple'"],
            [1, 'PEAR', "1 items matching 'pear'"],
            [0, 'banana', "No items matching 'banana'"],
            [3, null, "All items"],
        ];
    }

    private function getCollection(): ProduceCollection {
        $produceCollection = new ProduceCollection();

        $apple = new Produce(1, 'apple', Produce::TYPE_FRUIT, new Quantity(1, Quantity::UNIT_KILOGRAMS));
        $pear = new Produce(2, 'pear', Produce::TYPE_FRUIT, new Quantity(2, Quantity::UNIT_KILOGRAMS));
        $pineapple = new Produce(3, 'pineapple', Produce::TYPE_FRUIT, new Quantity(8700, Quantity::UNIT_GRAMS));

        return $produceCollection
            ->add($apple)
            ->add($pear)
            ->add($pineapple);
    }

    private function assertProduceItem(ProduceCollection $collection, Produce $expected, string $prefix) {
        $id = $expected->getId();
        $produce = $collection->getById($id);

        $this->assertNotEmpty($produce, $prefix."exists in collection");
        $this->assertEquals($expected->getId(),   $produce->getId(),   $prefix.'matches on ID');
        $this->assertEquals($expected->getName(), $produce->getName(), $prefix.'matches on Name');
        $this->assertEquals($expected->getType(), $produce->getType(), $prefix.'matches on Type');
        $this->assertTrue($expected->getQuantity()->equals($produce->getQuantity()), $prefix.'matches on Quantity');
    }
}
