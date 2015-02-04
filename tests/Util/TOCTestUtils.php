<?php
/**
 * toc
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/igorvbelousov/toc
 * @version 1.0
 * @package igorvbelousov/toc
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 * @author Igor V Belousov <igor@belousovv.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

namespace TOC\Util;

use Knp\Menu\ItemInterface;

class TOCTestUtils
{
    /**
     * Get a flattened array containing references to all of the items
     *
     * @param ItemInterface $item   The menu item
     * @param bool          $isTop  Is the initial menu item starting at the top-level?
     * @return array
     */
    public static function flattenMenuItems(ItemInterface $item, $isTop = true)
    {
        $arr = $isTop ? [] : [$item];

        foreach ($item->getChildren() as $child) {
            $arr = array_merge($arr, self::flattenMenuItems($child, false));
        }

        return $arr;
    }
}
