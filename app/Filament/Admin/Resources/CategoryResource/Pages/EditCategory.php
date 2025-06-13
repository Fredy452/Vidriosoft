<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    /**
     * Redirije al indice después de editar una categoría.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    /**
     * Obtiene la notificación que se mostrará después de editar una categoría.
     *
     * @return Notification|null
     */
    protected function getUpdatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Categoría actualizada')
            ->body('La categoría ha sido actualizada exitosamente.');
    }
}
