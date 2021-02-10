<?php

namespace AnthonyDee\SlugifyColumn;

use App;
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
    private $count = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugify {tableModel} {id-column} {input-column} {output-column} {--chunk=100}';

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
        $this->tableModel = App::make("App\\{$this->argument('tableModel')}");
        $this->idColumn = $this->argument('id-column');
        $this->inputColumn = $this->argument('input-column');
        $this->outputColumn = $this->argument('output-column');
        $this->chunk = $this->option('chunk');

        $this->tableModel->chunkById($this->chunk, function ($chunk) {
          $this->runChunkUpdate(
            $this->processChunkUpdate($chunk)
          );
        });

        $this->info($this->count . ' fields in the "' . $this->inputColumn . '" column were slugified.');
    }

    private function processChunkUpdate($chunk) {
      $updates = [];

      foreach ($chunk as $row) {
        $input = $row->{$this->inputColumn};
        $slug = Str::slug($input);
        $updates[] = [
          $this->idColumn => $row->{$this->idColumn},
          $this->outputColumn => $slug
        ];

        $this->incrementCount();
      }

      return $updates;
    }

    private function runChunkUpdate($updates)
    {
      batch()->update($this->tableModel, $updates, $this->idColumn);
    }

    private function incrementCount()
    {
      $this->count++;
    }
}
