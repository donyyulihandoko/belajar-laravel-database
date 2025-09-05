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
                'id_category' => 'P0007'
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
        $this->insertManyCategories();
        DB::table('categories')
            ->orderBy('id')
            ->chunk(10, function ($categories) {
                self::assertNotNull($categories);
                foreach ($categories as $category) {
                    Log::info(json_encode($category));
                };
            });
    }

    public function testQueryBuilderLazy()
    {
        $this->insertManyCategories();
        $collection =  DB::table('categories')
            ->orderBy('id')
            ->lazy(10)
            ->each(function ($item) {
                Log::info(json_encode($item));
            });

        self::assertNotNull($collection);
    }
}
