<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\DataSourceResource\Pages;
use App\Filament\Clusters\Settings\Resources\DataSourceResource\RelationManagers;
use App\Filament\Resources\TemplateResource;
use App\Models\DataSource;
use App\Models\Event;
use Dflydev\DotAccessData\Data;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataSourceResource extends Resource
{
    protected static ?string $model = DataSource::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Источники данных';
    protected static ?string $pluralLabel = 'Источники данных';

    protected static ?string $modelLabel = 'источник данных';
    protected static ?string $pluralModelLabel = 'источников данных';
    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;


    public static function canViewAny(): bool
    {
        return auth()->user()->userRole()->first()?->name === UserRoleEnum::Admin->value;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Название')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label('Описание в выпадающем меню')
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->url()
                    ->label('Ссылка на ресурс')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->suffixIconColor('success')
                    ->maxLength(255),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Источник')
                    ->weight(FontWeight::Bold)
                    ->description(fn (DataSource $record) => $record->description)
                    ->copyable()
                    ->copyMessage('Тест скопирован')
                    ->wrap(),
                Tables\Columns\TextColumn::make('link')
                    ->searchable()
                    ->label('Ссылка')
                    ->url(fn (DataSource $record): string => $record->link)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataSources::route('/'),
        ];
    }
}
