<?php
    require "Query/Builder/WhereClause.php";
    require "Query/Builder/Iface.php";
    require "Query/Builder/Join.php";
    require "Query/Builder/Sql.php";
    require "Query/Builder/Select.php";
    require "Query/Builder/Update.php";
    require "Query/Builder/Delete.php";
    require "Query/Builder/Insert.php";

    
    $fluent = new \Hoathis\Query\Builder\Insert();
    $fluent->into('foo')->value('foo', 'bar')->value(array('a' => 'b', 'b' => 'c'))->on('DUPLICATE KEY UPDATE foo=bar');

    $sql = $fluent->sql();
    var_dump($fluent->getWhereValue());
    var_dump($sql);
