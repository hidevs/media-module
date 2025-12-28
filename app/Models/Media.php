<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Modules\General\Contracts\Trait\WithUuidColumn;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use WithUuidColumn;

    public function model(): MorphTo
    {
        $ownerKey = null;

        if (! empty($this->model_type)) {
            $modelInstance = new $this->model_type;
            $ownerKey = method_exists($modelInstance, 'getMediaKeyName') ? $modelInstance->getMediaKeyName() : $modelInstance->getKeyName();
        }

        return $this->morphTo('model', 'model_type', 'model_id', $ownerKey);
    }

    public function getConversionsLinks(array $conversionsName = [], bool $only = true): array
    {
        $result = [];
        foreach ($this->getGeneratedConversions()->keys()->toArray() as $conversion) {
            $result[$conversion] = $this->getFullUrl($conversion);
        }
        if (count($conversionsName)) {
            return $only
                ? Arr::only($result, $conversionsName)
                : Arr::except($result, $conversionsName);
        }
        //        $result['original'] = $this->getUrl();

        return $result;
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public static function scopeGetDynamic(Builder $query, string $model, ?string $key, ?array $ids, ?array $collections): void
    {
        $query->where('model_type', $model);

        if (! is_null($ids) && ! is_null($key)) {
            $ids = $model::query()->whereIn($key, $ids)->pluck((new $model)->getKeyName())->toArray();
            $query->whereIn('model_id', $ids);
        }

        if (! is_null($collections)) {
            $query->whereIn('collection_name', $collections);
        }
    }
}
