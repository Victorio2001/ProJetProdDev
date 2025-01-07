<?php

namespace BibliOlen\Models;

use DateTime;
use Exception;

class BookModel
{
    public int $id;
    public string $titre;
    public string $description;
    public string $isbn;
    public int $anneePublication;
    public string $img;
    public int $nbExemplaires;
    public int $idEditeur;
    public DateTime $createdAt;
    public array $auteurs;
    public PublisherModel $publisher;
    public array $categories;
    public string $buttonLink;

    public bool $archived = false;

    public function __construct(
        int            $id,
        string         $titre,
        string         $resume,
        string         $isbn,
        int            $anneePublication,
        string         $imageCouverture,
        int            $nbExemplaires,
        int            $idEditeur,
        DateTime       $createdAt,
        array          $auteurs,
        PublisherModel $publisher,
        array          $categories,
        bool           $archived
    )
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $resume;
        $this->isbn = $isbn;
        $this->anneePublication = $anneePublication;
        $this->img = "/public/img/couvertures/$imageCouverture";
        $this->nbExemplaires = $nbExemplaires;
        $this->idEditeur = $idEditeur;
        $this->buttonLink = "/livres/$id";
        $this->auteurs = $auteurs;
        $this->publisher = $publisher;
        $this->categories = $categories;
        $this->archived = $archived;

        try {
            $this->createdAt = new DateTime($createdAt->format('Y-m-d H:i:s'));
        } catch (Exception) {
            $this->createdAt = new DateTime();
        }
    }

    public static function getDefault(): BookModel
    {
        return new BookModel(
            0,
            '',
            '',
            '',
            0,
            '',
            0,
            0,
            new DateTime(),
            [],
            PublisherModel::getDefault(),
            [],
            false
        );
    }
}
