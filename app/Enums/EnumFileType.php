<?php

namespace Modules\Media\Enums;

use Illuminate\Support\Collection;
use Modules\General\Contracts\Enum\EnumMethods;

enum EnumFileType: string
{
    use EnumMethods;

    case ALL = 'ALL';
    case IMAGE = 'IMAGE';
    case VIDEO = 'VIDEO';
    case AUDIO = 'AUDIO';
    case DOCUMENT = 'DOCUMENT';
    case EXCEL = 'EXCEL';
    case EPUB = 'EPUB';
    case ZIP = 'ZIP';

    public function mimeTypes(): Collection
    {
        return match ($this) {
            self::IMAGE => collect([
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/webp',
                'image/svg+xml',
            ]),
            self::VIDEO => collect([
                'video/mp4',
                'video/ogg',
                'video/webm',
            ]),
            self::AUDIO => collect([
                'audio/mpeg',
                'audio/aac',
                'audio/ogg',
                'audio/webm',
            ]),
            self::DOCUMENT => collect([
                'application/pdf',
            ]),
            self::EXCEL => collect([
                'text/csv',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]),
            self::EPUB => collect([
                'application/epub+zip',
            ]),
            self::ZIP => collect([
                'application/zip',
            ]),
            self::ALL => collect(self::cases())
                ->filter(fn (self $case) => $case->name !== self::ALL->name)
                ->map(fn (self $case) => $case->mimeTypes()->toArray())
                ->flatten(),
        };
    }
}
