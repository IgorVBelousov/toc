<?php

/**
 * PHP TableOfContents Library
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

namespace TOC;

use RuntimeException;

/**
 * TOC Markup Fixer adds `id` attributes to all H1...H6 tags where they do not
 * already exist
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 * @author Igor V Belousov <igor@belousovv.ru>
 */
class MarkupFixer
{
    use HeaderTagInterpreter;
    private $ids = array();

  /**
   * Fix markup
   *
   * @param string $markup 
   * @param int    $topLevel
   * @param int    $depth
   * @return string Markup with added IDs
   */
  public function fix($markup, $topLevel = 1, $depth = 6)
  {

    $tags   = $this->determineHeaderTags($topLevel, $depth);
    preg_replace_callback(
      '/<.*?id[\t,\ ]*?=[\t,\ ]*?[\'|"](.*?)[\'|"].*?>/ui',
      function ($m)
      {
        array_push($this->ids, $m[1]);
      },
      $markup);
    $markup = preg_replace_callback(
      '/(<h['.
        implode('|', $tags)
          .'](\s+[^>]*?)?)>(.*?)<\/h/ui',
        'self::fix_callback',
        $markup
      );
    $this->ids = array();

    return $markup;
  }

  /**
   * Replace callback for fix function
   * 
   * @param array $value 
   * @return string Markup with added IDs
   */
   private function fix_callback($value)
  {
    $sluggifier = new UniqueSluggifier();
    if (preg_match('/id/ui',$value[1])==false)
    {
      if (preg_match('/title/ui',$value[1])) {
        $title = preg_replace_callback(
          '/.*title[\t,\ ]*?=[\t,\ ]*?[\'|"](.*?)[\'|"]/ui',
          function ($m){return $m[1];},
          $value[1]);
        return $value[1].' id="'.
          preg_replace(
              '/^[-0-9._:]/', 
              'N',
              $sluggifier->slugify($title)
            )
          .'">'.$value[3].'</h';
      }
      return $value[1].' id="'.
        preg_replace(
            '/^[-0-9._:]/', 
            'N',
            $this->CheckUniqId($sluggifier->slugify($value[3]))
          )
        .'">'.$value[3].'</h';
    } 

    return $value[0];
  }

  /**
   * Check and return unique id
   * 
   * @param string $id Check id
   * @param integer $number parametr for recursive check
   * 
   * @return string unique id
   */
  private function CheckUniqId($id,$number=0){
    $tmpid = ($number>0)?$id.'-'.$number:$id;
    if (array_search($tmpid, $this->ids)===false) 
      {
        array_push($this->ids,$tmpid);
        return $tmpid;
      }
    $number++;
    return $this->CheckUniqId($id,$number);
  }
}

/* EOF: TocMarkupFixer.php */
