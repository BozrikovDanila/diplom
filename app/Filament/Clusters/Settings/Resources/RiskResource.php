<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\RiskResource\Pages;
use App\Filament\Clusters\Settings\Resources\RiskResource\RelationManagers;
use App\Models\Risk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RiskResource extends Resource
{
    protected static ?string $model = Risk::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $activeNavigationIcon = 'heroicon-o-bolt';


    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Риски';

    protected static ?string $modelLabel = 'риск';

    protected static ?string $pluralModelLabel = 'рисков';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function canViewAny(): bool
    {
        return auth()->user()->userRole()->first()?->name === UserRoleEnum::Admin->value;
    }

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Риск')
                    ->icon('heroicon-s-bolt')
                    ->iconColor(Color::Red)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Дата редактирования')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRisks::route('/'),
        ];
    }
}
