<?php

namespace HairyLemonLtd\Deployer\Commands;

use Lorisleiva\LaravelDeployer\Commands\BaseCommand;
use HairyLemonLtd\Deployer\ConfigFileBuilder;

class DeployInit extends BaseCommand
{
    protected $builder;

    protected $signature = "deploy:init
        {hostname? : The hostname of the deployment server}
    ";
    
    protected $useDeployerOptions = false;
    protected $description = '**hairyLemon Deployer**  Generate a deploy.php configuration file';

    public function __construct(ConfigFileBuilder $builder)
    {
        parent::__construct();
        $this->builder = $builder;
    }

    public function handle()
    {
        if ($this->configFileExists()) {
            return;
        }
        
        $this->configureBuilder();
        $this->builder->build()->store();
    }

    public function configFileExists()
    {
        $filepath = base_path('config' . DIRECTORY_SEPARATOR . 'deploy.php');

        return file_exists($filepath)
            && ! $this->confirm("<fg=red;options=bold>A configuration file already exists.</>\nAre you sure you want to continue and override it?");
    }

    public function configureBuilder()
    {

        $this->welcomeMessage('🚀',  'Let\'s configure your hairyLemon deployment!');
        $this->defineRepositoryUrl();
        $this->defineHostname();
        $this->defineDeployementPath();
        $this->defineAdditionalHooks();
    }

    public function welcomeMessage($emoji, $message)
    {
        if (! $this->input->isInteractive()) {
            return;
        }

        $this->output->newLine();
        $this->comment(str_repeat('*', strlen($message) + 15));
        $this->comment("*     $emoji  $message     *");
        $this->comment(str_repeat('*', strlen($message) + 15));
        $this->output->newLine();
    }

    public function defineRepositoryUrl()
    {
        $repository = $this->ask(
            'Repository URL', 
            $this->builder->get('options.repository')
        );

        $this->builder->set('options.repository', $repository);
    }

    public function defineHostname()
    {
        if (! $hostname = $this->argument('hostname')) {
            $hostname = $this->ask(
                'Hostname of your deployment server', 
                $this->builder->getHostname()
            );
        }

        $user = $this->ask(
            'Username for ' .$hostname
        );

        $type = $this->ask('Site type? ' . ' [ dev | production ]');
        $webroot = $this->ask( 'Webroot? ' . ' [ ie: {site-name} ]');

        $this->builder->setHost('name', $hostname);
        $this->builder->setHost('user', $user);
        $this->builder->setHost('type', $type);
        $this->builder->setHost('webroot', $webroot);
    }

    public function askPhpVersion()
    {
        return $this->ask(
            'Which php version are you using? (format: #.#)', 
            ConfigFileBuilder::DEFAULT_PHP_VERSION
        );
    }

    public function defineDeployementPath()
    {
        $ds = DIRECTORY_SEPARATOR;

        $path = $this->ask(
            'Deployment path (absolute to the server)', 
            $this->builder->getHost('deploy_path')
            .$ds. $this->builder->getHost('user')
            .$ds. $this->builder->getHost('type')
            .$ds. $this->builder->getHost('webroot')
        );

        $this->builder->setHost('deploy_path', $path);
    }

    public function defineAdditionalHooks()
    {
        $npm = $this->choice(
            'Do you want to compile your asset during deployment with npm/yarn?',
            [
                'No',
                'Yes using `npm run production`',
                'Yes using `yarn production`',
            ], 1
        );
        
        if ($npm !== 'No') {
            $manager = $npm === 'Yes using `npm run production`' ? 'npm' : 'yarn';
            $this->builder->add('hooks.build', "$manager:install");
            $this->builder->add('hooks.build', "$manager:production");
        }

        if ($this->confirm('Do you want to migrate during deployment?', true)) {
            $this->builder->add('hooks.ready', 'artisan:migrate');
        }

        if ($this->confirm('Do you want to terminate horizon after each deployment?')) {
            $this->builder->add('hooks.ready', 'artisan:horizon:terminate');
        }
    }
}
