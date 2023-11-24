<?php

namespace storfollo\gatetracker;

use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GateTrackerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('parseDate', [$this, 'parseDate']),
            new TwigFilter('gateName', [$this, 'gateName']),
        ];
    }

    public function parseDate($date): DateTimeImmutable
    {
        return new DateTimeImmutable(substr($date, 0, 14));
    }

    public function gateName($gate): string
    {
        $config = config($_GET['site'] ?? null);
        $gate = intval($gate);
        return $config['gates'][$gate] ?? $gate;
    }
}