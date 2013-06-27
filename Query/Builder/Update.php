<?php
    namespace Hoathis\Query\Builder {
        class Update extends Sql implements Iface
        {
            protected $_manipulate = 'UPDATE';
            protected $_manipulateModifier = array();
            protected $_set = array();

            public function modifiers($type)
            {
                if (!in_array($type, $this->_manipulateModifier))
                    $this->_manipulateModifier[] = $type;

                return $this;
            }

            public function table($table, $as = null)
            {
                return $this->from($table, $as);
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

            public function sql()
            {
                $sql = array(
                    $this->_manipulate,
                    $this->_manipulateOptions(),
                    $this->_table(),
                    $this->_set(),
                    $this->_where()
                );

                if ($this->_isMultiTable === false) {
                    $sql[] = $this->_orderby();
                    $sql[] = $this->_limit();
                }

                return trim(implode(' ', $sql));
            }

            protected function _set()
            {
                $array = array();
                foreach ($this->_set as $cols => $value)
                    $array[] = $cols . '=' . $value;

                return 'SET ' . implode(' , ', $array);
            }

            protected function _table()
            {
                if (empty($this->_from))
                    throw new \Exception('Error table value are empty');

                foreach ($this->_from as $i => $d)
                    if ($d instanceof Join)
                        $this->_from[$i] = $d->sql();

                return implode(',', $this->_from);

            }

            protected function _manipulateOptions()
            {
                if (!empty($this->_manipulateModifier))
                    return implode(',', $this->_manipulateModifier);

            }

        }
    }
