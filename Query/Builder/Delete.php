<?php
    namespace Hoathis\Query\Builder {
        class Delete extends Update
        {
            protected $_manipulate = 'DELETE';
            protected $_manipulateModifier = array();
            protected $_set = array();

            public function modifiers($type)
            {
                if (!in_array($type, $this->_manipulateModifier))
                    $this->_manipulateModifier[] = $type;

                return $this;
            }


            public function sql()
            {
                $sql = array(
                    $this->_manipulate,
                    $this->_manipulateOptions(),
                    $this->_select(),
                    $this->_where()
                );

                if ($this->_isMultiTable === false) {
                    $sql[] = $this->_orderby();
                    $sql[] = $this->_limit();
                }

                return trim(implode(' ', $sql));
            }

            protected function _manipulateOptions()
            {
                if (!empty($this->_manipulateModifier))
                    return implode(',', $this->_manipulateModifier);

            }

        }
    }
