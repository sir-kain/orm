<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Query;
use Doctrine\Tests\OrmFunctionalTestCase;
use Exception;

/**
 * @group GH-9101
 */
class GH9101Test extends OrmFunctionalTestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    try {
      $this->_schemaTool->createSchema(
        [
          $this->_em->getClassMetadata(GH9101Author::class),
        ]
      );

      // Create author 11/Joe
      $author       = new GH9101Author();
      $author->id   = 11;
      $author->name = 'Joe';
      $author->title = 'Joe';
      $this->_em->persist($author);

      // Create author 12/Sir
      $author1       = new GH9101Author();
      $author1->id   = 12;
      $author1->name = 'Sir';
      $author1->title = 'Sir';
      $this->_em->persist($author1);

      $this->_em->flush();
      $this->_em->clear();
    } catch (Exception $e) {
    }
  }

  public function testIndexByOk(): void
  {
    $dql    = 'SELECT A.title FROM Doctrine\Tests\ORM\Functional\Ticket\GH9101Author A INDEX BY A.title ';
    $dql2   = 'SELECT new Doctrine\Tests\Models\CMS\CmsUserDTO(A.title)
                FROM Doctrine\Tests\ORM\Functional\Ticket\GH9101Author A INDEX BY A.title ';
    $result = $this->_em->createQuery($dql)->getResult();
    $result2 = $this->_em->createQuery($dql2)->getResult();

    var_dump($result);
    var_dump($result2);
    // $joe   = $this->_em->find(GH9101Author::class, 10);
    // $alice = $this->_em->find(GH9101Author::class, 11);

    // self::assertArrayHasKey('Joe', $result, "INDEX BY A.name should return an index by the name of 'Joe'.");
    // self::assertArrayHasKey('Alice', $result, "INDEX BY A.name should return an index by the name of 'Alice'.");
  }
}

/**
 * @Entity
 */
class GH9101Author
{
  /**
   * @var int
   * @Id
   * @Column(type="integer")
   */
  public $id;

  /**
   * @var string
   * @Column(type="string")
   */
  public $name;

  /**
   * @var string
   * @Column(type="string")
   */
  public $title;

  public function __construct()
  {
  }
}
