<?php

namespace App\Filament\Clusters\Settings\Resources\RiskMatrixResource\Pages;

use App\Filament\Clusters\Settings\Resources\RiskMatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskMatrices extends ListRecords
{
    protected static string $resource = RiskMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-s-plus'),
        ];
    }
}
