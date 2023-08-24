<?php

namespace MatHermann\Ldap\Exception;

use Exception;

class LdapException extends Exception
{
    /**
     * @param int $code
     * @param string $message
     */
    public function __construct($code = 0, $message = "")
    {
        parent::__construct($message, $code);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return "LdapException " . $this->code . ($this->message !== "" ? ": " . $this->message : "");
    }
}
