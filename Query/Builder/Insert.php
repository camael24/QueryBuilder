<?php
    namespace Hoathis\Query\Builder {
        class Insert extends Sql implements Iface
        {
            protected $_manipulate = 'INSERT';
            protected $_manipulateModifier = array();
            protected $_into = null;
            protected $_set = array();
            protected $_values = array();
            protected $_on = array();
            protected $_select = null;

            public function modifiers($type)
            {
                if (!in_array($type, $this->_manipulateModifier))
                    $this->_manipulateModifier[] = $type;

                return $this;
            }

            public function value($cols, $value = null)
            {
                if ($value === null and is_array($cols))
                    foreach ($cols as $col => $value)
                        $this->value($col, $value);
                else
                    if (!array_key_exists($cols, $this->_values))
                        $this->_values[$cols] = $value;

                return $this;
            }

            public function select(Select $select)
            {
                $this->_select = $select;

                return $this;
            }

            public function into($table, $isFile = false, $option = array())
            {
                if ($isFile === true)
                    $table = ' OUTFILE ' . $table . ' ';
                if (!empty($option))
                    $table .= implode(' ', $option);

                $this->_into = $table;

                return $this;
            }

            public function on($query)
            {
                if (!in_array($query, $this->_on))
                    $this->_on[] = $query;

                return $this;
            }

            public function sql()
            {
                $sql = array(
                    $this->_manipulate,
                    $this->_manipulateOptions(),
                    $this->_into());

                if (!empty($this->_values)) {
                    $sql[] = $this->_cols();
                    $sql[] = $this->_values();
                } else if ($this->_select !== null) {
                    $sql[] = '(' . $this->_select->sql() . ')';
                } else {
                    $sql[] = $this->_set();
                }

                if (!empty($this->_on))
                    $sql[] = 'ON ' . implode(',', $this->_on);

                return trim(implode(' ', $sql));
            }

            protected function _set()
            {
                $array = array();
                foreach ($this->_set as $cols => $value)
                    $array[] = $cols . '=' . $value;

                return 'SET ' . implode(' , ', $array);
            }

            public function set($cols, $value = null)
            {
                if ($value === null and is_array($cols))
                    foreach ($cols as $col => $value)
                        $this->set($col, $value);
                else
                    if (!array_key_exists($cols, $this->_set))
                        $this->_set[$cols] = $value;


                return $this;
            }

            protected function _values()
            {
                $value = array_values($this->_values);

                return 'VALUES (' . implode(' , ', $value) . ')';
            }

            protected function _cols()
            {
                $cols = array_keys($this->_values);
                return '(' . implode(' , ', $cols) . ')';
            }

            protected function _manipulateOptions()
            {
                if (!empty($this->_manipulateModifier))
                    return implode(',', $this->_manipulateModifier);

            }

            protected function _into()
            {
                if (!empty($this->_into))
                    return 'INTO ' . $this->_into;
            }
        }
    }
