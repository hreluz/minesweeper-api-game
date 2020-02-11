<?php

namespace Tests;

use App\Grid;
use App\Mine;

trait TestHelpers
{
    /***
     * @param int $x
     * @param int $y
     * @param array $mines_positions
     * @param string $difficulty
     * @return mixed
     * @throws \Exception
     */
    public function get_grid(int $x, int $y, array $mines_positions, string $difficulty)
    {
        Grid::create_grid($x, $y, $difficulty);

        $grid = Grid::first();

        $grid->update([
            'grid' => json_encode($this->force_mines_position($mines_positions, $x ,$y )),
        ]);

        $grid->update([
            'free_spaces' =>  $x*$y - Mine::get_number_of_mines(Grid::$difficulties[$difficulty], $x, $y)
        ]);

        return $grid;
    }

    /**
     * @param array $positions
     * @param int $x
     * @param int $y
     * @return array
     */
    private function force_mines_position(array $positions, int $x, int $y) {
        $grid = [];

        for ($i = 0; $i < $x ; $i++ ){
            $grid[$i] = [];
            for ($j = 0 ; $j < $y ; $j++) {
                $grid[$i][$j] = -1;

            }

            if(!isset($positions[$i])) {
                continue;
            }

            foreach ($positions[$i] as $k) {
                $grid[$i][$k] = 99;
            }
        }

        return $grid;
    }
}