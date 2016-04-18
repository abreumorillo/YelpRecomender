<?php

namespace YRS;

/**
 * @purpose         : This class is used to rank document in descending order. The ranking is based
 * on the cosine score of the document
 * @course          : Knowledge Representation Technologies
 *
 * @author          : Group # 6
 */
class SearchScoreHeap extends \SplHeap
{
    /**
     * Implementation of the compare, this is used for ranking documents in descending order.
     *
     * @param array $score1
     * @param array $score2
     *
     * @return int
     */
    public function compare($score1, $score2)
    {
        $values1 = array_values($score1);
        $values2 = array_values($score2);
        if ($values1[0] === $values2[0]) {
            return 0;
        }

        return $values1[0] < $values2[0] ? -1 : 1;
    }
}
