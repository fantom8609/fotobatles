<?php

include_once ROOT . '/models/Category.php';
include_once ROOT . '/models/Product.php';
include_once ROOT . '/components/Pagination.php';

class CatalogController
{

    public function actionIndex()
    {
    	  $categories = array();
        $categories = Category::getCategoriesList();

        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(6);

        require_once(ROOT . '/views/catalog/index.php');
        return true;
    }
     public function actionCategory($categoryId,$page=1)
    {
        /*echo "categoreID:".$categoryId;
        echo "page:".$page;*/
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $categoryProducts = array();
        $categoryProducts = Product::getProductsListByCategory($categoryId,$page);

        $total = Product::getTotalProductsInCategory($categoryId);

        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');
       
        require_once(ROOT . '/views/catalog/category.php');

        return true;
    }


}
