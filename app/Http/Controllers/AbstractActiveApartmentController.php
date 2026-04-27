<?php

namespace App\Http\Controllers;

use App\Dto\Apartment\ManagedApartmentDto;
use App\Exceptions\Apartment\ApartmentHeaderMissingException;
use App\Exceptions\Apartment\UnauthenticatedException;
use App\Models\User;
use App\Services\Contracts\ApartmentServiceInterface;
use Illuminate\Http\Request;

abstract class AbstractActiveApartmentController extends Controller
{
    public function __construct(
        private readonly ApartmentServiceInterface $apartmentService,
    ) {
    }

    protected function resolveManagedApartment(Request $request): ManagedApartmentDto
    {
        /** @var ?User $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            throw new UnauthenticatedException();
        }
        $apartmentHeader = $request->header('apartment');
        if (! is_numeric($apartmentHeader)) {
            throw new ApartmentHeaderMissingException();
        }

        $apartmentId = (int) $apartmentHeader;
        return $this->apartmentService->getManagedApartment($authUser->id, $apartmentId);
    }
}
