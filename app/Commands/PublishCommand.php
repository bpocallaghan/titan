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

    private $baseNamespace = "namespace Bpocallaghan\Titan";

    /**
     * @var Filesystem
     */
    private $filesystem;

    private $appPath;

    private $basePath;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;

        $this->basePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $this->appPath = $this->basePath . "app" . DIRECTORY_SEPARATOR;
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
        }
    }

    /**
     * Copy the app files
     * Copy all controllers, models and views
     */
    private function copyApp()
    {
        // to do
        // change namespace for controllers and models
        // update files to namespace App\Models
        $destinationModels = app_path('Models');

        // copy files from source to destination
        $this->copyFilesFromSource($this->appPath . 'Models', $destinationModels);
    }

    /**
     * Copy the asset files
     * Copy all css, js, images asset files
     */
    public function copyAssets()
    {

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

        $sourceDatabase = $this->basePath . "database" . DIRECTORY_SEPARATOR;
        $destinationMigrations = database_path('migrations');
        $destinationSeeds = database_path('seeds');
        $destinationCSV = database_path('seeds' . DIRECTORY_SEPARATOR . 'csv');

        // copy files from source to destination
        $search = "{$this->baseNamespace}\Migrations;";
        $this->copyFilesFromSource($sourceDatabase . 'migrations', $destinationMigrations, $search);

        $search = "{$this->baseNamespace}\Seeds;";
        $this->copyFilesFromSource($sourceDatabase . 'seeds', $destinationSeeds, $search);
        $this->copyFilesFromSource($sourceDatabase . 'seeds' . DIRECTORY_SEPARATOR . 'csv',
            $destinationCSV);
    }

    /**
     * Copy files from the source to destination
     * @param        $source
     * @param        $destination
     * @param bool   $search
     * @param string $replace
     */
    private function copyFilesFromSource($source, $destination, $search = false, $replace = "")
    {
        $source = $this->formatFilePath($source . DIRECTORY_SEPARATOR);
        $destination = $this->formatFilePath($destination . DIRECTORY_SEPARATOR);
        $files = collect($this->filesystem->files($source));

        $this->line("Destination: {$destination}");

        // can we override the existing files or not
        $override = $this->overrideExistingFiles($files, $destination);

        // loop through all files and copy file to destination
        $files->map(function (SplFileInfo $file) use ($source, $destination, $override, $search, $replace) {

            $fileSource = $source . $file->getFilename();
            $fileDestination = $destination . $file->getFilename();

            //dump("$fileSource");
            //if (!$this->filesystem->exists($fileSource)) {
            //    dump("file does not exist? " . $fileSource);
            //    return;
            //}

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

                $this->info("File copied: {$file->getFilename()}");
            }
            //dump($file->getFilename());
        });
    }

    /**
     * See if any files exist
     * Ask to override or not
     * @param Collection $files
     * @param            $destination
     * @return bool
     */
    private function overrideExistingFiles(Collection $files, $destination)
    {
        $answer = true;
        $filesFound = [];
        // map over to see if same filename already exist in destination
        $files->map(function (SplFileInfo $file) use ($destination, &$filesFound) {
            if ($this->filesystem->exists($destination . $file->getFilename())) {
                $filesFound [] = $file->getFilename();
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
        return str_replace('\\', DIRECTORY_SEPARATOR, $path);
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
