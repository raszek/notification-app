<?php

namespace App\Tests\Controller;

use App\Factory\PersonFactory;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class PersonControllerTest extends WebTestCase
{

    use Factories;

    /** @test */
    public function user_can_list_person_list()
    {
        $client = static::createClient();
        $client->followRedirects();

        PersonFactory::createOne([
            'name' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'phone' => '123123123',
        ]);

        PersonFactory::createOne([
            'name' => 'Lisa',
            'lastname' => 'Disa',
            'email' => 'lisadisa@gmail.com',
            'phone' => '234234234',
        ]);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $crawler = $client->getCrawler();

        $tableRecords = $crawler->filter('table')->filter('tr')->each(function ($tr) {
            return $tr->filter('td')->each(function ($td) {
                return trim($td->text());
            });
        });

        $this->assertEquals(['John', 'Doe', 'johndoe@gmail.com', '123123123'], $tableRecords[1]);
        $this->assertEquals(['Lisa', 'Disa', 'lisadisa@gmail.com', '234234234'], $tableRecords[2]);
    }

    /** @test */
    public function user_can_add_person()
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/people/create');

        $this->assertResponseIsSuccessful();

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Create')->form();

        $client->submit($form, [
            'person[firstName]' => 'Vitold',
            'person[lastName]' => 'Cramling',
            'person[email]' => 'vitold@gmail.com',
            'person[phone]' => '123123123',
        ]);

        $this->assertResponseIsSuccessful();

        $createdPerson = $this->personRepository()->findOneBy([
            'email' => 'vitold@gmail.com',
        ]);

        $this->assertNotNull($createdPerson);
        $this->assertEquals('Vitold', $createdPerson->getName());
        $this->assertEquals('Cramling', $createdPerson->getLastname());
        $this->assertEquals('vitold@gmail.com', $createdPerson->getEmail());
        $this->assertEquals('123123123', $createdPerson->getPhone());
    }

    private function personRepository(): PersonRepository
    {
        return static::getContainer()->get(PersonRepository::class);
    }
}
