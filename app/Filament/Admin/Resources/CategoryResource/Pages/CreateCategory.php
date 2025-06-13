<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Obtiene la notificación que se mostrará después de crear una categoría.
     *
     * @return Notification|null
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Categoría creada')
            ->body('La categoría ha sido creada exitosamente.');
    }

    /**
     * Redirije al indice después de crear una categoría.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
