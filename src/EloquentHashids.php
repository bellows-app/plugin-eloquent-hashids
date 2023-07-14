<?php

namespace Bellows\Plugins;

use Bellows\PluginSdk\Contracts\Deployable;
use Bellows\PluginSdk\Contracts\Installable;
use Bellows\PluginSdk\Facades\Deployment;
use Bellows\PluginSdk\Plugin;
use Bellows\PluginSdk\PluginResults\CanBeDeployed;
use Bellows\PluginSdk\PluginResults\CanBeInstalled;
use Bellows\PluginSdk\PluginResults\DeploymentResult;
use Bellows\PluginSdk\PluginResults\InstallationResult;
use Illuminate\Support\Str;

class EloquentHashids extends Plugin implements Deployable, Installable
{
    use CanBeDeployed, CanBeInstalled;

    public function deploy(): ?DeploymentResult
    {
        return DeploymentResult::create()->environmentVariables($this->environmentVariables());
    }

    public function install(): ?InstallationResult
    {
        return InstallationResult::create()
            ->environmentVariables($this->environmentVariables())
            ->publishProvider('Vinkla\Hashids\HashidsServiceProvider')
            ->updateConfigs([
                'hashids.connections.main.length' => 10,
                'hashids.connections.main.salt'   => "env('HASH_IDS_SALT')",
            ]);
    }

    public function requiredComposerPackages(): array
    {
        return [
            'mtvs/eloquent-hashids',
        ];
    }

    public function shouldDeploy(): bool
    {
        return !Deployment::site()->env()->has('HASH_IDS_SALT');
    }

    public function environmentVariables(): array
    {
        return [
            'HASH_IDS_SALT' => Str::random(16),
        ];
    }
}
