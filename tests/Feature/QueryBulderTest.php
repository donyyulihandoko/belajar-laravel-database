<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryBulderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from products");
        DB::delete("delete from categories");
        DB::delete("delete from counters");
    }

    public function testQueryBuilderInsert()
    {
        DB::table('categories')->insert([
            'id' => 'P0001',
            'name' => 'Laptop',
            'description' => 'Laptop Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0002',
            'name' => 'Smartphone',
            'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0003',
            'name' => 'SmartTV',
            'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0004',
            'name' => 'Smartwatch',
            'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0005',
            'name' => 'Ipad',
            // 'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0006',
            'name' => 'Iwatch',
            // 'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0007',
            'name' => 'Macbook',
            // 'description' => 'Smartphone Deskription'
        ]);
        DB::table('categories')->insert([
            'id' => 'P0008',
            'name' => 'Lain lain',
            // 'description' => 'Smartphone Deskription'
        ]);

        $result = DB::select('SELECT count(id) AS total FROM categories',);
        self::assertEquals(8, $result[0]->total);
    }

    public function testQueryBuilderSelect()
    {
        $this->testQueryBuilderInsert();

        $collection = DB::table('categories')->select('id', 'name')->get();

        self::assertNotNull($collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhere()
    {
        $this->testQueryBuilderInsert();

        $collection = DB::table('categories')->where(function (QueryBuilder $builder) {
            $builder->where('id', '=', 'P0001');
            $builder->orWhere('id', '=', 'P0002');
        })->get();

        self::assertCount(2, $collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhereBetween()
    {
        $this->testQueryBuilderInsert();
        $collection = DB::table('categories')->whereBetween('created_at', ['2025-08-02 10:23:22', '2025-10-02 10:23:22'])->get();
        self::assertCount(8, $collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhereIn()
    {
        $this->testQueryBuilderInsert();

        $collection = DB::table('categories')->whereIn('id', ['P0003', 'P0004'])->get();

        self::assertCount(2, $collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhereNull()
    {
        $this->testQueryBuilderInsert();
        $collection = DB::table('categories')->whereNull('description')->get();
        self::assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderWhereDate()
    {
        $this->testQueryBuilderInsert();

        $collection = DB::table('categories')->whereDate('created_at', '2025-09-03')->get();
        self::assertCount(0, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderUpdate()
    {
        $this->testQueryBuilderInsert();
        DB::table('categories')->where('id', '=', 'P0001')->update(['name' => 'Update']);
        $collection = DB::table('categories')->where('name', '=', 'Update')->get();
        self::assertCount(1, $collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderUpdateORInsert()
    {
        DB::table('categories')->updateOrInsert(
            [
                'id' => 'P000x'
            ],
            [
                'name' => 'Laptop',
                'description' => 'Laptop Deskripsi'
            ]
        );

        $colllection = DB::table('categories')->where('id', '=', 'P000x')->get();
        self::assertCount(1, $colllection);

        $colllection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderIncrement()
    {
        DB::table('counters')->where('id', '=', 'sample')->increment('counter', 1);
        $collection = DB::table('counters')->where('id', 'sample')->get();
        self::assertCount(0, $collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderDelete()
    {
        $this->testQueryBuilderInsert();
        DB::table('categories')->where('id', 'P0008')->delete();

        $colllection = DB::table('categories')->where('id', 'P0008')->get();
        self::assertCount(0, $colllection);
    }

    public function insertProducts()
    {
        $this->testQueryBuilderWhere();
        DB::table('products')->insert(
            [
                'id' => 'product1',
                'name' => 'Macbook Pro M1 2020',
                'price' => 25_000_000,
                'id_category' => 'P0001'
            ]
        );
        DB::table('products')->insert(
            [
                'id' => 'product2',
                'name' => 'Asus Zenbook Duo',
                'price' => 17_500_000,
                'id_category' => 'P0001'
            ]
        );
        DB::table('products')->insert(
            [
                'id' => 'product3',
                'name' => 'Iphone X',
                'price' => 11_000_000,
                'id_category' => 'P0002'
            ]
        );
        DB::table('products')->insert(
            [
                'id' => 'product4',
                'name' => 'Samsung Galaksi A3',
                'price' => 10_000_000,
                'id_category' => 'P0002'
            ]
        );
    }

    public function testJoinTableProducts()
    {
        $this->insertProducts();
        $collection = DB::table('products')
            ->join('categories', 'products.id_category', 'categories.id')
            ->select(['products.id', 'products.name', 'categories.name as category_name', 'products.price'])
            ->get();

        self::assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderOrder()
    {
        $this->insertProducts();
        $collection = DB::table('products')
            ->whereNotNull('id')
            ->orderBy('price', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        self::assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testQueryBuilderPaging()
    {
        $this->insertProducts();
        $collection = DB::table('products')
            ->skip(2)
            ->limit(2)
            ->get();

        self::assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function insertManyCategories()
    {
        for ($a = 1; $a < 100; $a++) {
            DB::table('categories')->insert([
                'id' => 'P000' . $a,
                'name' => 'Categories' . $a,
                'description' => 'description category' . $a
            ]);
        }
    }
    public function testQueryBuilderChunk()
    {
        // $this->insertManyCategories();
        $this->testQueryBuilderInsert();
        DB::table('categories')
            ->orderBy('id')
            ->chunk(1, function ($categories) {
                self::assertNotNull($categories);
                foreach ($categories as $category) {
                    Log::info(json_encode($category));
                };
            });
    }

    public function testQueryBuilderLazy()
    {
        // $this->insertManyCategories();
        $this->testQueryBuilderInsert();

        $collection =  DB::table('categories')
            ->orderBy('id')
            ->lazy(1)
            ->each(function ($item) {
                Log::info(json_encode($item));
            });

        self::assertNotNull($collection);
    }

    public function testQueryBuilderAgregate()
    {
        $this->insertProducts();
        $result = DB::table('products')
            ->min('price');
        self::assertEquals(10_000_000, $result);
    }

    public function testRawQueryBuilder()
    {
        $this->insertProducts();
        $collection  =  DB::table('products')
            ->select(
                DB::raw('count(id) as total_product'),
                DB::raw('min(price) as min_price'),
                DB::raw('max(price) as max_price')
            )->get();

        self::assertEquals(4, $collection[0]->total_product);
        self::assertEquals(10_000_000, $collection[0]->min_price);
        self::assertEquals(25_000_000, $collection[0]->max_price);
    }

    public function insertProducts2()
    {
        DB::table('products')->insert(
            [
                'id' => 'product5',
                'name' => 'Laptop1',
                'price' => 15_000_000,
                'id_category' => 'P0001'
            ]
        );
        DB::table('products')->insert(
            [
                'id' => 'product6',
                'name' => 'Laptop2',
                'price' => 15_500_000,
                'id_category' => 'P0001'
            ]
        );
        DB::table('products')->insert(
            [
                'id' => 'product7',
                'name' => 'Smartphone1',
                'price' => 12_000_000,
                'id_category' => 'P0002'
            ]
        );
        DB::table('products')->insert(
            [
                'id' => 'product8',
                'name' => 'Smartphone2',
                'price' => 12_000_000,
                'id_category' => 'P0002'
            ]
        );
    }

    public function testQueryBuilderGrouping()
    {
        $this->insertProducts();
        $this->insertProducts2();

        $collection = DB::table('products')
            ->select(
                'id_category',
                DB::raw('count(*) as total_product')
            )
            ->groupBy('id_category')
            ->orderBy('id_category', 'desc')
            ->get();

        self::assertCount(2, $collection);
        self::assertEquals('P0002', $collection[0]->id_category);
        self::assertEquals('P0001', $collection[1]->id_category);
        self::assertEquals(4, $collection[0]->total_product);
        self::assertEquals(4, $collection[1]->total_product);
    }

    public function testQueryBuilderHaving()
    {
        $this->insertProducts();
        $this->insertProducts2();

        $collection = DB::table('products')
            ->select(
                'id_category',
                DB::raw('count(*) as total_product')
            )
            ->groupBy('id_category')
            ->having(DB::raw('count(*)'), '>', 5)
            ->orderBy('id_category', 'desc')
            ->get();

        self::assertCount(0, $collection);
        // self::assertEquals('P0002', $collection[0]->id_category);
        // self::assertEquals('P0001', $collection[1]->id_category);
        // self::assertEquals(4, $collection[0]->total_product);
        // self::assertEquals(4, $collection[1]->total_product);
    }

    public function testQueryBuilderLocking()
    {
        $this->insertProducts();
        DB::transaction(function () {
            $collection = DB::table('products')
                ->where('id', 'product1')
                ->lockForUpdate()
                ->get();
            self::assertCount(1, $collection);
        });
    }

    public function testPagination()
    {
        $this->testQueryBuilderInsert();

        $paginate = DB::table("categories")->paginate(perPage: 2, page: 2);

        self::assertEquals(2, $paginate->currentPage());
        self::assertEquals(2, $paginate->perPage());
        self::assertEquals(4, $paginate->lastPage());
        self::assertEquals(8, $paginate->total());

        $collection = $paginate->items();
        self::assertCount(2, $collection);
        foreach ($collection as $item) {
            Log::info(json_encode($item));
        }
    }

    public function testIterateAllPagination()
    {
        $this->testQueryBuilderInsert();


        $page = 1;

        while (true) {
            $paginate = DB::table("categories")->paginate(perPage: 2, page: $page);

            if ($paginate->isEmpty()) {
                break;
            } else {
                $page++;

                $collection = $paginate->items();
                self::assertCount(2, $collection);
                foreach ($collection as $item) {
                    Log::info(json_encode($item));
                }
            }
        }
    }

    public function testCursorPagination()
    {
        $this->testQueryBuilderInsert();


        $cursor = "id";
        while (true) {
            $paginate = DB::table("categories")->orderBy("id")->cursorPaginate(perPage: 2, cursor: $cursor);

            foreach ($paginate->items() as $item) {
                self::assertNotNull($item);
                Log::info(json_encode($item));
            }

            $cursor = $paginate->nextCursor();
            if ($cursor == null) {
                break;
            }
        }
    }
}
