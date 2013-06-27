<?php
  require "Query/Builder/WhereClause.php";
  require "Query/Builder/Sql.php";
  require "Query/Builder/Select.php";


    $fluent = new \Hoathis\Query\Builder\Select();
    $fluent
        ->scope('hello' , function (\Hoathis\Query\Builder\WhereClause $query , $value){
            $query->where('scope = ?' , $value);
        })

        ->from('foo')
        ->where('foo > 1')
        ->or
            ->where('foo > 2')
            ->where('bar > ?' , 5)
            ->scope('hello' , 'bar')
        ->and
            ->where(function ($query) {
                $query
                    ->where('bar = 3')
                    ->or
                        ->where('bar = 4')
                    ->and
                        ->where(function ($query) {
                            $query
                                ->where('fooo = 5')
                                ->and
                                    ->where('n => 2' , '8');
                        });

            });


    $sql = $fluent->sql();
    var_dump($fluent->getWhereValue()); // array(5 , 'bar' , '8')
    var_dump($sql); //SELECT  *  FROM foo WHERE  foo > 1 OR foo > 2 OR bar > ? (  scope = ? ) (  bar = 3 OR bar = 4 (  fooo = 5 AND n => 2 ) )
