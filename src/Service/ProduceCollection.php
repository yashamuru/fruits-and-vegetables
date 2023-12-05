<?php

namespace App\Service;

use Webmozart\Assert\Assert;
use App\Domain\Produce;

class ProduceCollection
{
    private $items = [];

    public function add(Produce $produce): self {
        $id = $produce->getId();
        $existing = $this->items[$id] ?? null;
        if (empty($existing)) {
            $this->items[$id] = $produce;
        } else {
            $newQuantity = $existing->getQuantity()->add($produce->getQuantity());
            $this->items[$id]->setQuantity($newQuantity);
        }
        return $this;
    }

    public function remove(int $id): self {
        Assert::keyExists($this->items, $id, 'Item not found');
        unset($this->items[$id]);
        return $this;
    }

    public function list(): array {
        return $this->items;
    }
}
