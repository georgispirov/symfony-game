<?php

namespace AppBundle\Grid;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Exception\InvalidArgumentException;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Exception\InvalidArgumentException as APYInvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class CartGrid implements CartGridInterface
{
    /**
     * @param Grid $grid
     * @param EntityManagerInterface $em
     * @param User $user
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid, EntityManagerInterface $em, User $user): Grid
    {
        if ( !$grid instanceof Grid ) {
            throw new APYInvalidArgumentException('Applied argument must be instance of Grid!');
        }

        $grid->setHiddenColumns(['orderedProductID', 'viewProductID']);
        $grid->setLimits([5,5,5]);

        $massAction    = new MassAction('Checkout Selected', 'AppBundle:Cart:cartCheckout');

        $productID = $grid->getColumn('viewProductID');

        $productColumn = new RowAction('View Product', 'viewProduct');
        $productColumn->setRouteParameters($productID->getId());

        $deleteColumn = new RowAction('Delete Any', 'removeOrderedProduct');
        $deleteColumn->setRouteParametersMapping(['productID' => $grid->getColumn('orderedProductID')->getId()]);

        $grid->getColumn('orderedDate')->setTitle('Ordered Date')->setOperators([])->setFilterable(false);

        $grid->getColumn('Product')->manipulateRenderCell(function ($value, $row, $router) use ($em) {
            /* @var $value  string */
            /* @var $row    \APY\DataGridBundle\Grid\Row */
            /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
            $product = $em->getRepository(Product::class)->findOneBy(['title' => $value]);

            if ( !$product instanceof Product ) {
                throw new InvalidArgumentException('Applied Ordered Product must be valid to be represented Product image.');
            }

            return $product->getImage();
        })->setFilterable(false)
          ->setOperators([]);

        $grid->getColumn('Price')
             ->manipulateRenderCell(function ($value, $row, $router) {
                /* @var $value  integer */
                /* @var $row    \APY\DataGridBundle\Grid\Row */
                /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
//                 return "$" . $em->getRepository(OrderedProducts::class)->getCheckoutFromSpecificProduct($user);
                 /*
                  * Note that there will be some possible issues with this concatenating
                  * in case of that the rendering cell should contains price with [[$]] prefix.
                  * In some cases there will be datetime value. This is APYDataGrid Bundle bug.
                  */
                 return "$" . $value;
             });

        $grid->getColumn('Quantity')
            ->setFilterable(true)
            ->setFilterType('input')
            ->setOperators([])
            ->setFilterable(false);

        $grid->getColumn('Quantity')->manipulateRenderCell(
            function ($value, $row, $router) {
                return 'All Ordered: ' . intval($value);
            }
        );

        $grid->getColumn('Price')
             ->setOperators([])
             ->setFilterable(false);

        $grid->addRowAction($productColumn);
        $grid->addRowAction($deleteColumn);
        $grid->addMassAction($massAction);

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign('center');
        }

        return $grid;
    }
}