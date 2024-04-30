<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\BeneficiosService;
use Mockery;

class BeneficiosServiceTest extends TestCase
{
    public function testProcessMethodReturnsCorrectStructure()
    {
        $service = Mockery::mock(BeneficiosService::class)->makePartial();

        // Testeamos los endpoints
        $service->shouldReceive('getDataFromUrl')
            ->andReturnUsing(function ($url) {
                // 
                if ($url == "https://run.mocky.io/v3/399b4ce1-5f6e-4983-a9e8-e3fa39e1ea71") {
                    return ['data' => [['id_programa' => 147, 'monto' => 30000, 'fecha' => '2023-03-10', 'fecha_recepcion' => '10/03/2023']]];
                } elseif ($url == "https://run.mocky.io/v3/06b8dd68-7d6d-4857-85ff-b58e204acbf4") {
                    return ['data' => [['id_programa' => 147, 'min' => 10000, 'max' => 50000, 'ficha_id' => 1]]];
                } elseif ($url == "https://run.mocky.io/v3/c7a4777f-e383-4122-8a89-70f29a6830c0") {
                    return ['data' => [['id_programa' => 147, 'nombre' => 'Programa 147']]];
                }
                return [];
            });

        $result = $service->process();
        $this->assertEquals(200, $result['code']);
        $this->assertTrue($result['success']);
        $this->assertIsArray($result['data']);
        $this->assertCount(1, $result['data']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}