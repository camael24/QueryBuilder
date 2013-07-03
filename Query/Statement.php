<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 27/06/13
     * Time: 14:12
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Query {
        /**
         * Class Statement
         *
         * @package Hoathis\Query
         */
        class Statement implements \Countable, \Iterator, \ArrayAccess
        {
            /**
             * @var Builder\Sql
             */
            private $_currentStatement = null;

            private $_query = array();
            private $_iterator = 0;

            /**
             * @param $key
             *
             * @return $this|Builder\Sql
             */
            public function __get($key)
            {
                $c   = null;
                $key = strtolower($key);
                switch ($key) {
                    case 'select':
                        $c = new \Hoathis\Query\Builder\Select();
                        break;
                    case 'update':
                        $c = new \Hoathis\Query\Builder\Update();
                        break;
                    case 'delete':
                        $c = new \Hoathis\Query\Builder\Delete();
                        break;
                    case 'insert':
                        $c = new \Hoathis\Query\Builder\Insert();
                        break;
                    default:
                        return $this;

                }

                if ($c !== null)
                    $this->_currentStatement = $c;

                return $c;
            }

            public function save()
            {
                $query        = $this->_currentStatement->sql();
                $value        = $this->_currentStatement->getWhereValue();
                $this->_query = str_split('hello');

                return $this;
            }

            public function fetch($size = null)
            {
                $return = array();
                if ($size === null)
                    return $this->_query;
                else
                    for ($i = 0; $i < $size; $i++)
                        $return[] = $this->_query[$i];

                return $return;
            }


            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Return the current element
             *
             * @link http://php.net/manual/en/iterator.current.php
             * @return mixed Can return any type.
             */
            public function current()
            {
                return $this->_query[$this->_iterator];
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Move forward to next element
             *
             * @link http://php.net/manual/en/iterator.next.php
             * @return void Any returned value is ignored.
             */
            public function next()
            {
                $this->_iterator += 1;
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Return the key of the current element
             *
             * @link http://php.net/manual/en/iterator.key.php
             * @return mixed scalar on success, or null on failure.
             */
            public function key()
            {
                return $this->_iterator;
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Checks if current position is valid
             *
             * @link http://php.net/manual/en/iterator.valid.php
             * @return boolean The return value will be casted to boolean and then evaluated.
             *       Returns true on success or false on failure.
             */
            public function valid()
            {
                return array_key_exists($this->_iterator, $this->_query);
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Rewind the Iterator to the first element
             *
             * @link http://php.net/manual/en/iterator.rewind.php
             * @return void Any returned value is ignored.
             */
            public function rewind()
            {
                $this->_iterator = 0;
            }

            /**
             * (PHP 5 &gt;= 5.1.0)<br/>
             * Count elements of an object
             *
             * @link http://php.net/manual/en/countable.count.php
             * @return int The custom count as an integer.
             * </p>
             * <p>
             *       The return value is cast to an integer.
             */
            public function count()
            {
                return count($this->_query);
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Whether a offset exists
             *
             * @link http://php.net/manual/en/arrayaccess.offsetexists.php
             *
             * @param mixed $offset <p>
             *                      An offset to check for.
             * </p>
             *
             * @return boolean true on success or false on failure.
             * </p>
             * <p>
             *       The return value will be casted to boolean if non-boolean was returned.
             */
            public function offsetExists($offset)
            {
                return array_key_exists($offset, $this->_query);
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Offset to retrieve
             *
             * @link http://php.net/manual/en/arrayaccess.offsetget.php
             *
             * @param mixed $offset <p>
             *                      The offset to retrieve.
             * </p>
             *
             * @return mixed Can return all value types.
             */
            public function offsetGet($offset)
            {
                if ($this->offsetExists($offset)) {
                    return $this->_query[$offset];
                }
                return null;
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Offset to set
             *
             * @link http://php.net/manual/en/arrayaccess.offsetset.php
             *
             * @param mixed $offset <p>
             *                      The offset to assign the value to.
             * </p>
             * @param mixed $value  <p>
             *                      The value to set.
             * </p>
             *
             * @return void
             */
            public function offsetSet($offset, $value)
            {
                return;
            }

            /**
             * (PHP 5 &gt;= 5.0.0)<br/>
             * Offset to unset
             *
             * @link http://php.net/manual/en/arrayaccess.offsetunset.php
             *
             * @param mixed $offset <p>
             *                      The offset to unset.
             * </p>
             *
             * @return void
             */
            public function offsetUnset($offset)
            {
                return;
            }
        }
    }
