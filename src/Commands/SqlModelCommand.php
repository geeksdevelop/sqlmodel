<?php

namespace Geeksdevelop\Sqlmodel\Commands;

use DB;
use Illuminate\Console\Command;

class SqlModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:make 
                            {table : Name of the table to generate the model}
                            {--db= : Name of the database where the table is located}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class using the sql structure of the table';

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
        if($this->checkDataBase($this->checkOptionDB())){
            $this->error('The model is generated with the following name '. $this->tableTitle());
            $name = '';
            if ($this->confirm('Do you want to change it? [y|N]')) {
                $name = $this->ask('Model name');
            }
            $this->callCommands($name);
        }else{
            $name = $this->option('db') ? $this->option('db') : env('DB_DATABASE');
            $this->error('The '.$name.' database does not exist.'); //Verificar la traduccion con yorman
        }
    }

    protected function callCommands($name = '')
    {
        $option = [
            'name' => $name ? $name : $this->tableTitle(),
            '--table' => $this->argument('table'),
            '--fillable' => $this->checkTable($this->argument('table'))
        ]; 

        $this->call('sql:model', $option);
    }

    protected function checkOptionDB()
    {
        return $this->option('db') ? $this->option('db') : false;
    }

    protected function checkDataBase($db='')
    {
        if(empty($db)){
            $db = env('DB_DATABASE');
        }
        $dbs = DB::select('SHOW DATABASES');
        foreach ($dbs as $value) {
            if($value->Database == $db)
                $r = true;
        }
        return isset($r) ? true : false;
    }

    protected function checkTable($table='')
    {
        $table = DB::select('DESCRIBE '.$table);
        $rows = [];
        foreach ($table as $column) {
            if($column->Field != 'id')
                array_push($rows, $column->Field);
        }
        return $this->tableFields($rows);
    }

    protected function tableFields($array='')
    {
        $fields = '';
        $fields .=  "".PHP_EOL;
        foreach ($array as $key => $value) {
            $fields .=  "\t\t'".$value."',".PHP_EOL;
        }
        $fields .=  "\t";
        return $fields;
    }

    protected function tableTitle()
    {
        return ucwords(str_singular($this->argument('table')));
    }
}
