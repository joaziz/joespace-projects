<?php

namespace Joespace\Projects;

use Filament\Panel;
use Joespace\Core\Contracts\Filament\ActivePluginInterface;
use Joespace\Core\Contracts\PermissionFactoryInterface;
use Joespace\Core\Support\ACL\PermissionFactory;
use Joespace\Projects\Filament\Resources\ProjectResource;

class ProjectsPlugin implements ActivePluginInterface
{

    public function GetPermissionsLookup(): PermissionFactoryInterface
    {
        return PermissionFactory::Make();
    }

    public function getId(): string
    {
        return "project";
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ProjectResource::class
        ]);
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}