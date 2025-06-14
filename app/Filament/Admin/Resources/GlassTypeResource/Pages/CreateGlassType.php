<?php

namespace App\Filament\Admin\Resources\GlassTypeResource\Pages;

use App\Filament\Admin\Resources\GlassTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateGlassType extends CreateRecord
{
    protected static string $resource = GlassTypeResource::class;

    /**
     * Obtiene la notificación que se mostrará después de crear un tipo de vidrio.
     *
     * @return Notification|null
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tipo de vidrio creado')
            ->body('El tipo de vidrio ha sido creado exitosamente.');
    }

    /**
     * Redirije al indice después de crear un tipo de vidrio.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
