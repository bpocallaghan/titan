<?php

namespace Bpocallaghan\Titan\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\SplFileInfo;

class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'titan:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy the [type] related files to your laravel app.';

    /**
     * @var Filesystem
     */
    private $filesystem;

    private $appPath;

    private $basePath;

    // directory separator
    private $ds;

    private $appNamespace = "namespace App";

    private $baseNamespace = "namespace Bpocallaghan\Titan";

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
        $filesToPublish = $this->option('files');

        //dump($filesToPublish);

        switch ($filesToPublish) {
            case 'app':
                $this->copyApp();
                break;
            case 'assets':
                $this->copyAssets();
                break;
            case 'database':
                $this->copyDatabase();
                break;
            case 'events':
                $this->copyEventsAndNotifications();
                break;
            case 'helpers':
                $this->copyHelpers();
                break;
            case 'public':
                $this->copyPublic();
                break;
            case 'routes':
                $this->copyRoutesAndProvider();
                break;
            case 'website':
                $this->copyAllWebsiteFiles();
                break;

            // COPY COMPONENTS
            case 'newsletter':
                $this->copyNewsletter();
                break;

            // COPY AUTH FILES
            case 'auth':
                $this->copyAuthFiles();
                break;
        }
    }

    /**
     * Cope all front end related files
     * Website routes, controllers, views, assets, webpack
     */
    private function copyAllWebsiteFiles()
    {
        // routes

        // VIEWS
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}website";
        $destination = resource_path("views{$this->ds}website");
        $this->copyFilesFromSource($source, $destination);

        // WEBSITE master layout
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}layouts{$this->ds}website.blade.php";
        $destination = resource_path("views{$this->ds}layouts");
        $this->copyFilesFromSource($source, $destination);

        // PARTIALS
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}partials";
        $destination = resource_path("views{$this->ds}partials");
        $this->copyFilesFromSource($source, $destination);

        // CONTROLLERS
        $source = "{$this->appPath}Controllers{$this->ds}Website";
        $destination = app_path("Http{$this->ds}Controllers{$this->ds}Website");

        // copy files and replace namespace and views only
        $this->copyFilesFromSource($source, $destination, 'namespace_views');

        // SEEDS
        $search = "{$this->baseNamespace}\Seeds;";
        $source = "{$this->basePath}database{$this->ds}seeds{$this->ds}UsersTableSeeder.php";
        $destination = database_path("seeds");
        $this->copyFilesFromSource($source, $destination, $search, "");

        // EVENTS (todo, admin for now)
        // MAILS (todo, admin for now)
        // NOTIFICATIONS (todo, admin for now)

        // webpack.js, packages.json root files
        $base = $source = $this->basePath . "resources" . $this->ds . "assets_setup" . $this->ds;
        $source = [
            $base . "webpack.mix.js",
            $base . "package.json",
            $base . "package-lock.json",
        ];
        $this->copyFilesFromSource($source, base_path(), false);

        // ASSETS
        $source = "{$this->basePath}resources{$this->ds}assets{$this->ds}";
        $destination = resource_path("assets{$this->ds}");
        // css
        $this->copyFilesFromSource("{$source}css", "{$destination}css", false);
        // fonts
        $this->copyFilesFromSource("{$source}fonts", "{$destination}fonts", false);
        // images
        $this->copyFilesFromSource("{$source}images", "{$destination}images", false);
        // js
        $this->copyFilesFromSource("{$source}js", "{$destination}js", false);
        // sass
        $this->copyFilesFromSource("{$source}sass", "{$destination}sass", false);

        // public/assets
        $this->copyFilesFromSource($this->basePath . 'public', base_path('public'));
    }

    /**
     * Copy the app files
     * Copy all controllers, models and views and routes
     */
    private function copyApp()
    {
        // copy MODELS
        $this->copyFilesFromSource($this->appPath . 'Models', app_path('Models'));

        // copy VIEWS
        // replace
        // @include('titan::
        // @extends('titan::
        // $this->view('titan::

        $source = $this->basePath . "resources" . $this->ds . "views";
        $this->copyFilesFromSource($source, resource_path("views"));

        // copy CONTROLLERS
        $this->copyFilesFromSource($this->appPath . "Controllers",
            app_path("Http{$this->ds}Controllers"));

        // copy RouteServiceProvider
        $this->copyRoutesAndProvider();
    }

    /**
     * Copy the asset files
     * Copy all css, js, images asset files
     */
    public function copyAssets()
    {
        // copy ASSETS
        //$source = $this->basePath . "resources" . $this->ds . "assets";
        //$this->copyFilesFromSource($source, resource_path("assets"));

        // copy WEBPACK.js
        $base = $source = $this->basePath . "resources" . $this->ds . "assets_setup" . $this->ds;
        $source = [
            $base . "webpack.mix.js",
            $base . "package.json",
            $base . "package-lock.json",
        ];
        $this->copyFilesFromSource($source, base_path(), false);
    }

    /**
     * Copy the database files
     * Copy all files in migrations, seeds and seeds/csv
     */
    private function copyDatabase()
    {
        // get all files in database/migrations and database/seeds
        // if one already exist in desired location
        // flag and ask to override or not

        $sourceDatabase = $this->basePath . "database" . $this->ds;
        $destinationMigrations = database_path('migrations');
        $destinationSeeds = database_path('seeds');

        // copy files from source to destination
        $search = "{$this->baseNamespace}\Migrations;";
        $this->copyFilesFromSource($sourceDatabase . 'migrations', $destinationMigrations, $search,
            "");

        $search = "{$this->baseNamespace}\Seeds;";
        $this->copyFilesFromSource($sourceDatabase . 'seeds', $destinationSeeds, $search, "");
    }

    /**
     * Copy the events files
     * Copy all events, listeners and notifications
     */
    public function copyEventsAndNotifications()
    {
        // copy EVENTS
        $this->copyFilesFromSource($this->appPath . 'Events', app_path('Events'));

        // copy LISTENERS
        $this->copyFilesFromSource($this->appPath . 'Listeners', app_path('Listeners'));

        // copy Mail
        $this->copyFilesFromSource($this->appPath . 'Mail', app_path('Mail'));

        // copy Notifications
        $this->copyFilesFromSource($this->appPath . 'Events', app_path('Notifications'));

        // copy EventServiceProvider
        $source = $this->appPath . "Providers{$this->ds}EventServiceProvider.php";
        $this->copyFilesFromSource($source, app_path('Providers'));
    }

    /**
     * Cope the helpers files
     */
    private function copyHelpers()
    {
        // copy Notifications
        $this->copyFilesFromSource($this->appPath . 'Helpers', app_path('Helpers'));

        // copy HelperServiceProvider
        $source = $this->appPath . "Providers{$this->ds}HelperServiceProvider.php";
        $this->copyFilesFromSource($source, app_path('Providers'));

        $this->line("Remember to add 'App\Providers\HelperServiceProvider::class,' in your 'config/app.php' in 'providers'");
    }

    /**
     * Copy the public files
     */
    private function copyPublic()
    {
        $this->copyFilesFromSource($this->basePath . 'public', base_path('public'));
    }

    /**
     * Copy the route service provider
     * The provider will point to routes in the vendor directory
     */
    private function copyRoutesAndProvider()
    {
        // copy ROUTES
        $this->copyFilesFromSource($this->basePath . "routes", base_path("routes"));

        // copy RouteServiceProvider
        $source = $this->appPath . "Providers{$this->ds}RouteServiceProvider.php";
        $this->copyFilesFromSource($source, app_path('Providers'), "namespace Bpocallaghan\Titan", "namespace App");
    }

    /**
     * Copy all newsletter related files to application
     */
    private function copyNewsletter()
    {
        // models
        $source =  "{$this->appPath}Models{$this->ds}NewsletterSubscriber.php";
        $this->copyFilesFromSource($source, app_path("Models"), 'namespace_views');

        // controllers
        $destination = app_path("Http{$this->ds}Controllers{$this->ds}Admin{$this->ds}Newsletter");
        $source = "{$this->appPath}Controllers{$this->ds}Admin{$this->ds}Newsletter{$this->ds}SubscribersController.php";
        $this->copyFilesFromSource($source, $destination, 'namespace_views');

        // views
        $destination = base_path("resources{$this->ds}views{$this->ds}admin{$this->ds}newsletters");
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}admin{$this->ds}newsletters";
        $this->copyFilesFromSource($source, $destination);

        // migrations
        $source = "{$this->basePath}database{$this->ds}migrations{$this->ds}2017_10_01_181435_create_newsletter_subscribers_table.php";
        $this->copyFilesFromSource($source, database_path("migrations"));

    }

    /**
     * Copy all auth related files to application
     */
    private function copyAuthFiles()
    {

        // controllers
        $this->copyFilesFromSource($this->appPath . "Controllers{$this->ds}Auth",
            app_path("Http{$this->ds}Controllers{$this->ds}Auth"));

        // views
        $destination = base_path("resources{$this->ds}views{$this->ds}auth");
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}auth";
        $this->copyFilesFromSource($source, $destination);
    }

    /**
     * Copy files from the source to destination
     * @param        $source
     * @param        $destination
     * @param string $search
     * @param string $replace
     * @param bool   $allFolders
     */
    private function copyFilesFromSource($source, $destination, $search = true, $replace = true, $allFolders = true)
    {
        // search and replace the default
        if($search === true && $replace === true) {
            $search = [
                'Bpocallaghan\Titan',
                "('titan::"
            ];
            $replace = [
                "App",
                "('"
            ];
        }

        // search and replace namespace prefix and views prefix
        if($search === 'namespace_views' && $replace === true) {
            $search = [
                'namespace Bpocallaghan\Titan',
                "('titan::"
            ];
            $replace = [
                "namespace App",
                "('"
            ];
        }
        
        // destination
        $destination = $this->formatFilePath($destination . $this->ds);

        // is source array
        if (is_array($source)) {
            // if one file
            $files = collect();
            $sources = $source;
            foreach ($sources as $k => $path) {
                // update source
                $pos = strrpos($path, $this->ds, -2) + 1;
                $source = substr($path, 0, $pos);
                $files->push(new SplFileInfo($path, $source, $path));
            }
        }
        // is source a file
        elseif ($this->filesystem->isFile($source)) {
            $files = collect([new SplFileInfo($source, $source, $source)]);
            // update source
            $pos = strrpos($source, $this->ds, -2) + 1;
            $source = substr($source, 0, $pos);
        }
        else {
            // source is directory path
            $source = $this->formatFilePath($source . $this->ds);

            // include sub folders
            if (!$allFolders) {
                $files = collect($this->filesystem->files($source));
            }
            else {
                $files = collect($this->filesystem->allFiles($source));
            }
        }

        $this->line("Destination: {$destination}");

        // can we override the existing files or not
        $override = $this->overrideExistingFiles($files, $source, $destination);

        // loop through all files and copy file to destination
        $files->map(function (SplFileInfo $file) use ($source, $destination, $override, $search, $replace) {

            $subDirectories = '';
            $fileSource = $file->getRealPath();
            //$fileSource = $source . $file->getFilename();
            //$fileSource = $file->getPath() . $this->ds . $file->getFilename();
            $fileDestination = $destination . $file->getFilename();

            // if file is in subdirectory - update destination
            if ($source != $file->getPath() . $this->ds) {
                $subDirectories = str_replace($source, "", $file->getPath() . $this->ds);
                $fileDestination = $destination . $subDirectories . $file->getFilename();
            }

            //dump("$fileSource");
            if (!$this->filesystem->exists($fileSource)) {
                dump("file does not exist? " . $fileSource);

                return;
            }

            // if not exist or if we can override the files
            if ($this->filesystem->exists($fileDestination) == false || $override == true) {

                // make all the directories
                $this->makeDirectory($fileDestination);

                // replace file namespace
                $stub = $this->filesystem->get($fileSource);
                if (is_string($search)) {
                    $stub = str_replace($search, $replace, $stub);
                }
                else if (is_array($search)) {
                    foreach ($search as $k => $value) {
                        $stub = str_replace($value, $replace[$k], $stub);
                    }
                }

                // save modified file to destination
                $this->filesystem->put($fileDestination, $stub);

                // copy (old)
                //$this->filesystem->copy($fileSource, $fileDestination);

                $this->info("File copied: {$subDirectories}{$file->getFilename()}");
            }
            //dump($file->getFilename());
        });
    }

    /**
     * See if any files exist
     * Ask to override or not
     * @param Collection $files
     * @param            $source
     * @param            $destination
     * @return bool
     */
    private function overrideExistingFiles(Collection $files, $source, $destination)
    {
        $answer = true;
        $filesFound = [];
        // map over to see if same filename already exist in destination
        $files->map(function (SplFileInfo $file) use ($source, $destination, &$filesFound) {

            $subDirectories = '';
            $fileDestination = $destination . $file->getFilename();

            // if file is in subdirectory - update destination path
            if ($source != $file->getPath() . $this->ds) {
                $subDirectories = str_replace($source, "", $file->getPath() . $this->ds);
                $fileDestination = $destination . $subDirectories . $file->getFilename();
            }

            // if file exist in destination
            if ($this->filesystem->exists($fileDestination)) {
                $filesFound [] = $subDirectories . $file->getFilename();
            }
        });

        // if files found
        if (count($filesFound) >= 1) {
            collect($filesFound)->each(function ($file) {
                $this->info(" - {$file}");
            });

            //$this->info("Destination: " . $destination);
            $answer = $this->confirm("Above is a list of the files that already exist. Override all files?");
        }

        return $answer;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Replace the default directory seperator with the
     * computer's directory seperator (windows, mac, linux)
     * @param $path
     * @return mixed
     */
    private function formatFilePath($path)
    {
        return str_replace('\\', $this->ds, $path);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'files',
                null,
                InputOption::VALUE_OPTIONAL,
                'Which files must be published (database, app, assets, views)?',
                'all'
            ],
        ];
    }
}
