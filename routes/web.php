<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'comfy'], function () use ($router) {

        $router->group(['prefix' => 'all'], function () use ($router) {
            $router->get('products',  ['uses' => 'DataController@getProducts']);
            $router->get('categories',  ['uses' => 'DataController@getCategories']);
        });

        $router->group(['prefix' => 'product'], function () use ($router) {
            $router->get('{id}', ['uses' => 'DataController@getProduct']);
            $router->get('by/category', ['uses' => 'DataController@getProductsByCategoryName']);
            $router->get('by/purchases', ['uses' => 'DataController@getTopPurchases']);
        });

        $router->group(['prefix' => 'purchase/all'], function () use ($router) {
            $router->get('count',  ['uses' => 'DataController@getAllCountPurchases']);
            $router->get('count/month',  ['uses' => 'DataController@getAllCountPurchasesByMonth']);
            $router->get('price',  ['uses' => 'DataController@getAllPricePurchases']);
            $router->get('price/month',  ['uses' => 'DataController@getAllPricePurchasesByMonth']);
            $router->get('category/count/{category}',  ['uses' => 'DataController@getAllCategoryCountPurchases']);
            $router->get('category/price/{category}',  ['uses' => 'DataController@getAllCategoryPricePurchases']);
            $router->get('names/count/{title}',  ['uses' => 'DataController@getAllNamesCountPurchases']);
            $router->get('names/price/{title}',  ['uses' => 'DataController@getAllNamesPricePurchases']);
        });

        $router->group(['prefix' => 'file'], function () use ($router) {
            $router->get('purchases/all',  ['uses' => 'DataController@downloadAllPurchasesFile']);
        });

    });


    $router->group(['prefix' => 'foxtrot'], function () use ($router) {

        $router->group(['prefix' => 'all'], function () use ($router) {
            $router->get('products',  ['uses' => 'DataController@getProducts']);
            $router->get('categories',  ['uses' => 'DataController@getCategories']);
        });

        $router->group(['prefix' => 'product'], function () use ($router) {
            $router->get('{id}', ['uses' => 'DataController@getProduct']);
            $router->get('by/category', ['uses' => 'DataController@getProductsByCategoryName']);
            $router->get('by/purchases', ['uses' => 'DataController@getTopPurchases']);
        });

        $router->group(['prefix' => 'purchase/all'], function () use ($router) {
            $router->get('count',  ['uses' => 'DataController@getAllCountPurchases']);
            $router->get('count/month',  ['uses' => 'DataController@getAllCountPurchasesByMonth']);
            $router->get('price',  ['uses' => 'DataController@getAllPricePurchases']);
            $router->get('price/month',  ['uses' => 'DataController@getAllPricePurchasesByMonth']);
            $router->get('category/count/{category}',  ['uses' => 'DataController@getAllCategoryCountPurchases']);
            $router->get('category/price/{category}',  ['uses' => 'DataController@getAllCategoryPricePurchases']);
            $router->get('names/count/{title}',  ['uses' => 'DataController@getAllNamesCountPurchases']);
            $router->get('names/price/{title}',  ['uses' => 'DataController@getAllNamesPricePurchases']);
        });

        $router->group(['prefix' => 'file'], function () use ($router) {
            $router->get('purchases/all',  ['uses' => 'DataController@downloadAllPurchasesFile']);
        });
    });

    $router->group(['prefix' => 'eldorado'], function () use ($router) {

        $router->group(['prefix' => 'all'], function () use ($router) {
            $router->get('products',  ['uses' => 'DataController@getProducts']);
            $router->get('categories',  ['uses' => 'DataController@getCategories']);
        });

        $router->group(['prefix' => 'product'], function () use ($router) {
            $router->get('{id}', ['uses' => 'DataController@getProduct']);
            $router->get('by/category', ['uses' => 'DataController@getProductsByCategoryName']);
            $router->get('by/purchases', ['uses' => 'DataController@getTopPurchases']);
        });

        $router->group(['prefix' => 'purchase/all'], function () use ($router) {
            $router->get('count',  ['uses' => 'DataController@getAllCountPurchases']);
            $router->get('count/month',  ['uses' => 'DataController@getAllCountPurchasesByMonth']);
            $router->get('price',  ['uses' => 'DataController@getAllPricePurchases']);
            $router->get('price/month',  ['uses' => 'DataController@getAllPricePurchasesByMonth']);
            $router->get('category/count/{category}',  ['uses' => 'DataController@getAllCategoryCountPurchases']);
            $router->get('category/price/{category}',  ['uses' => 'DataController@getAllCategoryPricePurchases']);
            $router->get('names/count/{title}',  ['uses' => 'DataController@getAllNamesCountPurchases']);
            $router->get('names/price/{title}',  ['uses' => 'DataController@getAllNamesPricePurchases']);
        });

        $router->group(['prefix' => 'file'], function () use ($router) {
            $router->get('purchases/all',  ['uses' => 'DataController@downloadAllPurchasesFile']);
        });
    });
});
