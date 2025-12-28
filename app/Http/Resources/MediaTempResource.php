<?php

namespace Modules\Media\Http\Resources;

use Illuminate\Http\Request;
use Modules\General\Contracts\Resource\BaseResource;

class MediaTempResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            '_type' => 'TempMedia',
            'id' => $this->resource['id'],
            'url' => $this->resource['url'],
            'mime_type' => $this->resource['mime_type'],
            'size' => human_filesize($this->resource['size']),
            'created_at' => $this->resource['created_at']->timestamp,
        ];
    }
}
