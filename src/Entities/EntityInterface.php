<?php

namespace FwsMailchimp\Entities;

/**
 * Mailchimp's entity interface
 *
 * @author Garry Childs (Freedom Web Services)
 */
interface EntityInterface
{

    /**
     * @return mixed
     */
    public function getId(): mixed;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

}
