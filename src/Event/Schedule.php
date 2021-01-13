<?php

namespace CEKW\WpPluginFramework\Core\Event;

use Exception;

class Schedule
{
    private ?EventInterface $event = null;
    private string $schedule = '';

    public function addEvent(EventInterface $event): Schedule
    {
        $this->event = $event;

        return $this;
    }

    public function __call(string $name, array $args): Schedule
    {
        $schedules = array_keys(wp_get_schedules());
        if (!in_array($name, $schedules)) {
            throw new Exception('Any none default schedule needs to be defined by using the cron_schedules filter hook.');
        }

        $this->schedule = $name;

        return $this;
    }

    public function at(int $timestamp): void
    {
        if (!wp_next_scheduled($this->event->getTag())) {
            wp_schedule_event($timestamp, $this->schedule, $this->event->getTag());
        }
    }

    public function removeEvent(EventInterface $event): void
    {
        wp_unschedule_hook($event->getTag());
    }
}