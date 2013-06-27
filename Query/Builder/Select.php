<?php
    namespace Hoathis\Query\Builder {
        class Select extends Sql implements Iface
        {
            protected $_manipulate = 'SELECT';
            protected $_manipulateModifier = array();

            protected $_into = null;
//            private $_having = array();
            protected $_lock = null;
            protected $_by_type = null;
            protected $_on = null;

            public function modifiers($type)
            {
                if (!in_array($type, $this->_manipulateModifier))
                    $this->_manipulateModifier[] = $type;

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

            public function lock($type)
            {
                $this->_lock = $type;

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
                    $this->_union(),
                    $this->_manipulate,
                    $this->_manipulateOptions(),
                    $this->_select(),
                    $this->_into(),
                    $this->_from(),
                    $this->_where(),
                    $this->_groupby(),
                    $this->_orderby(),
                    $this->_limit(),
                    $this->_lock(),
                    $this->_union(true)
                );

                return trim(implode(' ', $sql));
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

            protected function _lock()
            {
                if ($this->_lock !== null)
                    return $this->_lock;
            }
        }
    }
