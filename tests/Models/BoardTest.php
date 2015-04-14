<?php namespace GameOfLifeTest\Models;
/**
 * Created by IntelliJ IDEA.
 * User: blitzcat
 * Date: 4/12/15
 * Time: 3:35 PM
 */
use GameOfLife\Models\Board;
use GameOfLife\Models\Controller;

class BoardTest extends \PHPUnit_Framework_TestCase
{
    public function testBoardIsEmpty()
    {
        $board = new Board(4, 4);
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $this->assertFalse($board->getCellStatus($i, $j));
            }
        }

    }

    public function testBoardSetters()
    {
        $length = 3;
        $height = 4;
        $board = new Board($length, $height);
        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $height; $j++) {
                $board->setCellLive($i, $j);
                $this->assertTrue($board->getCellStatus($i, $j));
            }
        }
    }

    public function testUnsetFalse()
    {
        $board = new Board(1, 1);
        $this->assertFalse($board->getCellStatus(2,0));
    }

    public function testCanSetUnsetSpace()
    {
        $board = new Board(1, 1);
        $board->setCellLive(4,4);
        $this->assertTrue($board->getCellStatus(4,4));
    }

    public function testAskNeighbors()
    {
        $board = new Board(3, 3);
        $this->assertEquals(false, $board->NeighborCount(1,1));
        $this->assertEquals(false, $board->NeighborCount(2,2));

        $board = new Board(4, 4);
        $board->setCellLive(1,1);
        $board->setCellLive(1,2);
        $board->setCellLive(2,1);
        $board->setCellLive(2,2);
        $this->assertEquals(3, $board->NeighborCount(2,2));
    }

    public function testOneGeneration()
    {
        $board = new Board(4,4);
        $controller = new Controller($board);
        $controller->setInitialStateSquare();
        $before = $board->show();
        $board = $board->buildNextGeneration();
        $this->assertEquals($before, $board->show());
    }

    public function testTenGenerations()
    {
        $board = new Board(4,4);
        $controller = new Controller($board);
        $controller->setInitialStateSquare();
        $before = $board->show();
        for ($i = 0; $i < 10; $i++) {
            $board = $board->buildNextGeneration();
        }
        $this->assertEquals($before, $board->show());
    }

    public function testRandomGenerations()
    {
        $board = new Board(20,20);
        $controller = new Controller($board);
        $controller->setInitialStateRandom();

        $string = $board->show();

        for ($i = 0; $i < 10; $i++) {
            $board = $board->buildNextGeneration();
            $this->assertNotEquals($string, $board->show());
        }
    }

}