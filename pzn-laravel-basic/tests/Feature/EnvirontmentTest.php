<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnvirontmentTest extends TestCase
{
    // Tes apakah YOUTUBE hasilnya Rivaldo Ismir
    public function testGetEnv()
    {
        $youtube = env('YOUTUBE');
        self::assertEquals('Rivaldo Ismir', $youtube);
    }

    // test apakah nilai author sama dengan ALDO, namun di .env belum didefinisikan maka pake nilai defaultnya yaitu ALDO
    public function testDefaultEnv()
    {
        $author = env('AUTHOR', 'ALDO');
        self::assertEquals('ALDO', $author);
    }
}
