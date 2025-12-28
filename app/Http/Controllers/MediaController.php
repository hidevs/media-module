<?php

namespace Modules\Media\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Modules\General\Contracts\Controller\BaseController;
use Modules\Media\Http\Requests\MediaStoreRequest;
use Modules\Media\Http\Resources\MediaTempResource;
use Modules\Media\Repositories\MediaRepository;

class MediaController extends BaseController
{
    public function __construct(private readonly MediaRepository $mediaRepository) {}

    public function store(MediaStoreRequest $request)
    {
        $files = array_map(fn (UploadedFile $file) => $this->mediaRepository->upload($file), $request->file('files'));

        return MediaTempResource::collection($files);
    }
}
