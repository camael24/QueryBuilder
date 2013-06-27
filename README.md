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
        ->limit(5 , 8);
      $sql = $fluent->sql();
      
DELETE
------------
    $fluent = new \Hoathis\Query\Builder\Delete();
    $fluent->expression('t1,t2')->from(array('t1' , 't2' , 't3'))->where(function ($query){
       $query->where('t1.id=t2.id')
           ->where('t2.id=t3.id');
    });
    
    
I am base my code on http://dev.mysql.com/doc/refman/5.0/fr/data-manipulation.html reference

Regards,
thehawk_
