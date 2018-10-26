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
    protected $description = 'Setup Titan in freshly installed Laravel project.';

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

    /**
     * Execute the command
     */
    public function handle()
    {
        // php artisan migrate
        $this->call('migrate');

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

        // replace app/User.php (rename namespace)
        $stubsPath = $this->basePath . "stubs{$this->ds}";
        $stub = $this->filesystem->get("{$stubsPath}User.stub");
        $this->filesystem->put(app_path() . "{$this->ds}User.php", $stub);
        $this->info('app\User.php was updated');

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
