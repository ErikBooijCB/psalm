<?php

namespace Psalm\Diff;

use PhpParser;

class Stmt
{
    private static $pretty_printer;

    public static function isEqual(PhpParser\Node\Stmt $a, PhpParser\Node\Stmt $b)
    {
        if (get_class($a) !== get_class($b)) {
            return false;
        }

        $a_size = (int)$a->getAttribute('endFilePos') - (int)$a->getAttribute('startFilePos');
        $b_size = (int)$b->getAttribute('endFilePos') - (int)$b->getAttribute('startFilePos');

        if ($a_size !== $b_size) {
            return false;
        }

        if (!self::$pretty_printer) {
            self::$pretty_printer = new PhpParser\PrettyPrinter\Standard;
        }

        $a_code = $prettyPrinter->prettyPrint([$a]);
        $b_code = $prettyPrinter->prettyPrint([$b]);

        return $a_code === $b_code;
    }
}
