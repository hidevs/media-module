<?php

namespace Modules\Media\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PruneTempMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $expiredAt = 'now - 2 hours') {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $disk = Storage::disk('tmp');
        foreach ($disk->allFiles() as $disk_path) {
            $filepath = $disk->path($disk_path);
            if (Carbon::parse($this->expiredAt)->isAfter(Carbon::parse(filectime($filepath))->toDateTime())) {
                $disk->delete($disk_path);
            }
        }
    }
}
