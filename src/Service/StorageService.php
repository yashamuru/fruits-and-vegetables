<?php

namespace App\Service;

use App\Domain\Produce;
use App\Domain\Quantity;
use App\Service\ProduceCollection;

class StorageService
{
    protected string $request = '';
    protected array $collections = [];

    public function __construct(
        string $request
    ) {
        $this->request = $request;
        foreach(Produce::VALID_TYPES as $type) {
            $this->collections[$type] = new ProduceCollection();
        }
        $items = json_decode($request, true);
        foreach ($items as $item) {
            $produce = new Produce(
                $item['id'],
                $item['name'],
                $item['type'],
                new Quantity($item['quantity'], $item['unit'])
            );
            $this->collections[$produce->getType()]->add($produce);
        }
    }

    public function getRequest(): string {
        return $this->request;
    }

    public function add(array $item): void {
        $produce = new Produce(
            $item['id'],
            $item['name'],
            $item['type'],
            new Quantity($item['quantity'], $item['unit'])
        );
        $this->collections[$produce->getType()]->add($produce);
    }

    public function remove(int $id): void {
        foreach($this->collections as $collection) {
            $collection->remove($id);
        }
    }

    public function list(string $unit): array {
        return $this->search(null, $unit);
    }

    public function search(string $searchString = null, string $unit = Quantity::UNIT_GRAMS): array {
        $collections = $this->collections;
        $response = [];
        foreach ($collections as $collection) {
            $response[] = $collection->search($searchString);
        }

        $orderedList = $this->sort(array_merge(...$response));
        $formatter = new ProduceFormatter($unit);

        return array_map(array($formatter, 'format'), $orderedList);
    }

    private function sort(array $list): array {
        usort($list, function($a, $b) {
            return $a->getId() <=> $b->getId();
        });
        return $list;
    }
}
