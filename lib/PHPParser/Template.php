<?php

class PHPParser_Template extends PHPParser_Builder {

    /**
     *
     * @var array
     **/
    protected $context = array();

    public function __construct($file) {
        $this->content = file_get_contents($file);
    }

    public function __call($method, $arguments) {
        switch ($method) {

            case 'getMethods':
            case 'getStatements':
                    $context = isset($arguments[0]) ? $arguments[0] : null;

                    $content = $this->replace($this->content, $context);

                    $ast = $this->parse($content);

                    $result = call_user_func(array($ast, $method));
                    
                    return $result;
                break;

            case 'getMethod':
            case 'getProperty':
                    $context = isset($arguments[1]) ? $arguments[1] : null;

                    $content = $this->replace($this->content, $context);
                    $name = $this->replace($arguments[0], $context);
                    
                    $ast = $this->parse($content);
                    
                    return call_user_func_array(array($ast, $method), array($name));
                break;
        }
    }

    public function getStatements($context = null) {
        $content = $this->replace($this->content, $context);
        
        $ast = $this->parse($content);

        return $ast->stmts;
    }

    public function replace($content, $context = null) {
        if(is_null($context))
            $context = $this->context;

        foreach ($context as $key => $value) {
            $content = str_replace("__" . $key, $value, $content);

            $variations = "lcfirst,ucfirst";
            foreach (explode(",", $variations) as $variation) {
                $key = call_user_func($variation, $key);
                $value = call_user_func($variation, $value);
                $content = str_replace("__" . $key, $value, $content);
            }
        }

        return $content;
    }

    public function setContext($context) {
        $this->context = $context;
    }
}

?>