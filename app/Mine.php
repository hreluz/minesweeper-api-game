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
     * @param $x
     * @param $y
     * @return float
     */
    public static function get_number_of_mines(int $difficulty, int $x, int $y)
    {
        return round(($x * $y) /10  * ($difficulty));
    }

    /**
     * @param int $difficulty
     * @param array $grid
     * @param int $x
     * @param int $y
     * @return array
     */
    public static function add_mines_to_grid(int $difficulty, array $grid, int $x, int $y)
    {
        $number_mines = self::get_number_of_mines($difficulty, $x, $y);

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
