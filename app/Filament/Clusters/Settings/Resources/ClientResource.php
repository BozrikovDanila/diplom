<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\ClientResource\Pages;
use App\Filament\Clusters\Settings\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\TemplateResource\GetFieldsInterface;
use App\Models\Client;
use App\Models\ClientStatus;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Colors\ColorManager;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Клиенты';
    protected static ?string $pluralLabel = 'Клиенты';
    protected static ?string $modelLabel = 'клиента';
    protected static ?string $pluralModelLabel = 'клиентов';

    protected static ?int $navigationSort = 7;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;


    public static function canViewAny(): bool
    {
        return auth()->user()->userRole()->first()?->name === UserRoleEnum::Admin->value;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ФИО')
                    ->schema([
                        Forms\Components\TextInput::make('last_name')
                            ->label('Фамилия')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('first_name')
                            ->label('Имя')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('second_name')
                            ->label('Отчество')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Почта и номер телефона')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('phone')
                            ->label('Номер телефона')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\TextInput::make('INN')
                    ->label('ИНН')
                    ->regex('/^[0-9]{4}\-[0-9]{5}\-[0-9]{1}$/')
                    ->mask('9999-99999-9')
                    ->placeholder('1232-45345-4')
                    ->rule(static function (TextInput $component): \Closure {
                        return static function (string $attribute, $value, \Closure $fail) use ($component) {
                            $inn = str($value);

                            $v = (
                                intval($inn[0]) * 2 +
                                intval($inn[1]) * 4 +
                                intval($inn[2]) * 10 +
                                intval($inn[3]) * 3 +
                                intval($inn[4]) * 5 +
                                intval($inn[5]) * 9 +
                                intval($inn[6]) * 4 +
                                intval($inn[7]) * 6 +
                                intval($inn[8]) * 8
                                ) % 11;

                            if (str($v)[-1] != $inn[9]) {
                                $fail('ИНН неверный');
                            }
                        };
                    })
                    ->required()
                    ->maxLength(12),
                Forms\Components\Section::make('Количество сотрудников и оценок')
                    ->schema([
                        Forms\Components\TextInput::make('employees_number')
                            ->label('Сотрудники')
                            ->placeholder('Количество')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('assessments_number')
                            ->label('Прогнозы')
                            ->placeholder('Количество')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(1),
                    ]),
                Forms\Components\Select::make('riskMatrices')
                    ->label('Матрицы')
                    ->preload()
                    ->relationship('riskMatrices', 'name')
                    ->multiple(),
                Forms\Components\DatePicker::make('duration')
                    ->label('Срок действия')
                    ->default(now('Europe/Moscow'))
                    ->required()
                    ->suffixAction(
                        Action::make('setDurationInfinity')
                            ->label('')
                            ->tooltip('Установить дату в 01.01.2500')
                            ->icon('heroicon-m-clock')
                            ->action(function (Set $set) {
                                $set('duration', "2500-01-01");
                            })
                    ),
                Forms\Components\ToggleButtons::make('instant_access')
                    ->label('Доступ')
                    ->default(0)
                    ->options([
                        0 => 'Доступ к прогнозам после оплаты',
                        1 => 'Открыть доступ сразу',
                    ])
                    ->colors([
                        0 => 'warning',
                        1 => 'info',
                    ])
                    ->required(),
                Forms\Components\ToggleButtons::make('client_status_id')
                    ->label('Статус клиента')
                    ->default(1)
                    ->options(fn() => ClientStatus::all()->pluck('name', 'id')->all())
                    ->colors(function() {
                        $arr = ClientStatus::all()->pluck('name', 'id')->all();
                        $colors = [Color::Green, Color::Red, Color::Rose, Color::Emerald, Color::Gray, Color::Orange, Color::Indigo];
                        foreach ($arr as $key => $item) {
                            $arr[$key] = $colors[array_rand($colors)];
                        }

                        return $arr;
                    })
                    ->required(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('last_name')
                    ->label('ФИО')
                    ->weight(FontWeight::Bold)
                    ->wrap()
                    ->formatStateUsing(fn(Client $record) => "$record->last_name $record->first_name $record->second_name")
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(isIndividual: true)
                    ->copyable()
                    ->icon('heroicon-s-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Номер телефона')
                    ->copyable()
                    ->icon('heroicon-s-phone')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('INN')
                    ->label('ИНН')
                    ->copyable()
                    ->color(Color::Teal)
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('employees_number')
                    ->label('Сотрудники и прогнозы')
                    ->formatStateUsing(fn (Client $record) => "Cотрудников: $record->employees_number\n\nОценок: $record->assessments_number")
                    ->color(Color::Green)
                    ->markdown()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('До')
                    ->date('d/m/y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('riskMatrices.name')
                    ->label('Матрицы')
                    ->badge(),
                Tables\Columns\TextColumn::make('instant_access')
                    ->label('Доступ')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state) => match ($state){ "0" => 'После оплаты', "1" => 'Сразу' })
                    ->color(fn (string $state) => match ($state){ "0" => Color::Orange, "1" => Color::Emerald }),
                Tables\Columns\TextColumn::make('clientStatus.name')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state) => match ($state){ "Ждёт оплаты" => Color::Amber, "Активен" => Color::Green, "Заблокирован" => Color::Red })
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('update_status')
                    ->label('')
                    ->tooltip('Обновить статус клиента')
                    ->icon('heroicon-s-arrow-path-rounded-square')
                    ->iconPosition(IconPosition::After)
                    ->action(function (Client $record) {
                        $status = match ($record->clientStatus()->first()->id) {
                            1 => 2,
                            2 => 3,
                            3 => 1,
                        };

                        $record->update(['client_status_id' => $status]);
                    })
                    ->size(ActionSize::Large),
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->tooltip('Подробнее')
                    ->size(ActionSize::Large),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('Редактировать')
                    ->size(ActionSize::Large),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->size(ActionSize::Large),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Group::make([
                        TextEntry::make('last_name')
                            ->label('Фамилия')
                            ->inlineLabel(),
                        TextEntry::make('first_name')
                            ->label('Имя')
                            ->inlineLabel(),
                        TextEntry::make('second_name')
                            ->label('Отчество')
                            ->inlineLabel(),
                    ])->columns(1),
                    Group::make([
                        TextEntry::make('email'),
                        TextEntry::make('phone')
                            ->label('Номер телефона'),
                        TextEntry::make('INN')
                            ->label('ИНН'),
                    ])->columns(1),
                    Group::make([
                        Group::make([
                            TextEntry::make('employees_number')
                                ->numeric()
                                ->hiddenLabel()
                                ->badge()
                                ->color(Color::Teal)
                                ->prefix('Кол-во сотрудников: ')
                                ->size(TextEntry\TextEntrySize::Large),
                            TextEntry::make('assessments_number')
                                ->numeric()
                                ->hiddenLabel()
                                ->color(Color::Sky)
                                ->prefix('Кол-во оценок: ')
                                ->badge()
                                ->size(TextEntry\TextEntrySize::Large),
                        ])->columnSpan(2),
                        Group::make([
                            TextEntry::make('instant_access')
                                ->hiddenLabel()
                                ->badge()
                                ->size(TextEntry\TextEntrySize::Large)
                                ->color(fn (string $state) => match ($state){ "0" => Color::Yellow, "1" => Color::Green })
                                ->formatStateUsing(fn (string $state) => match ($state){ "0" => 'Доступ после оплаты', "1" => 'Доступ сразу' }),
                            TextEntry::make('clientStatus.name')
                                ->label('Статус клиента')
                                ->color(fn (string $state) => match ($state){ "Ждёт оплаты" => Color::Orange, "Активен" => Color::Green, "Заблокирован" => Color::Red })
                                ->badge(),
                        ])->columnSpan(2),
                        TextEntry::make('duration')
                            ->label('Длительность')
                            ->prefix('До ')
                            ->date('d/m/y')
                            ->columnSpan(2),
                    ])
                ])
                ->columnSpan(2),
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
            'index' => Pages\ListClients::route('/'),
        ];
    }
}
