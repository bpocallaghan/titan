<?php

namespace Bpocallaghan\Titan\Commands;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;

trait CopyFilesHelpers
{
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
        if ($search === true && $replace === true) {
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
        if ($search === 'namespace_views' && $replace === true) {
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
     * @param string $path
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
}
