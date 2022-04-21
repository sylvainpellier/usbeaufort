<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use function usort;

class AppExtension extends AbstractExtension
{
    public function getName()
    {

    }
    public function getFilters()
    {
        return [
            new TwigFilter('minPause', [$this, 'formatMinPause']),
            new TwigFilter('orderMeets', [$this, 'orderMeets']),
        ];
    }

    public function orderMeets( $meets )
    {
        $meets = $meets->toArray();
        usort($meets , function($a,$b){ return $a->getTime() > $b->getTime(); });
        return $meets;
    }
    public function formatMinPause( $meets )
    {
        $min = false;

        $meets = $meets->toArray();
        usort($meets , function($a,$b){ return $a->getTime() > $b->getTime(); });

        $diff = null;
        if(count($meets)>1) {
            $min = $meets[1]->getTime() - $meets[0]->getTime();
            foreach ($meets as $key => $meet) {
                if (isset($meets[$key + 1])) {

                    $diff =  $meets[$key + 1]->getTime() - $meet->getTime() ;
                    if (!$min || $diff < $min) {
                        $min = $diff;
                    }
                }
            }
        }
        return $diff ? $min : null;
    }

}