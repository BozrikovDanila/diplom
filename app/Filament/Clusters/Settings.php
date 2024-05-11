<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Colors\Color;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-cog-8-tooth';

    protected static ?string $navigationLabel = 'Настройки';
    protected static ?string $pluralModelLabel = 'Настройки';

    protected static ?int $navigationSort = 100;

    public static function getNavigationLabel(): string
    {
        return "Настройки";
    }

    public static function getClusterBreadcrumb(): string
    {
        return "Настройки";
    }

}
