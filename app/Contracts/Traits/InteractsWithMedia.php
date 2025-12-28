<?php

namespace Modules\Media\Contracts\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Modules\Media\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia as BaseInteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait InteractsWithMedia
{
    use BaseInteractsWithMedia;

    public function getMediaKeyName(): string
    {
        return $this->getKeyName();
    }

    public function media(): MorphMany
    {
        $instance = $this->newRelatedInstance($this->getMediaModel());

        [$type, $id] = $this->getMorphs('model', null, null);

        $table = $instance->getTable();

        return $this->newMorphMany(
            $instance->newQuery(),
            $this,
            $table.'.'.$type,
            $table.'.'.$id,
            $this->getMediaKeyName()
        );
    }

    public function setMediaAttribute($collectionName, string|UploadedFile $file): void
    {
        throw_if(
            ! $this->getRegisteredMediaCollections()->pluck('name')->contains($collectionName),
            new \Exception("Collection '{$collectionName}' does not registered.")
        );

        $this->addMediaFromFileOrPath($file, $collectionName);
    }

    public function addMediaFromTmp(string $path, string $collectionName = 'default', bool $clearCollection = true): Media
    {
        $extension = $this->makeFilename(extension_from_path($path));

        $media = $this->addMediaFromDisk($path, 'tmp')
            ->setFileName($extension)
            ->setName($extension)
            ->toMediaCollection($collectionName);

        if ($clearCollection) {
            $this->clearMediaCollectionExcept($collectionName, $media);
        }

        return $media;
    }

    public function uniquePath(): string
    {
        return md5($this->getKey().config('app.key'));
    }

    public function makeFilename(string $extension): string
    {
        return Str::orderedUuid()->toString()."{$extension}";
    }

    public function addMediaFromFileOrPath(string|UploadedFile $file, string $collectionName = 'default'): ?Media
    {
        $extension = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : extension_from_path($file);
        $filename = $this->makeFilename($extension);

        if (is_string($file) && (str_starts_with($file, 'http://') || str_starts_with($file, 'https://'))) {
            return $this->addMediaFromUrl($file)
                ->setFileName($filename)
                ->setName($filename)
                ->preservingOriginal()
                ->toMediaCollection($collectionName);
        }

        return $this->addMedia($file)
            ->setFileName($filename)
            ->setName($filename)
            ->preservingOriginal()
            ->toMediaCollection($collectionName);
    }
}
