<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RawQueryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM categories");
    }

    public function testCrud()
    {
        DB::insert("INSERT INTO categories(id, name, description) VALUES (?, ?, ?)", ['GADGET', 'Samsung Galaksi A4', 'Gadget dengan merek Samsung Series A4']);

        $result = DB::select('SELECT * FROM categories WHERE id = ?', ['GADGET']);

        self::assertEquals(1, count($result));
        self::assertEquals('GADGET', $result[0]->id);
        self::assertEquals('Samsung Galaksi A4', $result[0]->name);
        self::assertEquals('Gadget dengan merek Samsung Series A4', $result[0]->description);
    }

    public function testCrudNamedBinding()
    {
        DB::insert(
            "INSERT INTO categories(id, name, description) VALUES (:id, :name, :description)",
            [
                'id' => 'GADGET',
                'name' => 'Samsung Galaksi A4',
                'description' => 'Gadget dengan merek Samsung Series A4'
            ]
        );

        $result = DB::select('SELECT * FROM categories WHERE id = ?', ['GADGET']);

        self::assertEquals(1, count($result));
        self::assertEquals('GADGET', $result[0]->id);
        self::assertEquals('Samsung Galaksi A4', $result[0]->name);
        self::assertEquals('Gadget dengan merek Samsung Series A4', $result[0]->description);
    }

   
}
