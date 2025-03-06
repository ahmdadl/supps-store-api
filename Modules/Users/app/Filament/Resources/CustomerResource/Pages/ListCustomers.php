<?php

namespace Modules\Users\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Users\Filament\Resources\CustomerResource;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
