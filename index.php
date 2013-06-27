<?php
    require "Query/Builder/WhereClause.php";
    require "Query/Builder/Iface.php";
    require "Query/Builder/Join.php";
    require "Query/Builder/Sql.php";
    require "Query/Builder/Select.php";
    require "Query/Builder/Update.php";
    require "Query/Builder/Delete.php";

//    $fluent = new \Hoathis\Query\Builder\Select();
//    $fluent
//        ->scope('hello', function (\Hoathis\Query\Builder\WhereClause $query, $value) {
//            $query->where('scope = ?', $value);
//        })
//
//        ->from('foo')
//        ->and
//        ->scope('hello', 'bar')
//        ->where(function ($query) {
//            $query
//                ->where('bar = 3')
//                ->or
//                ->where('bar = 4')
//                ->and
//                ->where(function ($query) {
//                    $query
//                        ->where('fooo = 5');
//                });
//
//        });

//    $fluent = new \Hoathis\Query\Builder\Update();
//    $fluent
//        ->table('table')
//        ->table(function (\Hoathis\Query\Builder\Join $join) {
//            $join->join('hello', 'bar');
//        })
//        ->set('table.foo', 'bar')
//        ->set(array(
//            'hola' => 'wazza',
//            'bibu' => 5
//        ))
//        ->where('foo = bar')
//        ->limit(5 , 8)
//    ;

    $fluent = new \Hoathis\Query\Builder\Delete();
    $fluent->expression('t1,t2')->from(array('t1' , 't2' , 't3'))->where(function ($query){
       $query->where('t1.id=t2.id')
           ->where('t2.id=t3.id');
    });


    $sql = $fluent->sql();
    var_dump($fluent->getWhereValue());
    var_dump($sql);
