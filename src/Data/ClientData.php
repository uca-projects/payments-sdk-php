<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;

class ClientData extends Data
{

    /**
     * @param string $id
     * @param string $name
     * @param string $domain
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $domain
    ) {}
}
