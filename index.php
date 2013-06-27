<?php
    require "Query/Builder/WhereClause.php";
    require "Query/Builder/Join.php";
    require "Query/Builder/Sql.php";
    require "Query/Builder/Select.php";

    //
    $fluent = new \Hoathis\Query\Builder\Select();
    $fluent1 = new \Hoathis\Query\Builder\Select();
    $fluent
        ->scope('hello', function (\Hoathis\Query\Builder\WhereClause $query, $value) {
            $query->where('scope = ?', $value);
        })

        ->from('foo')
        ->scope('hello');
    $fluent1
        ->from('foo')
        ->from(function (\Hoathis\Query\Builder\Join $join) {
            $join
                ->right
                ->join('bar AS B', 'table AS t')
                ->on('t.id = B.table_id');

        })->union($fluent);


    $sql = $fluent1->sql();
    var_dump($fluent1->getWhereValue()); // array(5 , 'bar' , '8')
    var_dump($sql); //SELECT  *  FROM foo WHERE  foo > 1 OR foo > 2 OR bar > ? (  scope = ? ) (  bar = 3 OR bar = 4 (  fooo = 5 AND n => 2 ) )
