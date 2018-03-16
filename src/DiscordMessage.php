<?php

namespace NotificationChannels\Discord;

class DiscordMessage
{
    /**
     * @var string
     */
    public $body;

    /**
     * @var array
     */
    public $embed;

    /**
     * @param string $body
     * @param array  $embed
     *
     * @return static
     */
    public static function create($body = '', $embed = [])
    {
        return new static($body, $embed);
    }

    /**
     * DiscordMessage constructor.
     *
     * @param string $body
     * @param array  $embed
     */
    public function __construct($body = '', $embed = [])
    {
        $this->body = $body;
        $this->embed = $embed;
    }

    /**
     * @param $body
     *
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param $embed
     *
     * @return $this
     */
    public function embed($embed)
    {
        $this->embed = $embed;

        return $this;
    }
}
