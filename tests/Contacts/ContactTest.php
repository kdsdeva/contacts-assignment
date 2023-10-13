<?php

namespace App\Tests\Contacts;


use App\Entity\Contacts;
use App\Tests\DataFixtures\DataFixtureTestCase;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class ContactTest extends DataFixtureTestCase
{

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testContactEntry()
    {
        $this->clearContactData();

        $contactCreated = $this->createTestContact();
        $contact = $this->entityManager->getRepository(Contacts::class)->findOneBy(['id' => $contactCreated->getId()]);
        $this->assertIsString($contact->getFirstname());
        $this->assertIsString($contact->getLastname());
        $this->assertIsString($contact->getEmail());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function createTestContact(): Contacts
    {
        $contact = new Contacts();
        $contact->setTitle('Mr');
        $contact->setFirstname('contact');
        $contact->setLastname('admin');
        $contact->setAddress('Test address');
        $contact->setEmail('admin@gmail.com');
        $contact->setMobileNumber('89938333339');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return $contact;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function clearContactData()
    {
        $contacts = $this->entityManager->getRepository(Contacts::class)->findAll();

        foreach ($contacts as $contact) {
            $this->entityManager->remove($contact);
        }
        $this->entityManager->flush();
    }
}