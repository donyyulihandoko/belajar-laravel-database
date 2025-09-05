<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;


class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM categories");
    }
    public function testDatabaseTransaction()
    {
        DB::transaction(function () {
            DB::insert(
                "INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)",
                [
                    'id' => 'GADGET',
                    'name' => 'Samsung Galaksi A4',
                    'description' => 'Gadget dengan merek Samsung Series A4'
                ]
            );

            DB::insert(
                "INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)",
                [
                    'id' => 'Laptop',
                    'name' => 'Macbook Air M1 2020',
                    'description' => 'Laptop Macbook Air M1 2020'
                ]
            );
        });

        $result = DB::select("SELECT * FROM categories");

        self::assertCount(2, $result);
    }

    public function testDatabaseTransactionFailed()
    {
        try {
            DB::transaction(function () {
                DB::insert(
                    "INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)",
                    [
                        'id' => 'Laptop',
                        'name' => 'Samsung Galaksi A4',
                        'description' => 'Gadget dengan merek Samsung Series A4'
                    ]
                );

                DB::insert(
                    "INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)",
                    [
                        'id' => 'Laptop',
                        'name' => 'Macbook Air M1 2020',
                        'description' => 'Laptop Macbook Air M1 2020'
                    ]
                );
            });
        } catch (QueryException $exception) {
            // expected
        }

        $result = DB::select("SELECT * FROM categories");

        self::assertCount(0, $result);
    }

    public function testManualDatabaseTransaction()
    {
        try {
            DB::beginTransaction();
            DB::insert(
                "INSERT INTO categories (id, name, description) values(?,?,?)",
                [
                    'P0001',
                    'Laptop',
                    'Laptop Description'
                ]
            );
            DB::insert(
                "INSERT INTO categories (id, name, description) values(?,?,?)",
                [
                    'P0002',
                    'Laptop',
                    'Laptop Description'
                ]
            );
            DB::commit();
        } catch (QueryException $exception) {
            DB::rollBack();
        }

        $result = DB::select("SELECT * FROM categories");

        self::assertCount(2, $result);
    }

    public function testManualDatabaseTransactionFailed()
    {
        try {
            DB::beginTransaction();
            DB::insert(
                "INSERT INTO categories (id, name, description) values(?,?,?)",
                [
                    'P0001',
                    'Laptop',
                    'Laptop Description'
                ]
            );
            DB::insert(
                "INSERT INTO categories (id, name, description) values(?,?,?)",
                [
                    'P0001',
                    'Laptop',
                    'Laptop Description'
                ]
            );
            DB::commit();
        } catch (QueryException $exception) {
            DB::rollBack();
        }

        $result = DB::select("SELECT * FROM categories");

        self::assertCount(0, $result);
    }
}
