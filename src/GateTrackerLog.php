<?php

namespace storfollo\gatetracker;

use DateTimeImmutable;
use Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class GateTrackerLog
{
    /**
     * @var Twig\Environment
     */
    public Twig\Environment $twig;
    /**
     * @var string
     */
    public string $web_root;
    /**
     * @var string
     */
    public string $project_root;
    private array $config;

    function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';
        if (!empty($_SERVER['SCRIPT_NAME']))
            $this->web_root = dirname($_SERVER['SCRIPT_NAME']);
        $this->project_root = dirname(__FILE__, 2);

        $loader = new Twig\Loader\FilesystemLoader(array(__DIR__ . '/../templates'), __DIR__);
        $this->twig = new Twig\Environment($loader, array('strict_variables' => true));
    }

    public function log_file(DateTimeImmutable $date = null): string
    {
        if (empty($date))
            $date = new DateTimeImmutable();
        $path = $this->config['path'] . '/' . $date->format('Y') . sprintf('/GateTracker%s.log', $date->format('Ymd'));
        /*if (!file_exists($path))
            throw new Exception('Log file not found');*/
        return $path;
    }

    public function previous(DateTimeImmutable $date): ?DateTimeImmutable
    {
        $old = new DateTimeImmutable();
        $old = $old->modify('-1 year');
        while ($date > $old)
        {
            $date = $date->modify('-1 day');
            if (file_exists($this->log_file($date)))
                return $date;
        }
        return null;
    }

    public function next(DateTimeImmutable $date): ?DateTimeImmutable
    {
        $today = new DateTimeImmutable();
        while ($date < $today)
        {
            $date = $date->modify('+1 day');
            if (file_exists($this->log_file($date)))
                return $date;
        }
        return null;
    }

    public function show_log(DateTimeImmutable $date = null)
    {
        $today = new DateTimeImmutable();
        if (empty($date))
            $date = $today;
        $prev = $this->previous($date);
        $next = $this->next($date);

        if ($next > $today || !file_exists($this->log_file($next)))
            $next = null;
        if (!file_exists($this->log_file($prev)))
            $prev = null;

        if (!file_exists($this->log_file($date)))
        {
            echo 'Ingen logg funnet for ' . $date->format('Y-m-d');
            return;
        }

        $iter = new LogIteratorHeader($this->log_file($date));
        echo $this->render('log.twig', [
            'lines' => $iter,
            'prev' => $prev,
            'next' => $next,
            'date' => $date]);
    }

    /**
     * Render a twig template
     * @param string $template
     * @param array $context
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $template, array $context): string
    {
        $context['root'] = $this->web_root;
        return $this->twig->render($template, $context);
    }
}