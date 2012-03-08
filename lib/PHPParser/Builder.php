<?php
class PHPParser_Builder {

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

}
?>