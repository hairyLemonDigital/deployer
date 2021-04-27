<?php

namespace HairyLemonLtd\Deployer;

use Illuminate\Support\Arr;
use Lorisleiva\LaravelDeployer\ConfigFileBuilder As LaravelDeployerConfigFileBuilder;
use HairyLemonLtd\Deployer\ConfigFile;

class ConfigFileBuilder extends LaravelDeployerConfigFileBuilder
{
    const DEFAULT_PHP_VERSION = '7.3';

    protected $configs = [
        'default' => 'normal', // the hairylemon one
        'strategies' => [],
        'hooks' => [
            'start' => [],
            'build' => [],
            'ready' => [],
            'done' => [],
            'success' => [],
            'fail' => [],
            'rollback' => [],
        ],
        'options' => [
            'application' => "env('APP_NAME', 'Laravel')",
            'writable_mode' => 'chmod',
            'shared_dirs'   => [
                'storage',
                'public/assets'
            ],
            'writeable_dirs' => [
                'bootstrap/cache',
                'storage',
                'storage/app',
                'storage/app/public',
                'storage/framework',
                'storage/framework/cache',
                'storage/framework/sessions',
                'storage/framework/views',
                'storage/logs',
            ]
        ],
        'hosts' => [
            'master.cluster.16h.io' => [
                'deploy_path' => '/var/www/clients',
                'user' => 'hairylemon',
                'type'  => 'dev' // production
            ],
            'cigna-control' => [
                'deploy_path' => '/var/www/clients',
                'user'        => 'cigna',
                'type'        => 'dev' // production
            ],
        ],
        'localhost' => [],
        'include' => [],
        'custom_deployer_file' => false,
    ];


    public function __construct()
    {
        parent::__construct(); // loads config above
    }

    /**
     * Build a config file object based on the information
     * collected so far.
     *
     * @return \HairyLemonLtd\Deployer\ConfigFile
     */
    public function build(): \HairyLemonLtd\Deployer\ConfigFile
    {
        return new ConfigFile($this->configs);
    }
}
