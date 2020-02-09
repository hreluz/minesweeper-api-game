<?php

namespace Tests\Feature;

use App\Grid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Exception;
use Tests\TestCase;

class AddMinesToGridTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function check_four_mines_are_added_to_grid_in_super_easy_in_6_by_6()
    {
        Grid::create_grid(6, 6, 'SUPER EASY');
        $grid_decode = json_decode((Grid::first())->grid, true);
        $number_mines = 0;

        for ($i = 0; $i < 6 ; $i++) {
            for ($j = 0; $j < 6 ; $j++){
                if($grid_decode[$i][$j] == 99 ){
                    $number_mines++;
                }
            }
        }

        $this->assertEquals($number_mines, 4);
    }

    /**
     * @test
     */
    public function check_six_mines_are_added_to_grid_in_super_easy_in_8_by_8()
    {
        Grid::create_grid(8, 8, 'SUPER EASY');
        $grid_decode = json_decode((Grid::first())->grid, true);
        $number_mines = 0;

        for ($i = 0; $i < 8 ; $i++) {
            for ($j = 0; $j < 8 ; $j++){
                if($grid_decode[$i][$j] == 99 ){
                    $number_mines++;
                }
            }
        }

        $this->assertEquals($number_mines, 6);
    }

    /**
     * @test
     */
    public function check_six_mines_are_added_to_grid_in_hard_in_8_by_8()
    {
        Grid::create_grid(8, 8, 'HARD');
        $grid_decode = json_decode((Grid::first())->grid, true);
        $number_mines = 0;

        for ($i = 0; $i < 8 ; $i++) {
            for ($j = 0; $j < 8 ; $j++){
                if($grid_decode[$i][$j] == 99 ){
                    $number_mines++;
                }
            }
        }

        $this->assertEquals($number_mines, 26);
    }

    /**
     * @test
     */
    public function check_fifty_mines_are_added_to_grid_in_hard_in_10_by_10()
    {
        Grid::create_grid(10, 10, 'SUPER HARD');
        $grid_decode = json_decode((Grid::first())->grid, true);
        $number_mines = 0;

        for ($i = 0; $i < 10 ; $i++) {
            for ($j = 0; $j < 10 ; $j++){
                if($grid_decode[$i][$j] == 99 ){
                    $number_mines++;
                }
            }
        }

        $this->assertEquals($number_mines, 50);
    }
}
