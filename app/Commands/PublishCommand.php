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
    use CopyFilesHelpers;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'titan:publish';

    protected $signature = 'titan:publish {--files=}';

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
            case 'config':
                $this->copyConfig();
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

            // COPY PAGES FILES
            case 'pages':
                $this->copyPagesFiles();
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

        // databaseseeder
        $search = "{$this->baseNamespace}\Migrations;";
        $source = "{$this->basePath}database{$this->ds}migrations{$this->ds}DatabaseSeeder.php";
        $destination = database_path("seeds");
        $this->copyFilesFromSource($source, $destination, $search, "");

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
     * Copy the config file
     */
    private function copyConfig()
    {
        // copy config
        $this->filesystem->copy($this->basePath . "config/config.php", config_path('titan.php'));
        $this->line("Config Copied: " . config_path('titan.php'));
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
        $this->copyFilesFromSource($this->appPath . 'Notifications', app_path('Notifications'));

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
        //events
        $source =  "{$this->appPath}Events{$this->ds}UserRegistered.php";
        $this->copyFilesFromSource($source, app_path("Events"), 'namespace_views');

        //notifications
        $source =  "{$this->appPath}Notifications{$this->ds}UserConfirmedAccount.php";
        $this->copyFilesFromSource($source, app_path("Notifications"), 'namespace_views');

        // models
        $source =  "{$this->appPath}Models{$this->ds}UserInvite.php";
        $this->copyFilesFromSource($source, app_path("Models"), 'namespace_views');

        $source =  "{$this->appPath}Models{$this->ds}LogLogin.php";
        $this->copyFilesFromSource($source, app_path("Models"), 'namespace_views');

        // controllers
        $this->copyFilesFromSource($this->appPath . "Controllers{$this->ds}Auth",
            app_path("Http{$this->ds}Controllers{$this->ds}Auth"));

        // views
        $destination = base_path("resources{$this->ds}views{$this->ds}auth");
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}auth";
        $this->copyFilesFromSource($source, $destination);
    }

    /**
     * Copy all page related files to application
     */
    private function copyPagesFiles()
    {
        // models
        $source =  "{$this->appPath}Models{$this->ds}Page.php";
        $this->copyFilesFromSource($source, app_path("Models"));
        $source =  "{$this->appPath}Models{$this->ds}PageContent.php";
        $this->copyFilesFromSource($source, app_path("Models"));
        $source =  "{$this->appPath}Models{$this->ds}Traits{$this->ds}PageHelper.php";
        $destination = app_path("Models{$this->ds}Traits{$this->ds}PageHelper.php");
        $this->copyFilesFromSource($source, app_path("Models"));

        // controllers
        $destination = app_path("Http{$this->ds}Controllers{$this->ds}Admin{$this->ds}Pages");
        $source = "{$this->appPath}Controllers{$this->ds}Admin{$this->ds}Pages";
        $this->copyFilesFromSource($source, $destination, 'namespace_views');

        // views
        $destination = base_path("resources{$this->ds}views{$this->ds}admin{$this->ds}pages");
        $source = "{$this->basePath}resources{$this->ds}views{$this->ds}admin{$this->ds}pages";
        $this->copyFilesFromSource($source, $destination);

        // migrations
        $source = "{$this->basePath}database{$this->ds}migrations{$this->ds}2017_09_26_154748_create_pages_table.php";
        $this->copyFilesFromSource($source, database_path("migrations"));
        $source = "{$this->basePath}database{$this->ds}migrations{$this->ds}2017_09_28_184930_create_banner_page_pivot_table.php";
        $this->copyFilesFromSource($source, database_path("migrations"));
        $source = "{$this->basePath}database{$this->ds}migrations{$this->ds}2017_10_10_123309_create_page_content_table.php";
        $this->copyFilesFromSource($source, database_path("migrations"));
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
