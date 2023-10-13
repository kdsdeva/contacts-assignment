<?php

namespace App\Tests\DataFixtures;

use App\Entity\Contacts;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class DataFixtureTestCase extends WebTestCase
{
    /** @var  Application $application */
    protected static $application;

    /** @var  KernelBrowser $client */
    protected $client;

    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /**
     * {@inheritDoc}
     */
    public function setUp():void
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:create');

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->encoder = $kernel->getContainer()->get('security.password_encoder');
        $this->userManager = $kernel->getContainer()->get('app.userManager');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function createUser(): User
    {
        // User Object
        $user = new User();
        $user->setUsername("contact");
        $user->setFirstName('Admin');
        $user->setLastName('Tech');
        $user->setEmail('contact@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $plainPassword = 'Contact@123';
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function createContact(): Contacts
    {
        // Contact Object
        $contact = new Contacts();
        $contact->setTitle('Mr');
        $contact->setFirstname('Test');
        $contact->setLastname('Contact');
        $contact->setEmail('testcontact@gmail.com');
        $contact->setMobileNumber(958796686);
        $contact->setAddress('Industrial Area');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return $contact;
    }
}