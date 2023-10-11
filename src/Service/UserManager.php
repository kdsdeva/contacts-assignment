<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder)
    {

        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }
    public function encodePassword($user,string $plainPassword): string
    {
        return $this->encoder->encodePassword($user,$plainPassword);
    }

}