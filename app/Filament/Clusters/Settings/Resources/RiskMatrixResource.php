<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\RiskMatrixResource\Pages;
use App\Filament\Clusters\Settings\Resources\RiskMatrixResource\RelationManagers;
use App\Models\RiskMatrix;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Metadata\Group;

class RiskMatrixResource extends Resource
{
    protected static ?string $model = RiskMatrix::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Матрицы рисков';
    protected static ?string $pluralModelLabel = 'Матрицы рисков';
    protected static ?int $navigationSort = 5;
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
                    ->maxLength(255),
                Forms\Components\Select::make('risks')
                    ->relationship('risks', 'name')
                    ->preload()
                    ->multiple()
                    ->required(),
                Forms\Components\Repeater::make('probabilities')
                    ->schema([
                        Forms\Components\Split::make([
                            Forms\Components\Group::make([
                                Forms\Components\TextInput::make('prob_name')
                                    ->required()
                                    ->hiddenLabel()
                                    ->maxWidth('content'),
                                Forms\Components\TextInput::make('prob_value')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxWidth('content')
                                    ->hiddenLabel(),
                                Forms\Components\ColorPicker::make('prob_color')
                                    ->required()
                                    ->hexColor()
                                    ->default('#74d65b')
                                    ->maxWidth('content')
                                    ->hiddenLabel(),
                            ])->columns(3),
                            Forms\Components\Group::make([
                                Forms\Components\TextInput::make('prob_from')
                                    ->required()
                                    ->hiddenLabel()
                                    ->maxWidth('min')
                                    ->readOnly(function (Get $get) {
                                        dd($get('../../probabilities'));
                                        return true;
                                    }),
                                Forms\Components\TextInput::make('prob_to')
                                    ->required()
                                    ->maxWidth('min')
                                    ->hiddenLabel(),
                            ])->columns(2)
                        ])->columnSpan(5),
                    ])->columns(5)->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('risks.name')
                    ->label('Риски')
                    ->badge(),
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
            'index' => Pages\ListRiskMatrices::route('/'),
        ];
    }
}
