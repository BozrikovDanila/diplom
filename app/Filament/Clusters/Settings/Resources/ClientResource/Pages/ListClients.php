<?php

namespace App\Filament\Clusters\Settings\Resources\ClientResource\Pages;

use App\Filament\Clusters\Settings\Resources\ClientResource;
use App\Models\Client;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-s-plus')
                ->modalWidth(MaxWidth::FitContent)
                ->mutateFormDataUsing(function ($data, $livewire) {
                    $data['INN'] = str_replace('-', '', $data['INN']);

                    return $data;
                }),
        ];
    }
}
