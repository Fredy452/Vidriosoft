<?php

namespace App\Filament\Admin\Resources\ProviderResource\Pages;

use App\Filament\Admin\Resources\ProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

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
}
