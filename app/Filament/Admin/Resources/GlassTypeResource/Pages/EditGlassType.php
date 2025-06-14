<?php

namespace App\Filament\Admin\Resources\GlassTypeResource\Pages;

use App\Filament\Admin\Resources\GlassTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGlassType extends EditRecord
{
    protected static string $resource = GlassTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
