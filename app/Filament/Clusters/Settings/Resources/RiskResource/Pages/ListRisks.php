<?php

namespace App\Filament\Clusters\Settings\Resources\RiskResource\Pages;

use App\Filament\Clusters\Settings\Resources\RiskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListRisks extends ListRecords
{
    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::FitContent)
                ->icon('heroicon-s-plus'),
        ];
    }
}
