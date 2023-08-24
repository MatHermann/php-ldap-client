<?php

namespace MatHermann\Ldap;

use MatHermann\Ldap\Exception\LdapException;

class LdapClient
{
    /**
     * @var false|resource $ds
     */
    protected $ds;
    /**
     * @var string $baseDn
     */
    protected $baseDn;

    /**
     * @param string $host
     * @param int $port
     * @param string $base_dn
     * @throws LdapException
     */
    public function __construct($host = '127.0.0.1', $port = 389, $base_dn = '')
    {
        if (!$this->ds = @ldap_connect($host, $port . ''))
            throw $this->getLdapException();

        $this->setLdapOptions([
            LDAP_OPT_PROTOCOL_VERSION => 3,
            LDAP_OPT_REFERRALS => 0,
            LDAP_OPT_NETWORK_TIMEOUT => 10,
        ]);

        $this->baseDn = $base_dn;
    }

    public function __destruct()
    {
        @ldap_close($this->ds);
    }

    /**
     * @return LdapException
     */
    public function getLdapException()
    {
        return new LdapException(ldap_errno($this->ds), ldap_error($this->ds));
    }

    /**
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    public function setLdapOption($option, $value)
    {
        ldap_set_option($this->ds, $option, $value);
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setLdapOptions($options)
    {
        foreach ($options as $option => $value)
            $this->setLdapOption($option, $value);
        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string|null $directory
     * @return Result
     * @throws LdapException
     */
    public function signInUser($username, $password, $directory = null)
    {
        $dn = "CN=$username";
        if ($directory)
            $dn .= ',' . $directory;
        if ($this->baseDn)
            $dn .= ',' . $this->baseDn;

        if (!@ldap_bind($this->ds, $dn, $password))
            throw $this->getLdapException();

        return $this->searchOne($directory, "(&(objectClass=user)(objectCategory=person)(CN=$username))");
    }

    /**
     * @param string|null $directory
     * @param string|array $query
     * @param array $attributes
     * @param int $attributes_only
     * @param int $size_limit
     * @param int $time_limit
     * @param int $deref
     * @return Result
     * @throws LdapException
     * @see ldap_search()
     */
    public function search($directory, $query, $attributes = [], $attributes_only = 0, $size_limit = -1, $time_limit = -1, $deref = 0)
    {
        if ($directory && !$this->baseDn)
            $dn = $directory;
        else if (!$directory && $this->baseDn)
            $dn = $this->baseDn;
        else
            $dn = "$directory," . $this->baseDn;

        $result = @ldap_search($this->ds, $dn, $query, $attributes, $attributes_only, $size_limit, $time_limit, $deref);

        if (!$result)
            throw $this->getLdapException();

        if (!$data = @ldap_get_entries($this->ds, $result))
            throw $this->getLdapException();

        return new Result($data);
    }

    /**
     * @param string|null $directory
     * @param string|array $query
     * @param array $attributes
     * @param int $attributes_only
     * @param int $size_limit
     * @param int $time_limit
     * @param int $deref
     * @return Result|null
     * @throws LdapException
     * @see ldap_search()
     */
    public function searchOne($directory, $query, $attributes = [], $attributes_only = 0, $size_limit = -1, $time_limit = -1, $deref = 0)
    {
        $result = $this->search($directory, $query, $attributes, $attributes_only, $size_limit, $time_limit, $deref);

        if ($result->count() > 0)
            return new Result($result->get(0));

        return null;
    }
}
