<?php

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;

class PaginationHelper
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginate($query, $currentPage, $pageLimit)
    {
        $items = $this->paginator->paginate(
            $query,
            $currentPage,
            $pageLimit
        );

        $total = $items->getTotalItemCount();
        $itemsFrom = ($currentPage - 1) * $pageLimit + 1;
        $itemsTo = $currentPage * $pageLimit > $total ? $total : $currentPage * $pageLimit;
        $itemsFrom = !$itemsTo ? 0 : $itemsFrom;

        $items->setCustomParameters([
            'itemsFrom' => $itemsFrom,
            'itemsTo' => $itemsTo
        ]);

        return $items;
    }
}
