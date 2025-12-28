<?php

namespace Modules\Media\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\General\Contracts\Repository\BaseApiRepository;
use Modules\Media\Models\Media;
use Prettus\Repository\Traits\CacheableRepository;

class MediaRepository extends BaseApiRepository
{
    use CacheableRepository;

    protected $fieldSearchable = [
        'model_type' => 'in',
        'model_id' => 'in',
        'uuid' => 'in',
        'collection_name' => 'in',
        'mime_type' => 'in',
    ];

    public function model()
    {
        return config('media-library.media_model', Media::class);
    }

    public function filename(UploadedFile $file): string
    {
        return auth()->id().uniqid(now()->format('-Ymd-His-')).".{$file->extension()}";
    }

    public function upload(UploadedFile $file): array
    {
        Storage::disk('tmp')->put($filename = $this->filename($file), $file->getContent());

        return [
            'id' => $filename,
            'url' => Storage::disk('tmp')->url($filename),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'created_at' => now(),
        ];
    }
}
