# Minesweeper API




## CreateGridTest
This file includes tests for creating a grid.

```php
check_grid_is_created()
check_x_cannot_be_negative()
check_y_cannot_be_negative()
check_difficulty_exists()
check_free_spaces_are_added_to_created_grid()
```

The static function  for creating a grid is 

```php
$x = 6;
$y = 6;
$difficulty = 'EASY';
Grid::create_grid($x, $y, $difficulty);
```

## AddMinesToGridTest
This file includes tests for describing how the mines are added to the grid.

```php
check_four_mines_are_added_to_grid_in_super_easy_in_6_by_6()
check_six_mines_are_added_to_grid_in_super_easy_in_8_by_8()
check_six_mines_are_added_to_grid_in_hard_in_8_by_8()
check_fifty_mines_are_added_to_grid_in_hard_in_10_by_10()
```

How many mines are added to the grid?
It depends of the difficulty chosen

The difficulties are 
```
        'SUPER EASY' => 1,
        'EASY' => 2,
        'NORMAL' => 3,
        'HARD' => 4,
        'SUPER HARD' => 5
```

The formula for getting the number of mines, depends, for example :

```
  round(($x * $y) /10  * ($difficulty));
```

```
  round((6 * 6 ) /10  * (2)) =  7 mines
```



## SolvedGridTest
This file includes tests for showing a solved grid


```php
get_solved_grid_seven_by_seven_on_easy_difficulty()
get_solved_grid_seven_by_seven_on_super_hard_difficulty()
get_solved_grid_nine_by_nine_on_hard_difficulty()
```

For getting a solved grid, only a function call is necessary, for example :


```php
$x = 7;
$y = 7;
$difficulty = 'EASY';
$grid = Grid::create_grid($x, $y, $difficulty);

$grid->get_grid_solved();
```

Because the mines are added randomly, we forced it just for the tests cases


## GameSevenBySevenEasyDifficultyTest
This test sums up all the previous features described, this test includes the tests:

```php
click_on_0_0_opens_3_more()
click_on_0_0_and_6_0_opens_10_more()
click_on_0_0_and_6_0_and_0_6_opens_17_more()
click_everything_less_than_mines_and_end_the_game()
click_on_a_mine_ends_game()
click_on_non_existing_X_cell()
click_on_non_existing_Y_cell()
```

After clicking a cell(x,y), the grid should be updated and show a new one, in case it clicks a mine, the game is over, and in case all the possibles cells that are not a mine have been opened, the game finishes. For using it, it should be called from the grid with the  x and y parameters : 


```php
$x = 7;
$y = 7;
$difficulty = 'EASY';
$grid = Grid::create_grid($x, $y, $difficulty);

$grid->get_grid_solved();
$grid->click_on(2,1);
```
