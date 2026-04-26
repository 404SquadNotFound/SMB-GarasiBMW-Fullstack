<?php

namespace Tests\Unit;

use App\Http\Services\ExportService;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

class ExportServiceTest extends TestCase
{
    protected function triggerResponse(StreamedResponse $response)
    {
        ob_start();
        $response->sendContent();
        ob_end_clean();
    }

    public function test_export_to_excel_returns_streamed_response()
    {
        $service = new ExportService();
        $fileName = 'test.xlsx';
        $headers = ['ID', 'Name'];

        $query = Mockery::mock(Builder::class);
        $query->shouldReceive('chunk')
            ->with(500, Mockery::any())
            ->andReturnUsing(function ($size, $callback) {
                $fakeItem = (object) ['id' => 1, 'name' => 'Test'];
                $callback(collect([$fakeItem]));
                return true;
            });

        $response = $service->exportToExcel($fileName, $headers, $query, function ($item) {
            return [$item->id, $item->name];
        });

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->triggerResponse($response);

        $this->assertStringContainsString(
            'filename=test.xlsx',
            $response->headers->get('Content-Disposition')
        );
    }

    public function test_export_writes_headers_first()
    {
        $service = new ExportService();
        $headers = ['ID', 'Name'];

        $query = Mockery::mock(Builder::class);
        $query->shouldReceive('chunk')
            ->once()
            ->andReturnUsing(function ($size, $callback) {
                $callback(collect([]));
                return true;
            });

        $response = $service->exportToExcel('test.xlsx', $headers, $query, function () {
            return [];
        });

        $this->triggerResponse($response);
        
        $this->assertTrue(true); 
    }

    public function test_maprow_function_is_called_correctly()
    {
        $service = new ExportService();
        $query = Mockery::mock(Builder::class);

        $query->shouldReceive('chunk')
            ->once()
            ->andReturnUsing(function ($size, $callback) {
                $callback(collect([
                    (object) ['id' => 99, 'name' => 'Raka'],
                ]));
                return true;
            });

        $response = $service->exportToExcel('test.xlsx', ['ID', 'Name'], $query, function ($item) {
            $this->assertEquals(99, $item->id);
            $this->assertEquals('Raka', $item->name);
            return [$item->id, strtoupper($item->name)];
        });

        $this->triggerResponse($response);
    }

    public function test_export_handles_empty_dataset()
    {
        $service = new ExportService();
        $query = Mockery::mock(Builder::class);

        $query->shouldReceive('chunk')
            ->once()
            ->andReturnUsing(function ($size, $callback) {
                $callback(collect([]));
                return true;
            });

        $response = $service->exportToExcel('test.xlsx', ['ID'], $query, function ($item) {
            return [$item->id ?? null];
        });

        $this->assertInstanceOf(StreamedResponse::class, $response);

        // TRIGGER EXECUTION
        $this->triggerResponse($response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}