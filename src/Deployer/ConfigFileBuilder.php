<?php

namespace HairyLemonLtd\Deployer;

use Illuminate\Support\Arr;
use Lorisleiva\LaravelDeployer\ConfigFileBuilder As LaravelDeployerConfigFileBuilder;
use HairyLemonLtd\Deployer\ConfigFile;

class ConfigFileBuilder extends LaravelDeployerConfigFileBuilder
{
    const DEFAULT_PHP_VERSION = '7.3';

    //change back to protected
    public $configs = [
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

    public function getHostname()
    {
        return array_search(head($this->configs['hosts']), $this->configs['hosts']);
    }

    /**
     * Update the host configurations with the given key/value pair.
     *
     * @return self
     */
    public function setHost($key, $value)
    {
        $hostname = $this->getHostname();


        echo " [$key, $value] hostname: " . $hostname."\n";
        echo " head(\$this->configs['hosts']): " . print_r(head($this->configs['hosts']), true) ."\n";

        if ($key !== 'name') {
            $this->configs['hosts'][$hostname][$key] = $value;

            return $this;
        }

        if ($hostname === $value) {
            return $this;
        }

        $this->configs['hosts'][$value] = $this->configs['hosts'][$hostname];
        unset($this->configs['hosts'][$hostname]);
        $this->setHost('deploy_path', "/var/www/$value");

        return $this;
    }
}
