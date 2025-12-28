<?php

namespace Modules\Media\Http\Resources;

use Illuminate\Http\Request;
use Modules\General\Contracts\Resource\BaseResource;

class MediaResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            '_type' => class_basename($this->resource),
            'uuid' => $this->resource->uuid,
            'model_id' => $this->resource->model_id,
            'model_type' => class_basename($this->resource->model_type),
            //            'name' => $this->resource->name,
            //            'file_name' => $this->resource->file_name,
            //            'disk' => $this->resource->disk,
            'collection_name' => $this->resource->collection_name,
            'mime_type' => $this->resource->mime_type,
            'size' => $this->resource->humanReadableSize,
            'order_column' => $this->resource->order_column,
            //            'conversions_disk' => $this->resource->conversions_disk,
            'original_url' => $this->resource->original_url,
            'conversions' => $this->resource->getConversionsLinks(),
            //            'generated_conversions' => $this->resource->generated_conversions,
            //            'responsive_images' => $this->resource->responsive_images,
            //            'manipulations' => $this->resource->manipulations,
            'custom_properties' => $this->resource->custom_properties,
            'created_at' => $this->resource->created_at->timestamp,
        ];
    }
}
