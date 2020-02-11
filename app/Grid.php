<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Grid extends Model
{
    protected  $fillable  = [
        'grid', 'x', 'y' ,'difficulty','started'
    ];

    public static $difficulties  = [
        'SUPER EASY' => 1,
        'EASY' => 2,
        'NORMAL' => 3,
        'HARD' => 4,
        'SUPER HARD' => 5
    ];


    /**
     * @param int $x
     * @param int $y
     * @param string $difficulty
     * @return Grid|array
     * @throws Exception
     */
    public static function create_grid(int $x, int $y, string $difficulty)
    {
        if($x < 6 || $y < 6) {
            if($x < 6 ) {
                throw new Exception('X cannot be less than 6');
            } else {
                throw new Exception('Y cannot be less than 6');
            }
        }

        if(!isset(self::$difficulties[$difficulty])) {
            throw new Exception('Difficulty does not exist');
        }

        $grid = [];

        for ($i = 0 ; $i < $x ; $i++) {
            $grid[$i] = [];
            for($j = 0 ; $j < $y ; $j++){
                $grid[$i][$j] = -1;
            }
        }

        $grid = Mine::add_mines_to_grid(self::$difficulties[$difficulty], $grid, $x, $y);
        $grid = new Grid([
            'grid' => json_encode($grid),
            'x' => $x,
            'y' => $y,
            'difficulty' => self::$difficulties[$difficulty],
            'started' => now()
        ]);

        $grid->save();

        return $grid;
    }

    /**
     * @return array
     */
    public function get_grid_solved()
    {
        $grid = json_decode($this->grid);
        for ($i = 0 ; $i < $this->x ; $i++ ) {
            for ($j = 0 ; $j < $this->y ; $j++) {

                if($grid[$i][$j] == 99) {
                    continue;
                }

                $mines_number = 0;
                $positions = [0, 1, -1];

                foreach ($positions as $x) {
                    foreach ($positions as $y){
                        if(isset($grid[$i + $x][$j + $y]) && $grid[$i + $x][$j + $y] == 99 ){
                            $mines_number++;
                        }
                    }
                }
                $grid[$i][$j] = $mines_number;
            }

        }

        return $grid;
    }


}
