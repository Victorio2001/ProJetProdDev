<?php

namespace Tests;

use BibliOlen\Models\BookModel;
use BibliOlen\Models\PublisherModel;
use PHPUnit\Framework\TestCase;
use DateTime;

class BookTest extends TestCase
{
    public function testConstructor() // vérifier le constructor
    {
        $id = 1;
        $titre = 'Test Book';
        $resume = 'This is a test book description.';
        $isbn = '1234567890123';
        $anneePublication = 2023;
        $imageCouverture = 'test.jpg';
        $nbExemplaires = 5;
        $idEditeur = 2;
        $createdAt = new DateTime('2023-01-01 12:00:00');
        $auteurs = ['Author1', 'Author2'];
        $publisher = PublisherModel::getDefault();
        $categories = ['Category1', 'Category2'];
        $archived = true;

        $book = new BookModel(
            $id,
            $titre,
            $resume,
            $isbn,
            $anneePublication,
            $imageCouverture,
            $nbExemplaires,
            $idEditeur,
            $createdAt,
            $auteurs,
            $publisher,
            $categories,
            $archived
        );

        $this->assertSame($id, $book->id);
        $this->assertSame($titre, $book->titre);
        $this->assertSame($resume, $book->description);
        $this->assertSame($isbn, $book->isbn);
        $this->assertSame($anneePublication, $book->anneePublication);
        $this->assertSame("/public/img/couvertures/$imageCouverture", $book->img);
        $this->assertSame($nbExemplaires, $book->nbExemplaires);
        $this->assertSame($idEditeur, $book->idEditeur);
        $this->assertSame("/livres/$id", $book->buttonLink);
        $this->assertSame($auteurs, $book->auteurs);
        $this->assertSame($publisher, $book->publisher);
        $this->assertSame($categories, $book->categories);
        $this->assertSame($archived, $book->archived);
        $this->assertEquals($createdAt, $book->createdAt);
    }

    public function testGetDefault() // Retourne un livre par defaut
    {
        $defaultBook = BookModel::getDefault();

        $this->assertSame(0, $defaultBook->id);
        $this->assertSame('', $defaultBook->titre);
        $this->assertSame('', $defaultBook->description);
        $this->assertSame('', $defaultBook->isbn);
        $this->assertSame(0, $defaultBook->anneePublication);
        $this->assertSame('/public/img/couvertures/', $defaultBook->img);
        $this->assertSame(0, $defaultBook->nbExemplaires);
        $this->assertSame(0, $defaultBook->idEditeur);
        $this->assertSame('/livres/0', $defaultBook->buttonLink);
        $this->assertSame([], $defaultBook->auteurs);
        $this->assertInstanceOf(PublisherModel::class, $defaultBook->publisher);
        $this->assertSame([], $defaultBook->categories);
        $this->assertFalse($defaultBook->archived);
        $this->assertInstanceOf(DateTime::class, $defaultBook->createdAt);
    }
}
