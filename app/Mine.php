<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mine extends Model
{
    /**
     * 99 = is a mine
     */


    /**
     * @param $difficulty
     * @param array $grid
     * @param int $x
     * @param int $y
     * @return array
     */
    public static function add_mines_to_grid($difficulty, array $grid, int $x, int $y)
    {
        $number_mines = round(($x * $y) /10  * ($difficulty));

        for ($i  = 0 ; $i < $number_mines; $i++ ) {
            $mine_x = rand(0, $x -1);
            $mine_y = rand(0, $y -1);

            if($grid[$mine_x][$mine_y] == 99) {
                $i--;
                continue;
            }

            $grid[$mine_x][$mine_y] = 99;
        }

        return $grid;
    }
}
