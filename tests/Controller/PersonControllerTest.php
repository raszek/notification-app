<?php

namespace App\Tests\Controller;

use App\Factory\PersonFactory;
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

}
