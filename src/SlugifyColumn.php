<?php

namespace AnthonyDee\SlugifyColumn;

use Mavinoo\Batch\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlugifyColumn extends Command
{
    private $tableModel;
    private $idColumn;
    private $inputColumn;
    private $outputColumn;
    private $chunk;

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

        if (class_exists($table)) {
            $this->tableModel = new $table();
        } else {
          $this->error('A model class for ' . $table . ' does not exist.');
          return false;
        }

        $this->idColumn = $this->argument('id-column');
        $this->inputColumn = $this->argument('input-column');
        $this->outputColumn = $this->argument('output-column');
        $this->chunk = $this->option('chunk');

        DB::table($this->table)->chunkById($this->chunk, function ($rows) {
          $updates = [];
          foreach ($rows as $row) {
            $input = $row->{$this->inputColumn};
            $slug = Str::slug($input);
            $updates[] = [
              $this->idColumn => $row->{$this->idColumn},
              $this->outputColumn => $slug
            ];
          }
          
          Batch::update($this->tableModel, $updates, $this->idColumn);

          // foreach ($rows as $row) {
          //     $id = $row->{$this->idColumn};
          //     $input = $row->{$this->inputColumn};
          //     $slug = Str::slug($input);
          //
          //     $result = $this->slugifyColumn($id, $slug);
          //
          //     if ($result) {
          //       $this->printInfo($slug);
          //     } else {
          //       $this->printError($id, $input);
          //       break;
          //     }
          // }
        });
    }

    private function printInfo($slug) {
      $this->info('Updated ' . $this->table . ' ' . $this->outputColumn . '. Slug:  '  . $slug);
    }

    private function printError($id, $input) {
      $this->error('Something went wrong! ID: ' . $id . ' Input value: ' . $input);
    }

    private function slugifyColumn($id, $slug) {
      return DB::table($this->table)
          ->where($this->idColumn, $id)
          ->update([
            $this->outputColumn => $slug
          ]);
    }
}
