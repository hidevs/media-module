<?php

namespace Modules\Media\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Enums\EnumFileType;

class TempMediaRule implements ValidationRule
{
    /**
     * @var EnumFileType[][]
     */
    private array $types;

    /**
     * @param  EnumFileType  ...$types
     */
    public function __construct(...$types)
    {
        $this->types = $types;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail(__('validation.string', ['attribute' => $attribute]));
        }

        if (! Storage::disk('tmp')->exists($value)) {
            $fail(__(':attribute file not found.'));
        }

        $typeAllowed = false;

        /** @var EnumFileType $type */
        foreach ($this->types as $type) {
            if ($type->mimeTypes()->contains(Storage::disk('tmp')->mimeType($value))) {
                $typeAllowed = true;
                break;
            }
        }

        if (! $typeAllowed) {
            $fail(__(':attribute file type is not allowed.'));
        }
    }
}
