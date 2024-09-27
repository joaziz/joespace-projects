<?php

namespace Joespace\Projects\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Joespace\Projects\Models\ProjectStats\ProjectStats;
use Spatie\ModelStates\HasStates;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

/**
 * @property ProjectStats $state
 * @property SchemalessAttributes $options
 */
class Projects extends Model
{
    use HasFactory;
    use HasStates;

    protected $guarded = [];
    protected $casts = [
        'state' => ProjectStats::class,
        'options' => SchemalessAttributes::class,

    ];

    public function scopeWithOptions(): Builder
    {
        return $this->options->modelScope();
    }

    public function UpdateBuildAndGet(): int
    {
        $build = ((int)$this->options->build) + 1;
        $this->options->build = $build;
        $this->save();
        return $build;
    }

    public function UpdateVersionAndGet(string $version_type): string
    {
//        $parts = ["Major" => 0, "Minor" => 1, "Patch" => 2];

        $version = $this->options->version;
        if (!$version)
            $version = "0.0.0";

        $versionParts = explode(".", $version);
//        $versionParts[$parts[$version_type]] = ((int)$versionParts[$parts[$version_type]]) + 1;

        if ($version_type === "Major") {
            $versionParts[0] = ((int)$versionParts[0]) + 1;
            $versionParts[1] = 0;
            $versionParts[2] = 0;
        }

        if ($version_type === "Minor") {
            $versionParts[1] = ((int)$versionParts[1]) + 1;
            $versionParts[2] = 0;
        }

        if ($version_type === "Patch") {
            $versionParts[2] = ((int)$versionParts[2]) + 1;
        }


        $this->options->version = implode(".", $versionParts);
        $this->save();
        return $this->options->version;
    }
}
