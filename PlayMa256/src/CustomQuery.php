<?php
namespace PlayMa256\CustomQuery;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
class CustomQuery extends Builder{

    protected $methods = [];

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
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
        $modelAttributes = $this->model->getAttributes();
        foreach($modelAttributes as $attribute){
            $this->registerMethod($attribute, function($value) use (&$attribute){
               return $this->query->where($attribute, '=', $value);
            });
        }

    }

}