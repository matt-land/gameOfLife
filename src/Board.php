<?php namespace GameOfLife;
/**
 * Created by IntelliJ IDEA.
 * User: blitzcat
 * Date: 4/12/15
 * Time: 2:00 PM
 */

use GameOfLife\CellRules\DeadCellRules;
use GameOfLife\CellRules\LivingCellRules;
class Board implements BoardInterface
{
    private $cells = array();
    private $length;
    private $width;

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function __construct($length, $width)
    {
        $this->length = $length;
        $this->width = $width;
    }

    public function show()
    {
        echo chr(27) . "[2J" . chr(27) . "[;H"; //clear the screen
        for ($posX = 0 ; $posX < $this->getLength(); $posX++) {
            for ($posY = 0 ; $posY < $this->getWidth(); $posY++) {
                echo  chr(27) . ($this->getCellStatus($posX , $posY) ? "[47m".chr(27)."[30m " : "[40m ");
            }
            echo PHP_EOL;
        }

        echo chr(27)."[39m".chr(27)."[49m";
    }

    public function __toString()
    {
        $string = '';
        for ($posX = 0 ; $posX < $this->getLength(); $posX++) {
            for ($posY = 0 ; $posY < $this->getWidth(); $posY++) {
                $string .= (int) $this->getCellStatus($posX , $posY);
            }
            $string .= PHP_EOL;
        }
        return $string;
    }

    public function NeighborCount($posX, $posY)
    {
        return
            $this->getCellStatus($posX -1, $posY -1) + //bottom left
            $this->getCellStatus($posX -1, $posY -0) + //bottom middle
            $this->getCellStatus($posX -1, $posY +1) + //bottom right

            $this->getCellStatus($posX -0, $posY -1) + //middle left
                                                       //middle sq (skip $this)
            $this->getCellStatus($posX -0, $posY +1) + //middle right

            $this->getCellStatus($posX +1, $posY -1) + //top left
            $this->getCellStatus($posX +1, $posY -0) + //top middle
            $this->getCellStatus($posX +1, $posY +1);  //top right
    }

    public function getCellStatus($posX, $posY)
    {
        if (! isset($this->cells[$posX][$posY])) {
            return false;
        }
        return $this->cells[$posX][$posY];
    }

    private function setCellStatus($posX, $posY, $state)
    {
        if (! isset($this->cells[$posX][$posY])) {
            $this->cells[$posX][$posY] = true;
        }
        $this->cells[$posX][$posY] = (bool) $state;
    }

    public function setCellLive($posX, $posY)
    {
        self::setCellStatus($posX, $posY, true);
    }

    public function setCellDead($posX, $posY)
    {
        self::setCellStatus($posX, $posY, false);
    }

    public function buildNextGeneration()
    {
        $nextBoard = clone($this);
        for ($posX = 0; $posX < $this->getLength(); $posX++) {
            for ($posY = 0; $posY < $this->getWidth(); $posY++) {
                //do something
                $cell = $this->getCellStatus($posX, $posY) ?
                    new LivingCellRules() : new DeadCellRules();
                $cell->nextGenerationLifeStatus($this->NeighborCount($posX, $posY)) ?
                    $nextBoard->setCellLive($posX, $posY) : $nextBoard->setCellDead($posX, $posY);
            }
        }

        return $nextBoard;
    }
}