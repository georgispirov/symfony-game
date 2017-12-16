<?php

namespace AppBundle\Grid;

use AppBundle\Entity\Product;
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
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid, EntityManagerInterface $em): Grid
    {
        if ( !$grid instanceof Grid ) {
            throw new APYInvalidArgumentException('Applied argument must be instance of Grid!');
        }

        $grid->setHiddenColumns(['orderedProductID', 'viewProductID']);
        $grid->setLimits([5,5,5]);

        $massAction    = new MassAction('Checkout Selected', 'AppBundle:Cart:cartCheckout');

        $productColumn = new RowAction('View', 'viewProduct');
        $productColumn->setRouteParametersMapping(['viewProductID' => $grid->getColumn('viewProductID')]);

        $updateColumn = new RowAction('Update', 'updateOrderedProduct');
        $updateColumn->setRouteParametersMapping(['productID' => $grid->getColumn('orderedProductID')->getId()]);

        $deleteColumn = new RowAction('Delete', 'removeOrderedProduct');
        $deleteColumn->setRouteParametersMapping(['productID' => $grid->getColumn('orderedProductID')->getId()]);

        $grid->getColumn('orderedDate')->setTitle('Ordered Date')->setOperators([Column::OPERATOR_SLIKE]);
        $grid->getColumn('User')->setTitle('Seller')->setOperators([Column::OPERATOR_SLIKE]);

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
                return '$' . $value;
            });

        $grid->getColumn('Quantity')
            ->setFilterable(true)
            ->setFilterType('input')
            ->setOperators(['eq']);

        $grid->getColumn('Quantity')->manipulateRenderCell(
            function ($value, $row, $router) {
                return intval($value);
            }
        );

        $grid->getColumn('Price')->setOperators([Column::OPERATOR_EQ]);

        $grid->addRowAction($productColumn);
        $grid->addRowAction($updateColumn);
        $grid->addRowAction($deleteColumn);
        $grid->addMassAction($massAction);

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign('center');
        }

        return $grid;
    }
}