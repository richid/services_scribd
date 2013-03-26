<?php
/**
 * Interface for Scribd's "thumbnail" API endpoint.
 *
 * PHP version 5.2.0+
 *
 * LICENSE: This source file is subject to the New BSD license that is 
 * available through the world-wide-web at the following URI:
 * http://www.opensource.org/licenses/bsd-license.php. If you did not receive  
 * a copy of the New BSD License and are unable to obtain it through the web, 
 * please send a note to license@php.net so we can mail you a copy immediately. 
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2013 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   Release: @package-version@
 * @link      http://pear.php.net/package/Services_Scribd
 */

require_once 'Services/Scribd/Common.php';

/**
 * The interface for the "thumbnail" API endpoint.  Allows the user to retrieve
 * a URL to the thumbnail of a document in a given size.
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2013 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/developers/platform
 */
class Services_Scribd_Thumbnail extends Services_Scribd_Common
{
    /**
     * Array of API endpoints that are supported
     *
     * @var array
     */
    protected $validEndpoints = array(
        'get'
    );

    /**
     * Retrieves a URL to the thumbnail of a document, in a given size. Note
     * that docs.getSettings and docs.getList also retrieve thumbnail URLs in
     * default size - this method is really for resizing those.
     *
     * IMPORTANT - it is possible that at some time in the future, Scribd
     * will redesign its image system, invalidating these URLs. So if you cache
     * them, please have an update strategy in place so that you can update
     * them if neceessary.
     *
     * @param integer $docId  The id of the document
     * @param integer $width  Width in px of the desired image
     * @param integer $height Height in px of the desired image
     *
     * @link http://www.scribd.com/developers/platform/api/thumbnail_get
     * @return string
     */
    public function get($docId, $width = null, $height = null)
    {
        $this->arguments['doc_id'] = $docId;
        $this->arguments['width'] = $width;
        $this->arguments['height'] = $height;

        $response = $this->call('thumbnail.get', HTTP_Request2::METHOD_GET);

        return trim((string) $response->thumbnail_url);
    }
}
