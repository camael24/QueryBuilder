<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 27/06/13
     * Time: 10:17
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Query\Builder {
        class Join
        {
            private $_direction = 'INNER JOIN';
            private $_one = '';
            private $_two = '';
            private $_using = array();
            private $_use = array();
            private $_on = array();

            public function __get($key)
            {
                $key = strtoupper($key);
                switch ($key) {
                    case 'LEFT':
                        $this->_direction = 'LEFT JOIN';
                        break;
                    case 'RIGHT':
                        $this->_direction = 'RIGHT JOIN';
                        break;
                    case 'NATURAL_LEFT':
                        $this->_direction = 'NATURAL LEFT JOIN';
                        break;
                    case 'NATURAL_RIGHT':
                        $this->_direction = 'NATURAL RIGHT JOIN';
                        break;
                    case 'NATURAL':
                        $this->_direction = 'NATURAL JOIN';
                        break;
                    case 'STRAIGHT_JOIN':
                        $this->_direction = 'STRAIGHT_JOIN';
                        break;
                    default:
                        $this->_direction = 'INNER JOIN';
                }
                return $this;
            }

            public function __call($name, $argument)
            {
                switch ($name) {
                    case 'use':
                        $this->callUse($argument[0]);
                        break;
                    default:
                }
                return $this;
            }

            public function join($table1, $table2)
            {
                $this->_one = $table1;
                $this->_two = $table2;

                return $this;
            }

            public function on($query)
            {
                if (!in_array($query, $this->_on))
                    $this->_on[] = $query;

                return $this;
            }

            public function using($query)
            {
                if (!in_array($query, $this->_using))
                    $this->_using[] = $query;

                return $this;
            }

            protected function callUse($query)
            {
                if (!in_array($query, $this->_use))
                    $this->_use[] = $query;
                return $this;
            }

            public function sql()
            {
                $base   = array();
                $base[] = $this->_one;
                $base[] = $this->_direction;
                $base[] = $this->_two;
                if (!empty($this->_using))
                    $base[] = 'USING (' . implode(',', $this->_using) . ')';
                if (!empty($this->_on))
                    $base[] = 'ON ' . implode(' AND ', $this->_on);
                if (!empty($this->_use))
                    $base[] = 'USE ' . implode(' ', $this->_use);

                return implode(' ', $base);
            }


        }
    }
