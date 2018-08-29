<?php

namespace FwsMailchimp\Entities;

/**
 * Mailchimp entity interface
 *
 * @author Garry Childs (Freedom Web Services)
 */
interface EntityInterface
{

    public function getId();

    public function toArray();

}
