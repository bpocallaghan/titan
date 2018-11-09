<?php

namespace Bpocallaghan\Titan\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\SplFileInfo;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'titan:setup';

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

    /**
     * Execute the command
     */
    public function handle()
    {
        // COPY ALL WEBSITE related FILES

        // php artisan titan:publish --files=website
        $this->call('titan:publish', ['--files' => 'website']);

        // MODIFY EXISTING FILES

        // replace app/User.php (rename namespace)
        // User.php needs to be updated before db:seed (user.php helpers)
        $stubsPath = $this->basePath . "stubs{$this->ds}";
        $stub = $this->filesystem->get("{$stubsPath}User.stub");
        $this->filesystem->put(app_path() . "{$this->ds}User.php", $stub);
        $this->info('app\User.php was updated');

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
        
    'description' => env('APP_DESCRIPTION', 'Description'),
    'author'      => env('APP_AUTHOR', 'Author'),
    'keywords'    => env('APP_KEYWORDS', 'laravel'),

    'facebook_id'          => env('FACEBOOK_APP_ID', ''),
    'google_map_key'       => env('GOOGLE_MAP_KEY', ''),
    'google_analytics'     => env('GOOGLE_ANALYTICS', ''),
    'recaptcha_public_key' => env('RECAPTCHA_PUBLIC_KEY', ''),
    'recaptcha_private_key' => env('RECAPTCHA_PRIVATE_KEY', ''),", $stub);
        $this->filesystem->put($path, $stub);
        $this->info('config\app.php was updated');

        $this->alert('Setup complete.');
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
