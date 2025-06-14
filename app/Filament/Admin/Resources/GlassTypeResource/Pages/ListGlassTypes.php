<?php

namespace App\Filament\Admin\Resources\GlassTypeResource\Pages;

use App\Filament\Admin\Resources\GlassTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGlassTypes extends ListRecords
{
    protected static string $resource = GlassTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
