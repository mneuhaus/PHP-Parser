<?php
class PHPParser_Builder {
    const CONFLICT_IGNORE   = 1;
    const CONFLICT_REPLACE  = 2;
    const CONFLICT_APPEND   = 4;
    const CONFLICT_PREPEND  = 8;

    /**
     *
     * @var array
     **/
    protected $context = array();

    /**
     * Path to search for Templates
     *
     * @var string
     **/
    protected $path;

    public function __construct($path) {
        $this->path = rtrim($path, "/");
    }

    public function from($class) {
        $file = $this->path . '/' . strtr($class, '_', '/') . '.php';
        if(file_exists($file)){
            $this->content = file_get_contents($file);
        }
        return $this;
    }

    public function with($context) {
        $this->context = $context;
        return $this;
    }


    static public function parse($input){
        try {
            
            if(file_exists($input))
                $input = file_get_contents($input);
            
            $parser = new PHPParser_Parser;
            
            $stmts = $parser->parse(new PHPParser_Lexer($input));
            
            if(count($stmts) > 1)
                return $stmts;

            return current($stmts);

        } catch (PHPParser_Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

    static public function render($stmts) {
        try {
            
            if(is_object($stmts))
                $stmts = array($stmts);

            $prettyPrinter = new PHPParser_PrettyPrinter_TYPO3CGL;

            // pretty print
            $code = '<?php ' . $prettyPrinter->prettyPrint($stmts);

            return $code;

        } catch (PHPParser_Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

    static public function createClass($name) {
        $stmt = new PHPParser_Node_Stmt_Class($name);
        return $stmt;
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
}
?>