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
            protected $_union = null;
            protected $_isMultiTable = false;
            protected $_selectExpressions = array();

            public function expression($cols, $as = null)
            {
                $string = $cols;
                if ($as !== null)
                    $string .= ' AS ' . $as;

                if (!in_array($string, $this->_selectExpressions))
                    $this->_selectExpressions[] = $string;

                return $this;
            }

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

                if (count($this->_from) > 1)
                    $this->_isMultiTable = true;

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

            public function union(Sql $sql)
            {
                $this->_union = $sql;
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

            protected function _union($start = false)
            {
                if ($this->_union !== null) {
                    if ($start === false)
                        return '(' . $this->_union->sql() . ') UNION (';
                    else
                        return ')';

                }
            }

            protected function _where()
            {
                $where     = $this->_where;
                $structure = $this->_subWhere($where);


                return 'WHERE ' . $structure;

            }

            protected function _subWhere($where)
            {
                $structure = array();
                foreach ($where as $position => $clause) {
                    if (array_key_exists('parenthesis', $clause)) {
                        $modifier = $clause['modifier'];
                        if ($modifier === null)
                            $modifier = 'AND';
                        if ($position === 0)
                            $modifier = '';

                        $structure[] = $modifier;
                        if (count($clause['parenthesis']) > 1)
                            $structure[] = '(';
                        $structure[] = $this->_subWhere($clause['parenthesis']);
                        if (count($clause['parenthesis']) > 1)
                            $structure[] = ')';
                    } else {

                        $structure[] = $this->_rendWhere($clause, $position);
                    }


                }
                return implode(' ', $structure);
            }

            private function _rendWhere($clause, $position)
            {
                $modifier = $clause['modifier'];
                $clause   = $clause['clause'];
                if ($modifier === null)
                    $modifier = 'AND';
                if ($position === 0)
                    $modifier = '';


                return $modifier . ' ' . $clause;
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

            protected function _select()
            {
                $return = null;
                if (!empty($this->_selectExpressions)) {
                    $return = implode(',', $this->_selectExpressions);
                }
                return (($return === null) ? '*' : $return);

            }

        }
    }
