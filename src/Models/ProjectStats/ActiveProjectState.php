<?php

namespace Joespace\Projects\Models\ProjectStats;

class ActiveProjectState extends ProjectStats
{
    public static $name = 'active';

    public function Color(): string
    {
        return "success";
    }
}