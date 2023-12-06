<?php

namespace App\Tests\App\Service;

use App\Service\StorageService;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    public function testReceivingRequest(): void
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $this->assertNotEmpty($storageService->getRequest());
        $this->assertIsString($storageService->getRequest());
    }

    public function testList(): void
    {
        $request = file_get_contents('tests/fixtures/basicRequest.json');
        $storageService = new StorageService($request);

        $responseGrams = json_decode(file_get_contents('tests/fixtures/basicResponse_g.json'), true);
        $this->assertEquals($responseGrams, $storageService->list('g'), "Formats list for grams");

        $responseKg = json_decode(file_get_contents('tests/fixtures/basicResponse_kg.json'), true);
        $this->assertEquals($responseKg,    $storageService->list('kg'), "Formats list for kilograms");
    }

    public function testSearch(): void
    {
        $request = file_get_contents('tests/fixtures/basicRequest.json');
        $storageService = new StorageService($request);

        $responseGrams = json_decode(file_get_contents('tests/fixtures/search/melon_g.json'), true);
        $this->assertEquals($responseGrams, $storageService->search("Melon", 'g'), "Formats search for grams");

        $responseKg = json_decode(file_get_contents('tests/fixtures/search/melon_kg.json'), true);
        $this->assertEquals($responseKg,    $storageService->search("Melon", 'kg'), "Formats search for kilograms");
    }

    public function testRemove(): void
    {
        $request = file_get_contents('tests/fixtures/basicRequest.json');

        $storageService = new StorageService($request);
        $storageService->remove(1);
        $storageService->remove(3);

        $expectedResponse = json_decode(file_get_contents('tests/fixtures/removed_1_and_3_kg.json'), true);
        $this->assertEquals($expectedResponse,    $storageService->list('kg'), "Removed response");
    }

    public function testAdd(): void
    {
        $request = file_get_contents('tests/fixtures/basicRequest.json');

        $storageService = new StorageService($request);
        $storageService->add([
            'id' => 2,
            'name' => 'Apple',
            'type' => 'fruit',
            'quantity' => 6.789,
            'unit' => 'kg'
        ]);
        $storageService->add([
            'id' => 5,
            'name' => 'Beans',
            'type' => 'vegetable',
            'quantity' => 3,
            'unit' => 'kg'
        ]);
        $storageService->add([
            'id' => 6,
            'name' => 'Cucumber',
            'type' => 'vegetable',
            'quantity' => 2,
            'unit' => 'kg'
        ]);

        $expectedResponse = json_decode(file_get_contents('tests/fixtures/added_2_and_5_g.json'), true);
        $this->assertEquals($expectedResponse, $storageService->list('g'), "Added response");
    }
}
