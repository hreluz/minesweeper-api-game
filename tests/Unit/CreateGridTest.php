<?php

namespace Tests\Feature;

use App\Grid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Exception;
use Tests\TestCase;

class CreateGridTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function check_grid_is_created()
    {
        Grid::create_grid(6, 6, 'EASY');
        $grid_decode = json_decode((Grid::first())->grid, true);

        foreach ($grid_decode as $x => $y) {
            $this->assertEquals(count($y), 6);
        }

        $this->assertEquals(count($grid_decode), 6);
        $this->assertDatabaseHas('grids', [
            'x' => 6,
            'y' => 6,
            'difficulty' => Grid::$difficulties['EASY'],
            'started' => now()
        ]);
    }

    /**
     * @test
     */
    public function check_x_cannot_be_negative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('X cannot be less than 6');

        Grid::create_grid(-1, 6, 'EASY');
    }

    /**
     * @test
     */
    public function check_y_cannot_be_negative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Y cannot be less than 6');

        Grid::create_grid(7, -16, 'EASY');
    }

    /**
     * @test
     */
    public function check_difficulty_exists()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Difficulty does not exist');

        Grid::create_grid(6, 6, 'Difficulty does not exist');
    }
}
