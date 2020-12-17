<?php

namespace App\Service;

class SearchOptionsHelper
{
    private $perPage;
    private $orderBy;
    private $itemType;
    private $default;

    public function __construct()
    {
        $this->perPage = [
            '1' => '5',
            '2' => '10',
            '3' => '20',
            '4' => '50',
            '5' => '100',
        ];
        $this->orderBy = [
            '1' => ['label' => 'Title (A to Z)', 'value' => 'b.title', 'dir' => 'ASC'],
            '2' => ['label' => 'Title (Z to A)', 'value' => 'b.title', 'dir' => 'DESC'],
            '3' => ['label' => 'Newest', 'value' => 'b.createdAt', 'dir' => 'DESC'],
            '4' => ['label' => 'Oldest', 'value' => 'b.createdAt', 'dir' => 'ASC'],
            '5' => ['label' => 'Most likes', 'value' => 'likesCount', 'dir' => 'DESC', 'join' => 'likes', 'alias' => 'l'],
            '6' => ['label' => 'Least likes', 'value' => 'likesCount', 'dir' => 'ASC', 'join' => 'likes', 'alias' => 'l'],
            '7' => ['label' => 'Most comments', 'value' => 'commentsCount', 'dir' => 'DESC', 'join' => 'comments', 'alias' => 'c'],
            '8' => ['label' => 'Least comments', 'value' => 'commentsCount', 'dir' => 'ASC', 'join' => 'comments', 'alias' => 'c'],
        ];
        $this->itemType = [
            '1' => 'Cards',
            '3' => 'Simple list',
            '2' => 'Expanded list',
        ];
        $this->default = [
            'perPage' => '1',
            'orderBy' => '1',
            'itemType' => '1',
        ];
    }

    public function getOptions()
    {
        return [
            'perPage' => $this->perPage,
            'orderBy' => $this->orderBy,
            'itemType' => $this->itemType,
        ];
    }

    public function getValue($type, $key = false)
    {
        return !isset($this->$type[$key]) || !$key ? $this->$type[$this->default[$type]] : $this->$type[$key];
    }
}
