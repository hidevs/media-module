<?php

namespace Modules\Media\Http\Requests;

use Modules\General\Contracts\Request\BaseRequest;
use Modules\Media\Enums\EnumFileType;

class MediaStoreRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimetypes:'.EnumFileType::ALL->mimeTypes()->implode(',')],
        ];
    }
}
