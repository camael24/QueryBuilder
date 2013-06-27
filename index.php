<?php
    require "Query/Builder/WhereClause.php";
    require "Query/Builder/Join.php";
    require "Query/Builder/Sql.php";
    require "Query/Builder/Select.php";

    $fluent = new \Hoathis\Query\Builder\Select();
    $fluent
        ->scope('hello', function (\Hoathis\Query\Builder\WhereClause $query, $value) {
            $query->where('scope = ?', $value);
        })

        ->from('foo')
        ->and
        ->scope('hello', 'bar')
        ->where(function ($query) {
            $query
                ->where('bar = 3')
                ->or
                ->where('bar = 4')
                ->and
                ->where(function ($query) {
                    $query
                        ->where('fooo = 5');
                });

        });

    $sql = $fluent->sql();
    var_dump($fluent->getWhereValue());
    var_dump($sql);
