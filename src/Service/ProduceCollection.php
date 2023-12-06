<?php

namespace App\Service;

use Webmozart\Assert\Assert;
use App\Domain\Produce;

class ProduceCollection
{
    private $items = [];

    public function add(Produce $produce): self {
        $id = $produce->getId();
        $existing = $this->getById($id) ?? null;
        if (empty($existing)) {
            $this->items[$id] = $produce;
        } else {
            $existing->getQuantity()->add($produce->getQuantity());
        }
        return $this;
    }

    public function remove(int $id): self {
        if (! empty($this->getById($id))) {
            unset($this->items[$id]);
        }
        return $this;
    }

    public function subtract(int $id, Produce $produce): self {
        $existing = $this->getById($id);
        Assert::notEmpty($existing, 'Item not found');
        $newQuantity = $existing->getQuantity()->subtract($produce->getQuantity());
        $existing->setQuantity($newQuantity);
        return $this;
    }

    public function getById(int $id): ?Produce {
        return $this->items[$id] ?? null;
    }

    /**
     * @return Produce[]
     */
    public function list(): array {
        return array_values($this->items);
    }

    /**
     * @return Produce[]
     */
    public function search(string $nameFilter = null) : array {
        if (empty($nameFilter)) {
            return $this->list();
        }
        $search = mb_strtolower($nameFilter);
        return array_filter($this->items, function($item) use ($search) {
            return strpos(mb_strtolower($item->getName()), $search) !== false;
        });
    }
}
