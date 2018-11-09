<?php

namespace Bpocallaghan\Titan\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\SplFileInfo;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'titan:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Titan in a freshly installed Laravel project.';

    /**
     * @var Filesystem
     */
    private $filesystem;

    private $appPath;

    private $basePath;

    private $ds;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->ds = DIRECTORY_SEPARATOR;
        $this->filesystem = $filesystem;

        $this->basePath = __DIR__ . $this->ds . '..' . $this->ds . '..' . $this->ds;
        $this->appPath = $this->basePath . "app" . $this->ds;
    }

    private function updateDotEnv($question, $prefix, $default = '')
    {
        $answer = $this->ask($question);
        if(!is_null($answer)) {

            $answer = trim($answer);
            $quotes = boolval(strpos($answer, " ")) ? '"':'';

            $search = "{$prefix}={$default}";
            $replace = "{$prefix}={$quotes}{$answer}{$quotes}";

            // update .env
            $path = base_path() . "{$this->ds}.env";
            $stub = $this->filesystem->get($path);
            $stub = str_replace($search, $replace, $stub);
            $this->filesystem->put($path, $stub);
        }
    }

    /**
     * Execute the command
     */
    public function handle()
    {
        $this->line('The following will update your .env file.');

        // add the extra environment variables to .env
        $path = base_path() . "{$this->ds}.env";
        $stub = $this->filesystem->get($path);
        $stub = str_replace("APP_NAME=Laravel", "APP_AUTHOR=
APP_DESCRIPTION=
APP_KEYWORDS=

ANALYTICS_VIEW_ID=
GOOGLE_ANALYTICS=
FACEBOOK_APP_ID=
GOOGLE_MAP_KEY=
RECAPTCHA_PUBLIC_KEY=
RECAPTCHA_PRIVATE_KEY=

APP_NAME=Laravel", $stub);
        $this->filesystem->put($path, $stub);

        // prompt to update .env
        $this->updateDotEnv("What is your APP_NAME?", "APP_NAME", "Laravel");
        $this->updateDotEnv("What is your APP_DESCRIPTION?", "APP_DESCRIPTION");
        $this->updateDotEnv("What is your APP_KEYWORDS?", "APP_KEYWORDS");
        $this->updateDotEnv("What is your APP_AUTHOR?", "APP_AUTHOR");
        $this->updateDotEnv("What is your APP_URL?", "APP_URL");

        $this->info('.env was updated. (Extra environment variables were addted at the top)');

        // php artisan migrate
        $this->call('migrate');

        // php artisan db:seed
        $this->call('db:seed');

        // php artisan titan:db:seed
        $this->call('titan:db:seed');

        $this->alert('Installation complete.');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
