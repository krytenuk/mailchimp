<?php

namespace FwsMailchimp;

class Module
{

    /**
     * @return array<int|string, mixed>
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

}
