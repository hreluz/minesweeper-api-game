<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Grid extends Model
{
    protected  $fillable  = [
        'grid', 'x', 'y' ,'difficulty','started','free_spaces'
    ];

    public static $difficulties  = [
        'SUPER EASY' => 1,
        'EASY' => 2,
        'NORMAL' => 3,
        'HARD' => 4,
        'SUPER HARD' => 5
    ];

    public static $status = [
        'PLAYING' => 1,
        'WON' => 2,
        'LOST' => 3
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
            'started' => now(),
        ]);
        $grid->free_spaces =  $x*$y - Mine::get_number_of_mines(self::$difficulties[$difficulty], $x, $y);
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

    /**
     * @param int $x
     * @param int $y
     * @return array
     * @throws Exception
     */
    public function click_on(int $x, int $y)
    {
        if($x < 0 || $x >= $this->x) {
            throw new Exception('X is out of range');
        }

        if($y < 0 || $y >= $this->y) {
            throw new Exception('Y is out of range');

        }

        if(!is_null($this->finalized)) {
            throw new Exception('Game is already over, my friend');
        }

        $grid = json_decode($this->last_grid);
        $grid_solved = $this->get_grid_solved();
        $cell_result = $grid_solved[$x][$y];

        if($cell_result == 99) {
            $this->finalized = now();
            $this->status = self::$status['LOST'];
            $this->save();

        } else {
            $grid = $this->get_new_grid($cell_result, $x, $y, $grid, $grid_solved);

            $this->logs()->save(new GridLog([
                'grid' => json_encode($grid),
                'cells_opened' => self::count_free_spaces($grid),
                'x' => $x,
                'y' => $y
            ]));

            if(self::count_free_spaces($grid) == $this->free_spaces) {
                $this->finalized = now();
                $this->status = self::$status['WON'];
                $this->save();
            }
        }

        return [
            'status' => $this->status,
            'grid' => $grid
        ];
    }

    /**
     * Relationships and Custom Attributes
     */

    public function getLastGridAttribute()
    {
        return $this->logs()->exists() ? $this->logs()->orderBy('id','DESC')->first()->grid : $this->grid;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(GridLog::class);
    }

    /**
     * @param $cell_result
     * @param int $x
     * @param int $y
     * @param array $grid
     * @param array $grid_solved
     * @return array
     */
    private function get_new_grid($cell_result, int $x, int $y, array $grid, array $grid_solved)
    {
        if($cell_result == 0 && $grid[$x][$y] == -1) {
            $grid[$x][$y] = 0;
            $positions = [0, 1, -1];

            foreach ($positions as $i) {
                foreach ($positions as $j){
                    $new_x = $i+$x;
                    $new_y = $j+$y;

                    if($new_x == $x && $new_y == $y){
                        continue;
                    }

                    if(isset($grid_solved[$new_x][$new_y]) && $grid_solved[$new_x][$new_y] == 0 ){
                        $grid = $this->get_new_grid(0, $new_x , $new_y , $grid, $grid_solved);
                    }
                }
            }
        } else {
            $grid[$x][$y] = $cell_result;
        }

        return $grid;
    }

    /**
     * @param array $grid
     * @return int
     */
    public static function count_free_spaces(array $grid)
    {
        $free_spaces = 0;
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                if($cell != -1 && $cell != 99 ){
                    $free_spaces++;
                }
            }
        }

        return $free_spaces;
    }
}
