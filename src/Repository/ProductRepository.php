<?php

namespace App\Repository;

use App\Entity\Product;
use App\Model\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function delete(Product $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(int $id = null, string $name = null, string $description = null)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        if ($id) {
            $queryBuilder
                ->andWhere('p.id LIKE :id')
                ->setParameter('id', '%' . $id . '%');
        }

        if ($name) {
            $queryBuilder
                ->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($description) {
            $queryBuilder
                ->andWhere('p.description LIKE :description')
                ->setParameter('description', '%' . $description . '%');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
