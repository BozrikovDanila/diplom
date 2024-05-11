<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\IndicatorTypeEnum;
use App\Enums\UserRoleEnum;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\IndicatorResource\Pages;
use App\Filament\Clusters\Settings\Resources\IndicatorResource\RelationManagers;
use App\Models\Indicator;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorResource extends Resource
{
    protected static ?string $model = Indicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Показатели';

    protected static ?string $modelLabel = 'показатель';
    protected static ?string $pluralModelLabel = 'Показатели';
    protected static ?int $navigationSort = 3;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;


    public static function canViewAny(): bool
    {
        return auth()->user()->userRole()->first()?->name === UserRoleEnum::Admin->value;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('question')
                    ->required()
                    ->label('Вопрос')
                    ->maxLength(255),
                Forms\Components\TextInput::make('indicator_key')
                    ->required()
                    ->regex('/^([a-zA-Z]){1}([a-zA-Z0-9])*(\_[a-zA-Z0-9])*$/')
                    ->maxLength(45)
                    ->label('Идентификатор показателя')
                    ->default(""),
                Forms\Components\Select::make('data_source_id')
                    ->label('Источник данных')
                    ->relationship('dataSource', 'name')
                    ->required(),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\Select::make('indicator_type_id')
                            ->relationship('indicatorType', 'name')
                            ->label('Тип показателя')
                            ->required()
                            ->live(debounce: 2),
                        Forms\Components\Repeater::make('radio_value')
                            ->schema([
                                Forms\Components\TextInput::make('radio')
                                    ->required()
                                    ->label("Ответ")
                                    ->maxLength(255),
                            ])
                            ->itemLabel(function ($uuid, $component) {
                                $keys = array_keys($component->getState());
                                $index = array_search($uuid, $keys) + 1;

                                return $index;
                            })
                            ->label('Варианты ответов')
                            ->required()
                            ->hidden(fn(Get $get) => $get('indicator_type_id') != IndicatorTypeEnum::RadioButtons->id())
                            ->deletable(fn($component) => count($component->getState()) > 2)
                            ->addActionLabel('Добавить вариант ответа')
                            ->columnSpan(2)
                            ->minItems(2)
                            ->defaultItems(2),
                        Forms\Components\Textarea::make('formula')
                            ->required()
                            ->label('Формула')
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->hint(str('[Documentation](https://docs.google.com/spreadsheets/d/1gZ38BOBqifmTJf0050qC0GSnWIJBzgy0sB7OJL-ntWM/edit?usp=sharing)')->inlineMarkdown()->toHtmlString())
                            ->hintColor(Color::Green)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Правила оформления формул')
                            ->hidden(fn(Get $get) => $get('indicator_type_id') != IndicatorTypeEnum::Value->id()),
                        Forms\Components\TextInput::make('const_value')
                            ->default("")
                            ->required()
                            ->label('Значение константы')
                            ->hidden(fn(Get $get) => $get('indicator_type_id') != IndicatorTypeEnum::Constant->id()),
                    ])
                    ->label('Тип'),
                Forms\Components\Select::make('competency_id')
                    ->label('Компетенция')
                    ->relationship('competency', 'name')
                    ->required(),
                Forms\Components\TagsInput::make('tags')
                    ->label('Тэги')
                    ->suggestions(function () {
                        return Tag::all()->pluck('name', 'id')->all();
                    })

            ])
            ->columns(1);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Вопрос')
                    ->searchable(),
                Tables\Columns\TextColumn::make('indicator_key')
                    ->label('Идентификатор')
                    ->searchable(),
                Tables\Columns\TextColumn::make('formula')
                    ->label('Формула')
                    ->searchable()
                    ->default('Нет формулы'),
                Tables\Columns\TextColumn::make('indicator_value')
                    ->label('Константа')
                    ->default('Нет константы')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('indicatorType.name')
                    ->label('Тип')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('competency.name')
                    ->label('Компетенция')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\EditAction::make()
                    ->fillForm(function (Indicator $record) {
                        $fill = $record->toArray();
                        $tags = [];
                        foreach ($record->tags()->allRelatedIds()->all() as $tagId) {
                            $tags[] = Tag::query()->where('id', $tagId)->first()?->name;
                        }

                        $fill['tags'] = $tags;

                        return $fill;
                    }),
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
            'index' => Pages\ListIndicators::route('/'),
        ];
    }
}

