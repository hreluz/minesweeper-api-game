<?php

namespace Tests\Feature;

use App\Grid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;
use Tests\TestCase;
use Tests\TestHelpers;

class CreateGridTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

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



    /**
     *
     * GRID with mines
     *    0    1    2    3    4    5    6
     * --------------------------------------
     * 0| ___  ___  ___  ___  ___  ___  ___
     * 1| ___  ___  ___  ___   *   ___  ___
     * 2| ___   *   ___  ___  ___  ___  ___
     * 3| ___  ___  ___  ___   *   ___  ___
     * 4| ___  ___  ___   *   ___  ___  ___
     * 5| ___  ___  ___  ___   *   ___  ___
     * 6| ___  ___  ___  ___  ___  ___  ___
     *
     *
     *  SOLVED GRID
     *    0    1    2    3    4    5    6
     * --------------------------------------
     * 0|  0    0    0    1    1    1    0
     * 1|  1    1    1    1    *    1    0
     * 2|  1    *    1    2    2    2    0
     * 3|  1    1    2    2    *    1    0
     * 4|  0    0    1    *    3    2    0
     * 5|  0    0    1    2    *    1    0
     * 6|  0    0    0    1    1    1    0
     *
     *
     */

    /**
     * @test
     */
    public function check_free_spaces_are_added_to_created_grid()
    {
        $mines_positions = [
            1 => [4] ,
            2 => [1] ,
            3 => [4],
            4 => [3],
            5 => [4]
        ];

        $this->get_grid(7, 7, $mines_positions, 'SUPER EASY');
        $this->assertDatabaseHas('grids',[
            'free_spaces' => 44,
            'finalized' => null
        ]);
    }
}
