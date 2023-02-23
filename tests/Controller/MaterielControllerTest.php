<?php

namespace App\Test\Controller;

use App\Entity\Materiel;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MaterielControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MaterielRepository $repository;
    private string $path = '/materiel/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Materiel::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Materiel index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        // $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'materiel[nom]' => 'Testing',
            'materiel[nombre_total]' => '20',
            'materiel[en_stock]' => '20',
            'materiel[en_pret]' => '0',
        ]);

        // self::assertResponseRedirects('/materiel/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Materiel();
        $fixture->setNom('My Title');
        $fixture->setNombreTotal('My Title');
        $fixture->setEnStock('My Title');
        $fixture->setEnPret('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Materiel');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Materiel();
        $fixture->setNom('My Title');
        $fixture->setNombreTotal('My Title');
        $fixture->setEnStock('My Title');
        $fixture->setEnPret('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'materiel[nom]' => 'Something New',
            'materiel[nombre_total]' => 'Something New',
            'materiel[en_stock]' => 'Something New',
            'materiel[en_pret]' => 'Something New',
        ]);

        self::assertResponseRedirects('/materiel/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getNombreTotal());
        self::assertSame('Something New', $fixture[0]->getEnStock());
        self::assertSame('Something New', $fixture[0]->getEnPret());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Materiel();
        $fixture->setNom('My Title');
        $fixture->setNombreTotal('My Title');
        $fixture->setEnStock('My Title');
        $fixture->setEnPret('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/materiel/');
    }
}
