<?php

namespace zmultilang\src\classes\resources;

/**
 * Class Injector
 * @package zmultilang\src\classes\resources
 * @author Hatem Azzouzi, https://www.linkedin.com/in/hatemazzouzi
 * @todo object lazy loading / override and access value from within the config array
 */

class Injector {
    private $_definitions = array();

    /**
     * 
     * @param string $config
     */
    public function __construct(string $config) {
        if (file_exists($config)) {
            foreach (require($config) as $pathname) {
                if (file_exists($pathname)) {
                    $definition = require($pathname);
                    if (is_array($definition)) {
                        $this->_definitions = array_replace_recursive($this->_definitions, $definition);
                    }
                }
            }
        }
    }

    /**
     * 
     * @param string $name
     * @return type
     */
    public function resource(string $name) {
        if ($this->_definitions[$name]) {
            if (is_callable($this->_definitions[$name], false)) {
                return $this->_definitions[$name]();
            }
            return $this->_definitions[$name];
        }
    }

}
