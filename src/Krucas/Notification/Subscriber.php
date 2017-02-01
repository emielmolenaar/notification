<?php namespace Krucas\Notification;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Session\Store;

class Subscriber
{
    /**
     * Session instance for flashing messages.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Session key.
     *
     * @var string
     */
    protected $key;

    /**
     * Create new subscriber.
     *
     * @param \Illuminate\Session\Store $session
     * @param string $key
     */
    public function __construct(Store $session, $key)
    {
        $this->session = $session;
        $this->key = $key;
    }

    /**
     * Get session instance.
     *
     * @return \Illuminate\Session\Store
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get session key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Execute this event to flash messages.
     *
     * @param $eventName
     * @param array $eventData
     * @return bool
     */
    public function onFlash($eventName, array $eventData)
    {
        list($notification, $notificationsBag, $message) = $eventData;

        $key = implode('.', [$this->key, $notificationsBag->getName()]);

        $this->session->push($key, $message);

        return true;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('notification.flash: *', 'Krucas\Notification\Subscriber@onFlash');
    }
}
