<?php

namespace App\Filament\Admin\Resources\ProviderResource\Pages;

use App\Filament\Admin\Resources\ProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProvider extends CreateRecord
{
    protected static string $resource = ProviderResource::class;

    /**
     * Redirije al indice después de crear un proveedor.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Obtiene la notificación que se mostrará después de crear un usuario.
     *
     * @return Notification|null
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Proveedor creado')
            ->body('El proveedor ha sido creado exitosamente.');
    }
}
