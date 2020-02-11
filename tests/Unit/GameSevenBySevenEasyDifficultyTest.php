<?php

namespace Tests\Feature;

use App\Grid;
use App\GridLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;
use Tests\TestCase;
use Tests\TestHelpers;

class GameSevenBySevenEasyDifficultyTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

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
    public function click_on_0_0_opens_3_more()
    {
        $grid = $this->get_custom_grid();
        $grid->click_on(0,0);

        $this->assertDatabaseHas('grids',[
            'finalized' => null
        ]);

        $this->assertDatabaseHas('grid_logs',[
            'grid_id' => $grid->id
        ]);

        $grid_log = GridLog::first();

        $this->assertEquals(
            json_decode($grid_log->grid, true),
            [[0,0,0,-1,-1,-1,-1],[-1,-1,-1,-1,99,-1,-1],[-1,99,-1,-1,-1,-1,-1],[-1,-1,-1,-1,99,-1,-1],[-1,-1,-1,99,-1,-1,-1],[-1,-1,-1,-1,99,-1,-1],[-1,-1,-1,-1,-1,-1,-1]]
        );

        $this->assertEquals(GridLog::count(), 1);
    }

    /**
     * @test
     */
    public function click_on_0_0_and_6_0_opens_10_more()
    {
        $grid = $this->get_custom_grid();
        $grid->click_on(0,0);
        $grid->click_on(6,0);

        $this->assertDatabaseHas('grids',[
            'finalized' => null
        ]);

        $grid_log = GridLog::orderBy('id', 'DESC')->first();

        $this->assertEquals(
            json_decode($grid_log->grid, true),
            [[0,0,0,-1,-1,-1,-1],[-1,-1,-1,-1,99,-1,-1],[-1,99,-1,-1,-1,-1,-1],[-1,-1,-1,-1,99,-1,-1],[0,0,-1,99,-1,-1,-1],[0,0,-1,-1,99,-1,-1],[0,0,0,-1,-1,-1,-1]]
        );

        $this->assertEquals(GridLog::count(), 2);
    }

    /**
     * @test
     */
    public function click_on_0_0_and_6_0_and_0_6_opens_17_more()
    {
        $grid = $this->get_custom_grid();
        $grid->click_on(0,0);
        $grid->click_on(6,0);
        $grid->click_on(0,6);

        $this->assertDatabaseHas('grids',[
            'status' => Grid::$status['PLAYING']
        ]);

        $grid_log = GridLog::orderBy('id', 'DESC')->first();

        $this->assertEquals(
            json_decode($grid_log->grid, true),
            [[0,0,0,-1,-1,-1,0],[-1,-1,-1,-1,99,-1,0],[-1,99,-1,-1,-1,-1,0],[-1,-1,-1,-1,99,-1,0],[0,0,-1,99,-1,-1,0],[0,0,-1,-1,99,-1,0],[0,0,0,-1,-1,-1,0]]
        );

        $grid_log = GridLog::orderBy('id', 'DESC')->first();
        $this->assertDatabaseHas('grid_logs',[
            'id' => $grid_log->id,
            'cells_opened' => 17
        ]);

        $this->assertEquals(GridLog::count(), 3);
    }

    /**
     * @test
     */
    public function click_everything_less_than_mines_and_end_the_game()
    {
        $grid = $this->get_custom_grid();
        $grid->click_on(0,0);
        $grid->click_on(6,0);
        $grid->click_on(0,6);

        $grid->click_on(0,3);
        $grid->click_on(0,4);
        $grid->click_on(0,5);

        $grid->click_on(1,0);
        $grid->click_on(1,1);
        $grid->click_on(1,2);
        $grid->click_on(1,3);
        $grid->click_on(1,5);

        $grid->click_on(2,0);
        $grid->click_on(2,2);
        $grid->click_on(2,3);
        $grid->click_on(2,4);
        $grid->click_on(2,5);

        $grid->click_on(3,0);
        $grid->click_on(3,1);
        $grid->click_on(3,2);
        $grid->click_on(3,3);
        $grid->click_on(3,5);

        $grid->click_on(4,2);
        $grid->click_on(4,4);
        $grid->click_on(4,5);

        $grid->click_on(5,2);
        $grid->click_on(5,3);
        $grid->click_on(5,5);

        $grid->click_on(6,3);
        $grid->click_on(6,4);
        $grid->click_on(6,5);

        $this->assertDatabaseHas('grids',[
            'status' => Grid::$status['WON']
        ]);

        $grid_log = GridLog::orderBy('id', 'DESC')->first();
        $this->assertDatabaseHas('grid_logs',[
            'id' => $grid_log->id,
            'cells_opened' => 44
        ]);

        $this->assertEquals(GridLog::count(), 30);
        $this->assertEquals(
            json_decode($grid_log->grid, true),
            [[0,0,0,1,1,1,0],[1,1,1,1,99,1,0],[1,99,1,2,2,2,0],[1,1,2,2,99,1,0],[0,0,1,99,3,2,0],[0,0,1,2,99,1,0],[0,0,0,1,1,1,0]]
        );
    }

    /**
     * @test
     */
    public function click_on_a_mine_ends_game()
    {
        $grid = $this->get_custom_grid();
        $grid->click_on(2,1);

        $this->assertDatabaseHas('grids',[
            'status' => Grid::$status['LOST'],
           'finalized' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * @test
     */
    public function click_on_non_existing_X_cell()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('X is out of range');

        $grid = $this->get_custom_grid();
        $grid->click_on(88,1);
    }

    /**
     * @test
     */
    public function click_on_non_existing_Y_cell()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Y is out of range');

        $grid = $this->get_custom_grid();
        $grid->click_on(1,88);
    }

    private function  get_custom_grid()
    {
        $mines_positions = [
            1 => [4] ,
            2 => [1] ,
            3 => [4],
            4 => [3],
            5 => [4]
        ];
        return $this->get_grid(7,7, $mines_positions, 'SUPER EASY');
    }
}
