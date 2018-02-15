<?php

namespace AppBundle\Twig;

class DateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('remaining_days', array($this, 'getRemainingDays'))
        ];
    }

    public function getRemainingDays(\DateTime $date)
    {
        $tz_object = new \DateTimeZone('Europe/Paris');
        $now = new \DateTime();
        $now->setTimezone($tz_object);
        $now->format('Y\-m\-d\ h:i:s');

        if ($now < $date)
        {
            $interval = $date->diff($now);
            $days = $interval->format('%a');

            return $days;
        }

        return 0;
    }
}