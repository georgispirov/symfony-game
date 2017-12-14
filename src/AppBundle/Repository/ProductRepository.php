<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository implements IProductRepository
{
    public function invokeFindByBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder($this->getClassMetadata()->getTableName());
    }

    /**
     * @param string $name
     * @return array
     */
    public function getProductsByCategory(string $name): array
    {
        /* @var Categories $category */
        $category = $this->getEntityManager()
                         ->getRepository(Categories::class)
                         ->findByCategoryName($name);

        return $this->findBy(['category' => $category->getId()]);
    }

    /**
     * @param int $id
     * @return null|Product
     */
    public function findProductByID(int $id)
    {
        return $this->getEntityManager()
                    ->getRepository(Product::class)
                    ->findOneBy([
                        'id' => $id
                    ]);
    }

    /**
     * @return Product[]
     */
    public function getAllActiveProducts(): array
    {
        $query = $this->getEntityManager()
                      ->getRepository(Product::class)
                      ->createQueryBuilder('p')
                      ->select('p')
                      ->where('p.quantity > 0');

        return $query->getQuery()->getResult();
    }

    /**
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function updateProduct(Product $product, User $user): bool
    {
        $em = $this->getEntityManager();
        $product->setUpdatedBy($user);
        $em->getUnitOfWork()->scheduleForUpdate($product);

        if (true === $em->getUnitOfWork()->isScheduledForUpdate($product)) {
            $em->flush();
            return true;
        }

        return true;
    }

    /**
     * @param int $categoryID
     * @return array
     * @internal param string $categoryName
     */
    public function getProductsByCategoryOnArray(int $categoryID): array
    {
        $query = $this->getEntityManager()
                      ->getRepository(Product::class)
                      ->createQueryBuilder('p')
                      ->where('p.category = :categoryID')
                      ->setParameter(':categoryID', $categoryID);

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getProductsByPromotion(Promotion $promotion): array
    {
        $query = $this->getEntityManager()
                      ->getRepository(Product::class)
                      ->createQueryBuilder('product')
                      ->join('product.promotion', 'promotion')
                      ->where('promotion = :promotion')
                      ->setParameter(':promotion', $promotion);

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getNonExistingProductsInPromotion(Promotion $promotion): array
    {
        $qb = $this->createQueryBuilder('product.promotion');

        $query = $this->getEntityManager()
                      ->getRepository(Product::class)
                      ->createQueryBuilder('product')
                      ->innerJoin('product.promotion', 'promotion', Join::WITH)
                      ->where('promotion <> :promotion')
                      ->orWhere('promotion.id is null')
                      ->setParameters([
                          ':promotion' => $promotion,
                  ]);

        return $query->getQuery()->getArrayResult();
    }
}
