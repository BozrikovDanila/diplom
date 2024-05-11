<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\CompetencyResource\Pages;
use App\Filament\Clusters\Settings\Resources\CompetencyResource\RelationManagers;
use App\Models\Competency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use \Filament\Support\Colors\Color;
class CompetencyResource extends Resource
{
    protected static ?string $model = Competency::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Компетенции';
    protected static ?string $pluralLabel = "Компетенции";

    protected static ?string $modelLabel = 'компетенцию';
    protected static ?string $pluralModelLabel = 'компетенций';

    protected static ?int $navigationSort = 2;

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
                    ->maxLength(255)
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->icon('heroicon-s-users')
                    ->color(Color::Slate),
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
            'index' => Pages\ListCompetencies::route('/'),
        ];
    }
}
