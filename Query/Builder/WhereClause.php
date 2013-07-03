<?php

    namespace Hoathis\Query\Builder {
        class WhereClause
        {
            protected $_where = array();
            protected $_where_modifier = null;
            protected $_value = array();

            public function where($clause, $value = null)
            {

                if ($clause instanceof \Closure) {

                    $where = new WhereClause();
                    $clause($where);
                    $valueClosure   = $where->getWhereValue();
                    $where          = $where->getWhereClause();
                    $this->_value[] = $valueClosure;
                    $this->_where[] = array('parenthesis' => $where, 'modifier' => $this->_where_modifier);


                } else {

                    if ($value !== null) {
                        if ($value instanceof \Closure) {
                            $select = new Select();
                            $value($select);
                            $clause .= ' ( ' . $select->sql() . ' )';
                        } else {
                            $this->_value[] = $value;
                        }
                    }

                    $this->_where[] = array(
                        'modifier' => $this->_where_modifier,
                        'clause'   => $clause
                    );
                }


                return $this;
            }

            public function __get($key)
            {
                $key = strtoupper($key);
                switch ($key) {
                    case 'AND':
                    case 'OR':
                        $this->_where_modifier = $key;
                        break;
                    default:
                }

                return $this;
            }

            public function getWhereClause()
            {
                return $this->_where;
            }

            public function getWhereValue()
            {
                return $this->_value;
            }

        }
    }
