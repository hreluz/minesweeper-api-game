<?php

namespace Tests\Feature;

use App\Grid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Exception;
use Tests\TestCase;
use Tests\TestHelpers;

class SolveGridTest extends TestCase
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
    public function get_solved_grid_seven_by_seven_on_easy_difficulty()
    {
        Grid::create_grid(7, 7, 'EASY');
        $grid = Grid::first();

        $mines_positions = [
            1 => [4] ,
            2 => [1] ,
            3 => [4],
            4 => [3],
            5 => [4]
        ];

        $grid->update([
            'grid' => json_encode($this->force_mines_position($mines_positions, 7 ,7 ))
        ]);

        $my_solved_grid = [
          0 => [
              0 => 0,
              1 => 0,
              2 => 0,
              3 => 1,
              4 => 1,
              5 => 1,
              6 => 0
          ],
          1 => [
              0 => 1,
              1 => 1,
              2 => 1,
              3 => 1,
              4 => 99,
              5 => 1,
              6 => 0
          ],
          2 => [
              0 => 1,
              1 => 99,
              2 => 1,
              3 => 2,
              4 => 2,
              5 => 2,
              6 => 0
          ],
          3 => [
              0 => 1,
              1 => 1,
              2 => 2,
              3 => 2,
              4 => 99,
              5 => 1,
              6 => 0
          ],
          4 => [
              0 => 0,
              1 => 0,
              2 => 1,
              3 => 99,
              4 => 3,
              5 => 2,
              6 => 0
          ],
          5 => [
              0 => 0,
              1 => 0,
              2 => 1,
              3 => 2,
              4 => 99,
              5 => 1,
              6 => 0
          ],
          6 => [
              0 => 0,
              1 => 0,
              2 => 0,
              3 => 1,
              4 => 1,
              5 => 1,
              6 => 0

          ]
        ];
        $this->assertEquals($my_solved_grid, $grid->get_grid_solved());

    }


    /**
     *
     * GRID with mines
     *    0    1    2    3    4    5    6
     * --------------------------------------
     * 0| ___   *   ___  ___  ___  ___  ___
     * 1| ___  ___   *   ___   *   ___   *
     * 2| ___   *   ___   *   ___   *   ___
     * 3|  *   ___  ___  ___   *   ___  ___
     * 4| ___  ___   *    *   ___  ___   *
     * 5| ___   *   ___  ___   *   ___  ___
     * 6| *    ___   *    *   ___   *   ___
     *
     *
     *  SOLVED GRID
     *    0    1    2    3    4    5    6
     * --------------------------------------
     * 0|  1    *    2    2    1    2    1
     * 1|  2    3    *    3    *    3    *
     * 2|  2    *    3    *    4    *    2
     * 3|  *    3    4    4    *    3    2
     * 4|  2    3    *    *    3    3    *
     * 5|  2    *    5    5    *    3    2
     * 6|  *    3    *    *    3    *    1
     *
     *
     */

    /**
     * @test
     */
    public function get_solved_grid_seven_by_seven_on_super_hard_difficulty()
    {
        Grid::create_grid(7, 7, 'SUPER HARD');
        $grid = Grid::first();

        $mines_positions = [
            0 => [1] ,
            1 => [2, 4, 6],
            2 => [1,3,5],
            3 => [0,4],
            4 => [2,3,6],
            5 => [1,4],
            6 => [0,2,3,5]
        ];

        $grid->update([
            'grid' => json_encode($this->force_mines_position($mines_positions, 7 ,7 ))
        ]);


        $my_solved_grid = [
            0 => [
                0 => 1,
                1 => 99,
                2 => 2,
                3 => 2,
                4 => 1,
                5 => 2,
                6 => 1
            ],
            1 => [
                0 => 2,
                1 => 3,
                2 => 99,
                3 => 3,
                4 => 99,
                5 => 3,
                6 => 99
            ],
            2 => [
                0 => 2,
                1 => 99,
                2 => 3,
                3 => 99,
                4 => 4,
                5 => 99,
                6 => 2
            ],
            3 => [
                0 => 99,
                1 => 3,
                2 => 4,
                3 => 4,
                4 => 99,
                5 => 3,
                6 => 2
            ],
            4 => [
                0 => 2,
                1 => 3,
                2 => 99,
                3 => 99,
                4 => 3,
                5 => 3,
                6 => 99
            ],
            5 => [
                0 => 2,
                1 => 99,
                2 => 5,
                3 => 5,
                4 => 99,
                5 => 3,
                6 => 2
            ],
            6 => [
                0 => 99,
                1 => 3,
                2 => 99,
                3 => 99,
                4 => 3,
                5 => 99,
                6 => 1
            ]
        ];
        $this->assertEquals($my_solved_grid, $grid->get_grid_solved());

    }


    /**
     *
     * GRID with mines
     *    0    1    2    3    4    5    6    7    8
     * ----------------------------------------------
     * 0|  *   *     *    *  ___   *   ___  ___  ___
     * 1|  *   ___  ___   *   *   ___   *   ___   *
     * 2| ___   *   ___   *  ___   *   ___   *   ___
     * 3| ___  ___   *   ___  ___  ___  ___  ___  ___
     * 4|  *   ___  ___   *    *   ___   *   ___  ___
     * 5| ___   *    *   ___  ___  ___   *   ___   *
     * 6|  *   ___  ___  ___   *   ___  ___   *    *
     * 7| ___  ___   *   ___  ___   *   ___  ___  ___
     * 8| ___   *   ___  ___   *   ___  ___  ___   *
     *
     *
     *  SOLVED GRID
     *    0    1    2    3    4    5    6    7    8
     * ----------------------------------------------
     * 0|  *    *    *    *    4    *    2    2    1
     * 1|  *    5    6    *    *    4    *    3    *
     * 2|  2    *    4    *    4    *    3    *    2
     * 3|  2    3    *    4    4    3    3    2    1
     * 4|  *    4    4    *    *    3    *    3    1
     * 5|  3    *    *    4    3    4    *    5    *
     * 6|  *    4    3    3    *    3    3    *    *
     * 7|  2    3    *    3    3    *    2    3    3
     * 8|  1    *    2    2    *    2    1    1    *
     *
     *
     */

    /**
     * @test
     */
    public function get_solved_grid_nine_by_nine_on_hard_difficulty()
    {
        Grid::create_grid(9, 9, 'HARD');
        $grid = Grid::first();

        $mines_positions = [
            0 => [0,1,2,3,5] ,
            1 => [0,3,4,6,8],
            2 => [1,3,5,7],
            3 => [2],
            4 => [0,3,4,6],
            5 => [1,2,6,8],
            6 => [0,4,7,8],
            7 => [2,5],
            8 => [1,4,8]
        ];

        $grid->update([
            'grid' => json_encode($this->force_mines_position($mines_positions, 9 ,9 ))
        ]);

        $my_solved_grid = [
            0 => [
                0 => 99,
                1 => 99,
                2 => 99,
                3 => 99,
                4 => 4,
                5 => 99,
                6 => 2,
                7 => 2,
                8 => 1
            ],
            1 => [
                0 => 99,
                1 => 5,
                2 => 6,
                3 => 99,
                4 => 99,
                5 => 4,
                6 => 99,
                7 => 3,
                8 => 99
            ],
            2 => [
                0 => 2,
                1 => 99,
                2 => 4,
                3 => 99,
                4 => 4,
                5 => 99,
                6 => 3,
                7 => 99,
                8 => 2
            ],
            3 => [
                0 => 2,
                1 => 3,
                2 => 99,
                3 => 4,
                4 => 4,
                5 => 3,
                6 => 3,
                7 => 2,
                8 => 1
            ],
            4 => [
                0 => 99,
                1 => 4,
                2 => 4,
                3 => 99,
                4 => 99,
                5 => 3,
                6 => 99,
                7 => 3,
                8 => 1
            ],
            5 => [
                0 => 3,
                1 => 99,
                2 => 99,
                3 => 4,
                4 => 3,
                5 => 4,
                6 => 99,
                7 => 5,
                8 => 99
            ],
            6 => [
                0 => 99,
                1 => 4,
                2 => 3,
                3 => 3,
                4 => 99,
                5 => 3,
                6 => 3,
                7 => 99,
                8 => 99
            ],
            7 => [
                0 => 2,
                1 => 3,
                2 => 99,
                3 => 3,
                4 => 3,
                5 => 99,
                6 => 2,
                7 => 3,
                8 => 3
            ],
            8 => [
                0 => 1,
                1 => 99,
                2 => 2,
                3 => 2,
                4 => 99,
                5 => 2,
                6 => 1,
                7 => 1,
                8 => 99
            ]
        ];
        $this->assertEquals($my_solved_grid, $grid->get_grid_solved());

    }
}
