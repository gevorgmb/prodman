<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ArchiveStockItemEvent;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use App\Services\Contracts\ArchivedAcquisitionItemServiceInterface;
use App\Services\Contracts\StockProductServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class ArchiveStockItemListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private StockProductServiceInterface $stockProductService,
        private AcquisitionItemServiceInterface $acquisitionItemService,
        private ArchivedAcquisitionItemServiceInterface $archivedItemService,
    )
    {
        //
    }

    /**
     * Handle the event.
     * @throws Throwable
     */
    public function handle(ArchiveStockItemEvent $event): void
    {
        \Log::info('Archiving stock item');
        $stockProduct = $this->stockProductService->findById($event->stockProductId);
        if (! $stockProduct) {
            return;
        }

        $acquisitionItem = $this->acquisitionItemService->findById($stockProduct->id);

        $archivedItemData = [
            'apartment_id' => $stockProduct->apartmentId,
            'item_id' => $stockProduct->itemId,
            'product_name' => $stockProduct->productName,
            'quantity' => $acquisitionItem->quantity,
            'expiration_date' => $stockProduct->expirationDate,
            'archive_date' => now(),
            'quantity_available' => $stockProduct->quantityAvailable,
            'reason' => $event->reason,
        ];

        try {
            DB::beginTransaction();
            $this->archivedItemService->create($archivedItemData, $stockProduct->apartmentId);
            $this->stockProductService->delete($stockProduct->id, $stockProduct->apartmentId);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
