<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Helpers\Helper;
use App\Product;
use App\Purchase;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function getCategories(Request $request)
    {
        $data = DB::connection(Helper::getCurrentShopName($request->path()))->table('categories')->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getProductsByCategoryName(Request $request)
    {
        $category = Category::on(Helper::getCurrentShopName($request->path()))->where('title', $request->input('title'))->first();
        if (is_null($category)) {
            $data = false;
        } else {
            $data = Product::on(Helper::getCurrentShopName($request->path()))
            ->with(['brand', 'purchases', 'category'])
            ->whereHas('category', function($query) use ($category) {
                $query->where('id', $category->id);
            })->get();
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getProducts(Request $request)
    {
        $data = Product::on(Helper::getCurrentShopName($request->path()))->with(['brand', 'category', 'purchases'])->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getProduct($id, Request $request)
    {
        $data = Product::on(Helper::getCurrentShopName($request->path()))->with(['brand', 'category', 'purchases'])->where('id', $id)->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getTopPurchases(Request $request)
    {
        $limit = 3;

        $connection = Helper::getCurrentShopName($request->path());
        $ids_data = DB::connection($connection)
            ->table('purchases')
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('product_id, count(`id`) as count')
            ->orderByRaw('count(`id`) desc')
            ->groupBy('product_id')
            ->limit($limit)
            ->get();

        $products = [];

        foreach ($ids_data as $item) {
            $products[] = [
                'amount' => $item->count,
                'shop' => $connection,
                'product' => $product = Product::on($connection)
                    ->with(['brand', 'category', 'purchases'])
                    ->where('id', $item->product_id)
                    ->first(),
            ];
        }

        return response()->json([
            'data' => $products,
            'ids' => $ids_data
        ], 200);
    }

    public function getAllCountPurchases(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());
        $data = DB::connection($connection)
            ->table('purchases')
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('count(`id`) as count, DATE_FORMAT(date, \'%Y-%m-%d\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m-%d\')'))
            ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function getAllCountPurchasesByMonth(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());
        $data = DB::connection($connection)
            ->table('purchases')
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('count(`id`) as count, DATE_FORMAT(date, \'%Y-%m\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m\')'))
            ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function getAllPricePurchases(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());
        $data = DB::connection($connection)
            ->table('purchases')
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('sum(`price`) as price, DATE_FORMAT(date, \'%Y-%m-%d\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m-%d\')'))
            ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function getAllPricePurchasesByMonth(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());
        $data = DB::connection($connection)
            ->table('purchases')
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('sum(`price`) as price, DATE_FORMAT(date, \'%Y-%m\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m\')'))
            ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function getAllCategoryCountPurchases(Request $request, $category)
    {
        $connection = Helper::getCurrentShopName($request->path());

        $data = Purchase::on($connection)
            ->with('products.category')
            ->whereHas('products.category', function ($query) use ($category) {
                $query->where('title', urldecode($category));
            })
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('count(`id`) as count, DATE_FORMAT(date, \'%Y-%m\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m\')'))
            ->get();

        if (count($data) < 1) {
            $data = false;
        }

        return response()->json($data, 200)
            ->withCallback($request->input('callback'));
    }

    public function getAllCategoryPricePurchases(Request $request, $category)
    {
        $connection = Helper::getCurrentShopName($request->path());

        $data = Purchase::on($connection)
            ->with('products.category')
            ->whereHas('products.category', function ($query) use ($category) {
                $query->where('title', urldecode($category));
            })
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('sum(`price`) as price, DATE_FORMAT(date, \'%Y-%m\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m\')'))
            ->get();

        if (count($data) < 1) {
            $data = false;
        }

        return response()->json($data, 200)
            ->withCallback($request->input('callback'));
    }

    public function getAllNamesCountPurchases(Request $request, $title)
    {
        $connection = Helper::getCurrentShopName($request->path());

        $data = Purchase::on($connection)
            ->with('products')
            ->whereHas('products', function ($query) use ($title) {
                $query->where('title', 'like', '%'.urldecode($title).'%');
            })
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('count(`id`) as count, DATE_FORMAT(date, \'%Y-%m-%d\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m-%d\')'))
            ->get();

        if (count($data) < 1) {
            $data = false;
        }

        return response()->json($data, 200)
            ->withCallback($request->input('callback'));
    }

    public function getAllNamesPricePurchases(Request $request, $title)
    {
        $connection = Helper::getCurrentShopName($request->path());

        $data = Purchase::on($connection)
            ->with('products')
            ->whereHas('products', function ($query) use ($title) {
                $query->where('title', 'like', '%'.urldecode($title).'%');
            })
            ->where(function ($query) use ($request) {
                if ($request->input('dates')) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('sum(`price`) as price, DATE_FORMAT(date, \'%Y-%m-%d\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m-%d\')'))
            ->get();

        if (count($data) < 1) {
            $data = false;
        }

        return response()->json($data, 200)
            ->withCallback($request->input('callback'));
    }

    public function getProductInfoByName(Request $request, $title)
    {
        $connection = Helper::getCurrentShopName($request->path());
        $product = Product::on($connection)
            ->where('title', $title)
            ->with(['brand', 'category'])
            ->first();

        $purchases = Purchase::on($connection)
            ->with('products')
            ->whereHas('products', function ($query) use ($title) {
                $query->where('title', 'like', '%'.urldecode($title).'%');
            })
            ->selectRaw('sum(`price`) as price, count(`id`) as cnt, DATE_FORMAT(date, \'%Y-%m-%d\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m-%d\')'))
            ->get();

        return response()->json([
            'data' => [
                'product' => $product,
                'purchases' => $purchases,
            ],
        ], 200);
    }

    public function allGeneralFile(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());

        $brands = Brand::on($connection)->get();
        $categories = Category::on($connection)->get();
        $products = Product::on($connection)->with('brand')->get();

        return response()->json([
            'data' => [
                'brands' => $brands,
                'categories' => $categories,
                'products' => $products,
            ],
        ], 200);
    }

    public function allPurchasesFile(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());
        $data = DB::connection($connection)
            ->table('purchases')
            ->where(function ($query) use ($request) {
                if (!empty($request->input('from')) && !empty($request->input('to'))) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('date', [$from, $to]);
                }
            })
            ->selectRaw('sum(`price`) as price, count(`id`) as count, DATE_FORMAT(date, \'%Y-%m\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m\')'))
            ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function purchasesInfoFile(Request $request)
    {
        $connection = Helper::getCurrentShopName($request->path());

        $counts = DB::connection($connection)
            ->table('purchases as p')
            ->join('products as prod', 'p.product_id', '=', 'prod.id')
            ->where(function ($query) use ($request) {
                if (!empty($request->input('from')) && !empty($request->input('to'))) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('p.date', [$from, $to]);
                }
            })
            ->selectRaw('count(p.product_id) as count, prod.title as title')
            ->groupBy('p.product_id')
            ->orderByRaw('count(p.product_id) desc')
            ->get();

        $prices = DB::connection($connection)
            ->table('purchases as p')
            ->join('products as prod', 'p.product_id', '=', 'prod.id')
            ->where(function ($query) use ($request) {
                if (!empty($request->input('from')) && !empty($request->input('to'))) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('p.date', [$from, $to]);
                }
            })
            ->selectRaw('max(p.price) as price, prod.title as title')
            ->groupBy('p.id')
            ->orderByRaw('max(p.price) desc')
            ->get();

        $dates = DB::connection($connection)
            ->table('purchases as p')
            ->where(function ($query) use ($request) {
                if (!empty($request->input('from')) && !empty($request->input('to'))) {
                    $from = Carbon::createFromFormat('d-m-Y', $request->input('from'))->toDateString();
                    $to = Carbon::createFromFormat('d-m-Y', $request->input('to'))->toDateString();
                    $query->whereBetween('p.date', [$from, $to]);
                }
            })
            ->selectRaw('count(p.id) as count, DATE_FORMAT(p.date, \'%Y-%m-%d\') as date')
            ->groupBy(DB::connection($connection)->raw('DATE_FORMAT(date, \'%Y-%m-%d\')'))
            ->orderByRaw('count(p.id) desc')
            ->get();

        $max_price = $prices->first();
        $min_price = $prices->last();

        $max_count = $counts->first();
        $min_count = $counts->last();

        $max_date = $dates->first();
        $min_date = $dates->last();

        $data = [
            'max_price' => $max_price,
            'min_price' => $min_price,
            'max_count' => $max_count,
            'min_count' => $min_count,
            'max_date' => $max_date,
            'min_date' => $min_date,
        ];

        return response()->json([
            'data' => $data,
        ], 200);
    }
}
