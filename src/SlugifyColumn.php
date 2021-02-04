<?php

namespace AnthonyDee\SlugifyColumn;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlugifyColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugify {table} {id-column} {input-column} {output-column}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a table and column name and slugifies the values ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = $this->argument('table');
        $idCol = $this->argument('id-column');
        $inputCol = $this->argument('input-column');
        $outputCol = $this->argument('output-column');

        DB::table($table)->chunkById(100, function ($rows) {
          foreach ($rows as $row) {
              DB::table($table)
                  ->where($idCol, $user->{$idCol})
                  ->update([$outputCol => Str::slug($row->{$inputCol})]);

              if ($result) {
                  $this->info('Updated ' . $table . ' ' . $outputCol . '. Slug:  '  . $slug);
              } else {
                $this->error('Something went wrong! ID: ' . $id . ' Input value: ' . $input);
                break;
              }
          }
        });
    }
}
