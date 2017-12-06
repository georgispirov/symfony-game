<?php

namespace AppBundle\Repository;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * OrderedProductsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderedProductsRepository extends EntityRepository implements IOrderedProductsRepository
{
    public function invokeFindByBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder($this->getClassMetadata()->getTableName());
    }

    /**
     * @param User $user
     * @return array
     */
    public function getOrderedProductsByUser(User $user): array
    {
        $query = $this->invokeFindByBuilder()->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     * @throws \Exception
     */
    public function addOrderedProduct(User $user, Product $product): bool
    {
        $em = $this->getEntityManager();

        $orderedProduct = new OrderedProducts();
        $orderedProduct->setUser($user);
        $orderedProduct->setOrderedDate(new \DateTime('now'));
        $orderedProduct->setConfirmed(false);
        $orderedProduct->setProduct($product);
        $orderedProduct->setTotalCheck($product->getPrice());

        $em->persist($orderedProduct);
        if (true === $em->getUnitOfWork()->isEntityScheduled($orderedProduct)) {
            $userMoney = $user->getMoney() - $product->getPrice();
            $em->flush();
            $user->setMoney($userMoney);
            $em->persist($user);
            $em->flush();
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findOrderedProductByID(int $id)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(OrderedProducts::class)->findOneBy(['id' => $id]);
    }
}
