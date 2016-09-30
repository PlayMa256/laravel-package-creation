<?php
namespace PlayMa256\CustomQuery;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CustomQuery extends Builder{

    protected $methods = [];
    private $tableName;

    public function __construct(QueryBuilder $query, $tableName)
    {
        parent::__construct($query);
        $this->tableName = $tableName;
    }

    public function retornaModelAttributes(){
        return $this->tableName;
    }
    /*
     *
     */
    public function __call($name, $arguments) {
        if (isset($this->methods[$name])) {
            $closure = $this->methods[$name];
            return $closure(...$arguments);
        }
        return null;
    }
    /*
     *
     */
    public function registerMethod($name, Callable $method) {
        $this->methods[$name] = $method;
    }

    /*
     *
     */
    public function createCustomQueries(){
        //Return all the database fields.
        $modelAttributes = DB::getSchemaBuilder()->getColumnListing($this->model->getTable());
        foreach($modelAttributes as $attribute){
            $this->registerMethod("findBy".ucfirst($attribute), function($value) use (&$attribute){
                return $this->query->where($attribute, '=', $value);
            });
        }
    }

    /*
     *
     */
    public function procurarPeloId($id){
        return $this->query->find($id);
    }
}