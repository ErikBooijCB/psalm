<?php

namespace Psalm\Provider;

use PhpParser;

/**
 * Borrows from https://github.com/nikic/PHP-Parser/blob/master/lib/PhpParser/Internal/Differ.php
 * 
 * Implements the Myers diff algorithm.
 *
 * Myers, Eugene W. "An O (ND) difference algorithm and its variations."
 * Algorithmica 1.1 (1986): 251-266.
 *
 * @internal
 */
class ClassStatementsDiffer
{
    /**
     * Calculate diff (edit script) from $old to $new.
     *
     * @param PhpParser\Node\Stmt[] $old
     * @param PhpParser\Node\Stmt[] $new New array
     *
     * @return DiffElem[] Diff (edit script)
     */
    public static function diff(array $old, array $new)
    {
        list($trace, $x, $y) = $this->calculateTrace($old, $new);
        var_dump($trace, $x, $y);
        return $this->extractDiff($trace, $x, $y, $old, $new);
    }

    /**
     * Calculate diff (edit script) from $old to $new.
     *
     * @param PhpParser\Node\Stmt[] $a
     * @param PhpParser\Node\Stmt[] $b
     */
    private static function calculateTrace(array $a, array $b)
    {
        $n = \count($a);
        $m = \count($b);
        $max = $n + $m;
        $v = [1 => 0];
        $trace = [];
        for ($d = 0; $d <= $max; $d++) {
            $trace[] = $v;
            for ($k = -$d; $k <= $d; $k += 2) {
                if ($k === -$d || ($k !== $d && $v[$k-1] < $v[$k+1])) {
                    $x = $v[$k+1];
                } else {
                    $x = $v[$k-1] + 1;
                }

                $y = $x - $k;
                while ($x < $n && $y < $m && \Psalm\Diff\Stmt::isEqual($a[$x], $b[$y])) {
                    $x++;
                    $y++;
                }

                $v[$k] = $x;
                if ($x >= $n && $y >= $m) {
                    return [$trace, $x, $y];
                }
            }
        }
        throw new \Exception('Should not happen');
    }
}