QueryBuilder
============

INSERT
------------

    $fluent = new \Hoathis\Query\Builder\Insert();
    $fluent
        ->into('foo')
        ->value('foo', 'bar')
        ->value(
            array(
                'a' => 'b',
                'b' => 'c'
            )
        )
        ->on('DUPLICATE KEY UPDATE foo=bar');

    $sql = $fluent->sql();

INSERT  INTO foo (foo , a) VALUES (bar , b)

SELECT
------------
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

SELECT : SELECT  *  FROM foo WHERE   scope = ? AND (  bar = 3 OR bar = 4 AND  fooo = 5 )


UPDATE
------------

    $fluent = new \Hoathis\Query\Builder\Update();
    $fluent
        ->table('table')
        ->table(function (\Hoathis\Query\Builder\Join $join) {
            $join->join('hello', 'bar');
        })
        ->set('table.foo', 'bar')
        ->set(array(
            'hola' => 'wazza',
            'bibu' => 5
        ))
        ->where('foo = bar')
        ->limit(5 , 8); // The limit will be ignored in presence of join ;)
      $sql = $fluent->sql();

UPDATE : UPDATE  table,hello INNER JOIN bar SET table.foo=bar , hola=wazza , bibu=5 WHERE  foo = bar

DELETE
------------
    $fluent = new \Hoathis\Query\Builder\Delete();
    $fluent->expression('t1,t2')->from(array('t1' , 't2' , 't3'))->where(function ($query){
       $query->where('t1.id=t2.id')
           ->where('t2.id=t3.id');
    });
    
DELETE  t1,t2 WHERE  (  t1.id=t2.id AND t2.id=t3.id )


STATEMENT
============
This class implements Iterator, Countable, ArrayAccess for access of Query like it :

    $statement = new \Hoathis\Query\Statement();
    $statement->select
        ->from('foo')
        ->where('bar = ?', array('h', 'e', 'l', 'l', 'o'))
        ->where('foo = 5', 5);

    $statement->save();

    echo count($statement);
    foreach($statement as $i => $v)
        var_dump($i , $v);

    echo $statement[0];

ABOUT
============
I am base my code on http://dev.mysql.com/doc/refman/5.0/fr/data-manipulation.html reference

Regards,
thehawk_
