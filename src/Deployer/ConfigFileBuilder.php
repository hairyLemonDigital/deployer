<?php

namespace HairyLemonLtd\Deployer;

use Illuminate\Support\Arr;
use Lorisleiva\LaravelDeployer\ConfigFileBuilder As LaravelDeployerConfigFileBuilder;
use Lorisleiva\LaravelDeployer\ConfigFile;

class ConfigFileBuilder extends LaravelDeployerConfigFileBuilder
{
    const DEFAULT_PHP_VERSION = '7.3';

    protected $configs = [
        'default' => 'basic',
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
        ],
        'hosts' => [
            'example.com' => [
                'deploy_path' => '/var/www/example.com',
                'user' => 'root',
            ]
        ],
        'localhost' => [],
        'include' => [],
        'custom_deployer_file' => false,
    ];


    public function __construct()
    {
        parent::__construct(); // loads config above
    }

}
