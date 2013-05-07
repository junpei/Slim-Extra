<?php

namespace Slim\Extra;

class Pagination
{
    public $page;
    public $limit;
    public $counts;
    public $offset;

    public $first;
    public $last;

    public $prev;
    public $next;

    public $pages;

    public function __construct() {
        $attributes = (object) func_get_arg(0);

        $this->page = $attributes->page - 0;
        $this->limit = $attributes->limit - 0 ?: 32;
        $this->counts = $attributes->counts;
        $this->offset = $this->page * $this->limit;

        $this->first = 0;
        $this->last = ($this->counts % $this->limit) > 0
            ? (int) floor($this->counts / $this->limit)
            : (int) $this->counts / $this->limit - 1;

        $this->prev = max($this->page - 1, $this->first);
        $this->next = min($this->page + 1, $this->last);

        $this->start = min($this->offset + 1, $this->counts);
        $this->end   = min($this->offset + $this->limit, $this->counts);

        $range = 4;
        $start = $this->page - $range;
        $end   = $this->page + $range;

        $start -= $end   < $this->last  ? 0 : $end - $this->last;
        $end   += $start > $this->first ? 0 : -$start;

        $start = $this->first >= $start ? $this->first + 1 : $start;
        $end   = $this->last  <= $end   ? $this->last  - 1 : $end;

        $this->pages = $start < 1 || $end < 1
            ? array() : range($start, $end);
    }
}
