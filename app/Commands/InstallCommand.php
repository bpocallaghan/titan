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
    protected $description = 'Setup Titan in a freshly installed Laravel project.';

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
        // php artisan migrate
        $this->call('migrate');

        // replace app/User.php (rename namespace)
        // User.php needs to be updated before db:seed (user.php helpers)
        $stubsPath = $this->basePath . "stubs{$this->ds}";
        $stub = $this->filesystem->get("{$stubsPath}User.stub");
        $this->filesystem->put(app_path() . "{$this->ds}User.php", $stub);
        $this->info('app\User.php was updated');

        // php artisan titan:db:seed
        $this->call('titan:db:seed');

        // php artisan titan:publish --files=public
        $this->call('titan:publish', ['--files' => 'public']);

        $stubsPath = $this->basePath . "stubs{$this->ds}";

        // update routes/web.php
        $stub = $this->filesystem->get("{$stubsPath}web.stub");
        $this->filesystem->put(base_path() . "{$this->ds}routes{$this->ds}web.php", $stub);
        $this->info('routes\web.php was updated');

        // update app/Http/Kernel.php - add middlewares
        $stub = $this->filesystem->get("{$stubsPath}Kernel.stub");
        $this->filesystem->put(app_path() . "{$this->ds}Http{$this->ds}Kernel.php", $stub);
        $this->info('app\Http\Kernel.php was updated');

        // update app/Exceptions/Handler.php
        $stub = $this->filesystem->get("{$stubsPath}Handler.stub");
        $this->filesystem->put(app_path() . "{$this->ds}Exceptions{$this->ds}Handler.php", $stub);
        $this->info('app\Exceptions\Handler.php was updated');

        // update config/app.php
        $path = base_path() . "{$this->ds}config{$this->ds}app.php";
        $stub = $this->filesystem->get($path);
        $stub = str_replace('return [', "return [
        
    'description' => env('APP_DESCRIPTION', 'App Description'),
    'author'      => env('APP_AUTHOR', 'App Author'),
    'keywords'    => env('APP_KEYWORDS', 'laravel'),

    'facebook_id'          => env('FACEBOOK_APP_ID', ''),
    'recaptcha_public_key' => env('RECAPTCHA_PUBLIC_KEY', ''),
    'google_analytics'     => env('GOOGLE_ANALYTICS', ''),
    'google_map_key'       => env('GOOGLE_MAP_KEY', ''),", $stub);
        $this->filesystem->put($path, $stub);
        $this->info('config\app.php was updated');

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

        $this->line("User Credentials");
        $this->info("Email: admin@laravel.local");
        $this->info("Password: admin");

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
