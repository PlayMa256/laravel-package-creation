<?php
namespace PlayMa256\CustomQuery;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CustomQuery extends Builder{

    protected $methods = [];

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
        createCustomQueries();
    }

    public function procurarPeloId($id){
        return $this->query->find($id);
    }

    public function __call($name, $arguments) {
        if (isset($this->methods[$name])) {
            $closure = $this->methods[$name];
            return $closure(...$arguments);
        }
        return null;
    }
    public function registerMethod($name, Callable $method) {
        $this->methods[$name] = $method;
    }

    public function createCustomQueries(){
        $modelAttributes = DB::getSchemaBuilder()->getColumnListing($this->model->getTable());
        foreach($modelAttributes as $attribute){
            $this->registerMethod("findBy".ucfirst($attribute), function($value) use (&$attribute){
               return $this->query->where($attribute, '=', $value);
            });
        }

    }

}