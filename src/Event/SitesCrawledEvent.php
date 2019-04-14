<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class SitesCrawledEvent extends Event
{
    public const NAME = 'sites.crawled';

    protected $crawledSites;

    public function __construct(array $crawledSites)
    {
        $this->crawledSites = $crawledSites;
    }

    public function getCrawledSites()
    {
        return $this->crawledSites;
    }
}
