<?php

namespace App\Filament\Resources\NumberResource\Pages;

use App\Filament\Resources\NumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNumbers extends ListRecords
{
    protected static string $resource = NumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
