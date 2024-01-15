<?php

namespace App\Filament\Resources\PrizeResource\Pages;

use App\Filament\Resources\PrizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrizes extends ListRecords
{
    protected static string $resource = PrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
