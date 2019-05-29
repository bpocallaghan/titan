<?php

namespace Bpocallaghan\Titan\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\SplFileInfo;

class MigrateCommand extends Command
{
    use CopyFilesHelpers;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'titan:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Titan extra feautres (blog, news, shop, etc).';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Composer
     */
    protected $composer;

    private $appPath;

    private $basePath;

    private $ds;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem, Composer $composer)
    {
        parent::__construct();

        $this->ds = DIRECTORY_SEPARATOR;
        $this->composer = $composer;
        $this->filesystem = $filesystem;

        $this->basePath = __DIR__ . $this->ds . '..' . $this->ds . '..' . $this->ds;
        $this->appPath = $this->basePath . "app" . $this->ds;
    }

    /**
     * Execute the command
     */
    public function handle()
    {
        /**
         * TODO
        titan:copy / titan:feature --type=blog

        Ask - copy migrate files [yes/no]
         * no - copy to titan only
         * yes - copy to app
        Then it ask, copy website files [yes/no]
        Then it ask, copy admin files [yes/no]
        - this include model
         */

        $name = $this->option('name');

        $source = [];
        $destination = "{$this->basePath}database{$this->ds}migrations";
        $sourceBase = "{$this->basePath}database{$this->ds}migrations_titan{$this->ds}";

        // copy migration files
        switch ($name) {
            case 'blog':
                $source = [
                    $sourceBase . "2017_07_10_165218_create_articles_table.php",
                    $sourceBase . "2017_07_10_152224_create_article_categories_table.php",
                ];
                break;
            case 'faq':
                $source = [
                    $sourceBase . "2017_07_08_102625_create_faqs_table.php",
                    $sourceBase . "2017_07_08_094112_create_faq_categories_table.php",
                ];
                break;
            case 'locations':
                $source = [
                    $sourceBase . "2018_07_17_084120_create_suburbs_table.php",
                    $sourceBase . "2018_07_17_114058_create_cities_table.php",
                    $sourceBase . "2018_07_17_124039_create_provinces_table.php",
                    $sourceBase . "2018_07_17_183710_create_countries_table.php",
                    $sourceBase . "2018_07_17_202746_create_continents_table.php",
                ];
                break;
            case 'news':
                $source = [
                    $sourceBase . "2017_09_25_175125_create_news_table.php",
                    $sourceBase . "2017_09_25_175153_create_news_categories_table.php",
                ];
                break;
            case 'newsletter_subscribers':
                $source = [
                    $sourceBase . "2017_10_01_181435_create_newsletter_subscribers_table.php",
                ];
                break;
            case 'photos':
                $source = [
                    $sourceBase . "2017_09_26_082330_create_photo_albums_table.php",
                    $sourceBase . "2017_09_26_090011_create_photo_tag_pivot_table.php",
                ];
                break;
            case 'testimonial':
                $source = [
                    $sourceBase . "2017_06_20_114920_create_testimonials_table.php",
                ];
                break;
            case 'documents':
                $source = [
                    $sourceBase . "2017_10_20_100622_create_document_categories_table.php",
                ];
                break;
            case 'shop':
                $source = [
                    $sourceBase . "2019_05_21_112451_create_products_table.php",
                    $sourceBase . "2019_05_21_131522_create_checkouts_table.php",
                    $sourceBase . "2019_05_21_143232_create_transactions_table.php",
                    $sourceBase . "2019_05_21_095926_create_product_statuses_table.php",
                    $sourceBase . "2019_05_21_090404_create_product_categories_table.php",
                    $sourceBase . "2019_05_21_131648_create_checkout_product_pivot_table.php",
                    $sourceBase . "2019_05_21_143243_create_product_transaction_pivot_table.php",
                ];
                break;
        }

        // if any source
        if(count($source) >= 1) {
            $this->copyFilesFromSource($source, $destination);

            // php artisan migrate
            $this->call('migrate');
        }


        // composer dump autoload
        //$this->composer->dumpAutoloads();

        $this->alert('Copy of migration files and database migrated complete.');
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
                'name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Which feature migration name must be copeid (blog, news, faq, testimonials, shop)?',
                'all'
            ],
        ];
    }
}
