<?php

namespace YRS;

/**
 * @purpose
 */
class SearchScoreHeap extends \SplHeap
{
    public function compare($score1, $score2)
    {
        // var_dump(array_values($score1));

        // if ($score1 === $score2) {
        //     return 0;
        // }

        // return $score1 < $score2 ? -1 : 1;
        $values1 = array_values($score1);
        $values2 = array_values($score2);
        // var_dump($values2[0]);
        if ($values1[0] === $values2[0]) return 0;
        return $values1[0] < $values2[0] ? -1 : 1;
    }
}
