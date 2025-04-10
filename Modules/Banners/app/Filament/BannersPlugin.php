<?php

namespace Modules\Banners\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class BannersPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Banners';
    }

    public function getId(): string
    {
        return 'banners';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
