<?php

namespace App\Filament\Clusters\Settings\Resources\IndicatorResource\Pages;

use App\Filament\Clusters\Settings\Resources\IndicatorResource;
use App\Models\Indicator;
use App\Models\Tag;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Support\Enums\MaxWidth;

use function Livewire\before;

class ListIndicators extends ListRecords
{
    protected static string $resource = IndicatorResource::class;

    protected array $tags = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function ($data, $livewire) {
                    $livewire->tags = $data['tags'];
                    unset($data['tags']);

                    if (isset($data['const_value'])) {
                        $data['indicator_value'] = $data['const_value'];
                        unset($data['const_value']);
                    } elseif(isset($data['radio_value'])) {
                        $data['indicator_value'] = array_map(fn ($el) => $el['radio'], $data['radio_value']);
                        unset($data['radio_value']);
                    }

                    return $data;
                })
                ->after(function (Indicator $record, $livewire) {
                    foreach ($livewire->tags as $tag) {
                        if (!$id = Tag::query()->where('name', $tag)->first()?->id) {
                            $id = Tag::query()->create(['name' => $tag])->id;
                        }

                        $record->tags()->attach($id);
                    }
                })
                ->modalWidth(MaxWidth::FitContent)
                ->icon('heroicon-s-plus'),
        ];
    }
}
