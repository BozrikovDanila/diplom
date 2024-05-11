<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskAssessmentResource\Pages;
use App\Filament\Resources\RiskAssessmentResource\RelationManagers;
use App\Models\RiskAssessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RiskAssessmentResource extends Resource
{
    protected static ?string $model = RiskAssessment::class;

    protected static ?string $navigationIcon = 'heroicon-s-calculator';

    protected static ?string $navigationLabel = 'Оценки рисков';

    protected static ?string $breadcrumb = 'Оценки рисков';

    protected static ?string $modelLabel = 'Оценки рисков';
    protected static ?string $pluralModelLabel = 'Оценки рисков';
    protected static ?int $navigationSort = 99;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('completion_date')
                    ->required(),
                Forms\Components\TextInput::make('total_score')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'id')
                    ->required(),
                Forms\Components\Select::make('assessment_status_id')
                    ->relationship('assessmentStatus', 'name')
                    ->required(),
                Forms\Components\Select::make('risk_matrix_id')
                    ->relationship('riskMatrix', 'name')
                    ->required(),
                Forms\Components\TextInput::make('org_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('INN')
                    ->required()
                    ->maxLength(12),
                Forms\Components\TextInput::make('selected_risks')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('completion_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessmentStatus.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('riskMatrix.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('org_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('INN')
                    ->searchable(),
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
            'index' => Pages\ListRiskAssessments::route('/'),
            'create' => Pages\CreateRiskAssessment::route('/create'),
            'edit' => Pages\EditRiskAssessment::route('/{record}/edit'),
        ];
    }
}
