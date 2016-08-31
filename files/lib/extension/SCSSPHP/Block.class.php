<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2015 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.github.io/scssphp
 */

namespace LSC\lib\extension\SCSSPHP;

/**
 * Block
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Block
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var \LSC\lib\extension\SCSSPHP\Block
     */
    public $parent;

    /**
     * @var integer
     */
    public $sourceIndex;

    /**
     * @var integer
     */
    public $sourceLine;

    /**
     * @var integer
     */
    public $sourceColumn;

    /**
     * @var array
     */
    public $selectors;

    /**
     * @var array
     */
    public $comments;

    /**
     * @var array
     */
    public $children;
}
