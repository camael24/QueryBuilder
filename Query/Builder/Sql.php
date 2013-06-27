<?php
    namespace Hoathis\Query\Builder {
        abstract class Sql extends WhereClause
        {
            protected $_groupby = array();
            protected $_orderby = array();
            protected $_groupbyoption = array();
            protected $_from = array();
            protected $_limit = array();
            protected $_scope = array();

            public function from($table, $as = null)
            {
                if ($table instanceof \Closure) {
                    $join = new Join();
                    $table($join);
                    $table = $join;
                }
                if ($as !== null)
                    $table .= ' AS ' . $as;

                if (!in_array($table, $this->_from))
                    $this->_from[] = $table;

                return $this;
            }


            public function by($columns)
            {

                $type = $this->_by_type;
                if ($type === null)
                    throw new \Exception('You must precise before the type');

                switch ($type) {
                    case 'GROUP':
                        $this->groupby($columns);
                        break;
                    case 'ORDER':
                        $this->orderby($columns);
                        break;
                }

                return $this;

            }

            public function groupby($columns, $option = null)
            {
                if ($option !== null)
                    $this->_groupbyoption[] = $option;

                if (is_array($columns)) {
                    $this->_groupby = array_merge($this->_groupby, $columns);
                } else {
                    $this->_groupby = array_merge($this->_groupby, array($columns));
                }

                return $this;
            }

            public function orderby($columns)
            {
                if (is_array($columns))
                    $this->_orderby = array_merge($this->_orderby, $columns);
                else
                    $this->_orderby = array_merge($this->_orderby, array($columns));

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
                    case 'GROUP':
                    case 'ORDER':
                        $this->_by_type = $key;
                        break;
                    default:
                }

                return $this;
            }

            public function limit($nb, $start = null)
            {
                $this->_limit = array(
                    'nb'    => $nb,
                    'start' => $start
                );
                return $this;
            }

            protected function _from()
            {
                if (empty($this->_from))
                    throw new \Exception('Error from value are empty');

                foreach ($this->_from as $i => $d)
                    if ($d instanceof Join)
                        $this->_from[$i] = $d->sql();

                return 'FROM ' . implode(',', $this->_from);
            }

            protected function _where()
            {
                if (empty($this->_where))
                    return;

                $string = array('WHERE');

                foreach ($this->_where as $where) {
                    if (array_key_exists('parenthesis', $where))
                        $string [] = $this->_renderWhere($where['parenthesis'], true);
                    else
                        $string [] = $this->_renderWhere(array($where));

                }

                return implode(' ', $string);

            }

            private function _renderWhere($whereClause, $parenthesis = false)
            {


                $string = array();
                if ($parenthesis === true)
                    $string[] = '(';


                foreach ($whereClause as $where) {
                    if (array_key_exists('parenthesis', $where)) {
                        $string[] = $this->_renderWhere($where['parenthesis'], true);
                    } else {
                        $modifier  = $where['modifier'];
                        $clause    = $where['clause'];
                        $string [] = (($modifier === null) ? '' : $modifier) . ' ' . $clause;
                    }
                }
                if ($parenthesis === true)
                    $string[] = ')';

                return implode(' ', $string);
            }

            protected function _groupby()
            {
                if (!empty($this->_groupby))
                    return 'GROUP BY ' . implode(',', array_unique($this->_groupby)) . ' ' . implode(' ', $this->_groupbyoption);
            }

            protected function _orderby()
            {
                if (!empty($this->_orderby))
                    return 'ORDER BY ' . implode(',', array_unique($this->_orderby));
            }

            protected function _limit()
            {
                if (!empty($this->_limit)) {
                    $limit = $this->_limit['nb'];
                    if ($this->_limit['start'] !== null) {
                        $limit .= ',' . $this->_limit['start'];
                    }

                    return 'LIMIT ' . $limit;
                }
            }

            public function scope($id, $value = null)
            {
                if ($value instanceof \Closure)
                    $this->_scope[$id] = $value;
                else
                    if (array_key_exists($id, $this->_scope))
                        $this->where($this->_scope[$id], $value);


                return $this;
            }


        }
    }
