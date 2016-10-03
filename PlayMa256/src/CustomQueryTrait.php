<?php
namespace PlayMa256\CustomQuery;
use PlayMa256\CustomQuery\CustomQuery;
trait CustomQueryTrait {
    public function newEloquentBuilder($query){
        return new CustomQuery($query, $this->getTable());
    }
}