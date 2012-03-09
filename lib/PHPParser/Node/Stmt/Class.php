<?php

/**
 * @property int                      $type       Type
 * @property string                   $name       Name
 * @property null|PHPParser_Node_Name $extends    Name of extended class
 * @property PHPParser_Node_Name[]    $implements Names of implemented interfaces
 * @property PHPParser_Node[]         $stmts      Statements
 */
class PHPParser_Node_Stmt_Class extends PHPParser_Node_Stmt
{
    const MODIFIER_PUBLIC    =  1;
    const MODIFIER_PROTECTED =  2;
    const MODIFIER_PRIVATE   =  4;
    const MODIFIER_STATIC    =  8;
    const MODIFIER_ABSTRACT  = 16;
    const MODIFIER_FINAL     = 32;

    protected static $specialNames = array(
        'self'   => true,
        'parent' => true,
        'static' => true,
    );

    /**
     * Constructs a class node.
     *
     * @param string      $name       Name
     * @param array       $subNodes   Array of the following optional subnodes:
     *                                'type'       => 0      : Type
     *                                'extends'    => null   : Name of extended class
     *                                'implements' => array(): Names of implemented interfaces
     *                                'stmts'      => array(): Statements
     * @param int         $line       Line
     * @param null|string $docComment Nearest doc comment
     */
    public function __construct($name, array $subNodes = array(), $line = -1, $docComment = null) {
        parent::__construct(
            $subNodes + array(
                'type'       => 0,
                'extends'    => null,
                'implements' => array(),
                'stmts'      => array(),
            ),
            $line, $docComment
        );
        $this->name = $name;

        if (isset(self::$specialNames[(string) $this->name])) {
            throw new PHPParser_Error(sprintf('Cannot use "%s" as class name as it is reserved', $this->name));
        }

        if (isset(self::$specialNames[(string) $this->extends])) {
            throw new PHPParser_Error(sprintf('Cannot use "%s" as class name as it is reserved', $this->extends));
        }

        foreach ($this->implements as $interface) {
            if (isset(self::$specialNames[(string) $interface])) {
                throw new PHPParser_Error(sprintf('Cannot use "%s" as interface name as it is reserved', $interface));
            }
        }
    }

    public static function verifyModifier($a, $b) {
        if ($a & 7 && $b & 7) {
            throw new PHPParser_Error('Multiple access type modifiers are not allowed');
        }

        if ($a & self::MODIFIER_ABSTRACT && $b & self::MODIFIER_ABSTRACT) {
            throw new PHPParser_Error('Multiple abstract modifiers are not allowed');
        }

        if ($a & self::MODIFIER_STATIC && $b & self::MODIFIER_STATIC) {
            throw new PHPParser_Error('Multiple static modifiers are not allowed');
        }

        if ($a & self::MODIFIER_FINAL && $b & self::MODIFIER_FINAL) {
            throw new PHPParser_Error('Multiple final modifiers are not allowed');
        }

        if ($a & 48 && $b & 48) {
            throw new PHPParser_Error('Cannot use the final modifier on an abstract class member');
        }
    }

    /**
     * Usability API
     */
    
    public function add($stmts, $conflict = PHPParser_Builder::CONFLICT_IGNORE) {
        if(is_array($stmts)){
            foreach ($stmts as $stmt){
                $this->add($stmt, $conflict);
            }
        } else {
            if(!$this->hasStatement($stmts)){
                $this->subNodes["stmts"][] = $stmts;
            }else{
                switch ($conflict) {
                    case PHPParser_Builder::CONFLICT_REPLACE:
                            $this->removeStatement($stmts);
                            $this->add($stmts);
                        break;

                    case PHPParser_Builder::CONFLICT_APPEND:
                            $this->get($stmts->name)->append($stmts->getStatements());
                        break;

                    case PHPParser_Builder::CONFLICT_PREPEND:
                            $this->get($stmts->name)->prepend($stmts->getStatements());
                        break;

                    case PHPParser_Builder::CONFLICT_IGNORE:
                    default:
                        break;
                }
            }
        }

        return $this;
    }

    public function addMethod($stmt, $conflict = PHPParser_Builder::CONFLICT_IGNORE) {
        $this->add($stmt);
        return $this;
    }

    public function addProperty($stmt, $conflict = PHPParser_Builder::CONFLICT_IGNORE) {
        $this->add($stmt);
        return $this;
    }

    public function getMethods() {
        return $this->getByType("ClassMethod");
    }

    public function getMethod($name) {
        $results = array();
        if(is_array($this->stmts)){
            foreach ($this->stmts as $key => $value) {
                if($value->name == $name)
                    return $value;
            }
        }
    }

    public function getProperties() {
        return $this->getByType("Property");
    }

    public function hasStatement($stmt) {
        $name = $stmt->name;
        foreach ($this->subNodes["stmts"] as $stmt) {
            if($stmt->name == $name)
                return true;
        }
        return false;
    }

    public function getProperty($name) {
        $results = array();
        if(is_array($this->stmts)){
            foreach ($this->stmts as $key => $value) {
                $prop = current($value->getByType("PropertyProperty"));
                if($prop->name == $name)
                    return $value;
            }
        }
    }

    public function removeStatement($stmt) {
        $name = $stmt->name;
        foreach ($this->stmts as $key => $stmt) {
            if($stmt->name == $name)
                unset($this->stmts[$key]);
        }
    }
}