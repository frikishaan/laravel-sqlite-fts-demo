<?php

namespace App\Console\Commands;

use App\Imports\PostsImport;
use Exception;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class PopulateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database with Hackernews data';

    private $csv_path = 'app/public/hn.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set("memory_limit", "1G"); // to prevent out of memory issue, since the csv is large

        try {
            Excel::import(
                new PostsImport, 
                storage_path($this->csv_path), 
                readerType: \Maatwebsite\Excel\Excel::CSV
            );
        }
        catch(Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
