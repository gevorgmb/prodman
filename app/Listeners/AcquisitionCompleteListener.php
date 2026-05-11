<?php

namespace App\Listeners;


use App\Events\AcquisitionCompleteEvent;
use App\Models\Acquisition;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\StockProductServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AcquisitionCompleteListener
{
    use InteractsWithQueue;

    public function __construct(
        private readonly AcquisitionItemServiceInterface $itemService,
        private readonly StockProductServiceInterface $stockProductService,
        private readonly ProductServiceInterface $productService,
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
            $min = 0;
            $unit = 'kg';

            $mergeStock = false;
            if ($item->productId) {
                $product = $this->productService->findByIdAndApartmentId($item->productId, $acquisition->apartment_id);
                if ($product) {
                    $min = $product->min;
                    $unit = $product->unit;
                    if ($product->mergeStock) {
                        $mergeStock = true;
                    }
                }
            }

            $this->stockProductService->create([
                'itemId' => $item->id,
                'product_id' => $item->productId ?? null,
                'productName' => $item->productName,
                'quantityAvailable' => $item->quantity,
                'expirationDate' => $item->expirationDate?->toDateString(),
                'min' => $min,
                'unit' => $unit,
            ], $acquisition->apartment_id);
            if ($mergeStock) {
                $this->stockProductService->mergeByProductId($item->productId);
            }
        }
    }
}
