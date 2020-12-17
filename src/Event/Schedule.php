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
            if (empty($args[0]) || empty($args[1])) {
                throw new Exception('Any none default schedule needs to be defined by passing the interval in seconds as the first parameter and the display name as the second parameter.');
            }

            add_filter('cron_schedules', function (array $schedules) use ($name, $args): array {
                $schedules[$name] = [
                    'interval' => (int) $args[0],
                    'display' => (string) $args[1]
                ];

                return $schedules;
            });
        }

        $this->schedule = $name;

        return $this;
    }

    public function at(int $timestamp): void
    {
        if (!wp_next_scheduled($this->event->getHook())) {
            wp_schedule_event($timestamp, $this->schedule, $this->event->getHook());
        }

        add_action($this->event->getHook(), $this->event);
    }
}