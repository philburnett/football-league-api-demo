<?php declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class JwtUser implements UserInterface
{
    /**
     * @var null|string
     */
    private $username;

    /**
     * @var array
     */
    private $roles;

    /**
     * JwtUser constructor.
     *
     * @param null|string $username
     * @param array       $roles
     */
    public function __construct(string $username, array $roles = [])
    {
        $this->username = $username;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }
}
