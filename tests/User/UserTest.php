<?php

namespace App\Tests\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine')->getManager();
        $this->encoder = self::$container->get('security.password_encoder');
        $this->userManager = self::$container->get('app.userManager');
    }

    public function testCreateAndCheckUser()
    {
        $this->clearUserData();

        $user = $this->createTestUser();

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals($user->getUsername(), "contactadmin");
        $this->assertIsString($user->getFirstname());
        $this->assertIsString($user->getLastname());
        $this->assertIsArray($user->getRoles());
    }

    public function createTestUser(): User
    {
        $user = new User();
        $user->setUsername("contactadmin");
        $user->setFirstName('Admin');
        $user->setLastName('Contact');
        $user->setEmail('contact@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $plainPassword = 'Contact@123';
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function clearUserData()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();
    }


}