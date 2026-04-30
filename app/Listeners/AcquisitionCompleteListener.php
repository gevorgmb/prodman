<?php
 
namespace App\Listeners;


use App\Events\AcquisitionCompleteEvent;
use App\Models\Acquisition;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use App\Services\Contracts\StockProductServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AcquisitionCompleteListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly AcquisitionItemServiceInterface $itemService,
        private readonly StockProductServiceInterface $stockProductService,
    ) {
    }

    public function handle(AcquisitionCompleteEvent $event): void
    {
        $acquisition = Acquisition::find($event->acquisitionId);
        if (!$acquisition) {
            return;
        }

        $items = $this->itemService->getAllByAcquisitionId($event->acquisitionId);

        foreach ($items as $item) {
            $this->stockProductService->create([
                'itemId' => $item->id,
                'productName' => $item->productName,
                'quantity' => $item->quantity,
                'quantityUsed' => 0,
                'expirationDate' => $item->expirationDate?->toDateString(),
            ], $acquisition->apartment_id);
        }
    }
}
