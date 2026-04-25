<?php
namespace Tests\Unit\Services;

use App\Http\Services\ExportService;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class ExportServiceTest extends TestCase
{
    public function test_export_to_excel_returns_streamed_response()
    {
        $service = new ExportService();
        $fileName = 'test.xlsx';
        $headers = ['ID', 'Name'];

        $query = \Mockery::mock(Builder::class);
        $query->shouldReceive('chunk')
            ->with(500, \Mockery::any())
            ->andReturnUsing(function ($size, $callback) {
                // Simulate chunking with fake items
                $fakeItem = (object)['id' => 1, 'name' => 'Test'];
                $callback(collect([$fakeItem]));
                return true;
            });

        $response = $service->exportToExcel($fileName, $headers, $query, function ($item) {
            return [$item->id, $item->name];
        });

        $this->assertInstanceOf(StreamedResponse::class, $response);

        // Actually stream the response to trigger the callback
        ob_start();
        $response->sendContent();
        ob_end_clean();

        $this->assertStringContainsString(
            'filename=test.xlsx',
            $response->headers->get('Content-Disposition')
        );
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type')
        );
    }
}