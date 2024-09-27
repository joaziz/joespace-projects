<?php

namespace Joespace\Projects\Models\ProjectStats;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ProjectStats extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(ActiveProjectState::class)//        ->allowTransition()
            ;
    }

    abstract public function Color(): string;
}