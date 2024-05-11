<?php

namespace App\Filament\Clusters\Settings\Resources\DataSourceResource\Pages;

use App\Filament\Clusters\Settings\Resources\DataSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListDataSources extends ListRecords
{
    protected static string $resource = DataSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-s-plus')
                ->modalWidth(MaxWidth::FitContent),
        ];
    }
}
