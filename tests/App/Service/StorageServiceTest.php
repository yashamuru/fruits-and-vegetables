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
        $responseGrams = json_decode(file_get_contents('tests/fixtures/basicResponse_g.json'), true);
        $responseKg = json_decode(file_get_contents('tests/fixtures/basicResponse_kg.json'), true);

        $storageService = new StorageService($request);

        $this->assertEquals($responseGrams, $storageService->list('g'), "Formats list for grams");
        $this->assertEquals($responseKg,    $storageService->list('kg'), "Formats list for kilograms");
    }

    public function testSearch(): void
    {
        $request = file_get_contents('tests/fixtures/basicRequest.json');
        $responseGrams = json_decode(file_get_contents('tests/fixtures/search/melon_g.json'), true);
        $responseKg = json_decode(file_get_contents('tests/fixtures/search/melon_kg.json'), true);

        $storageService = new StorageService($request);

        $this->assertEquals($responseGrams, $storageService->search("Melon", 'g'), "Formats search for grams");
        $this->assertEquals($responseKg,    $storageService->search("Melon", 'kg'), "Formats search for kilograms");
    }
}
