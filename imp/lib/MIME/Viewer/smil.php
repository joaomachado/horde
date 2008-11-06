<?php
/**
 * The IMP_Horde_MIME_Viewer_smil renders SMIL documents to very basic HTML.
 *
 * $Horde: imp/lib/MIME/Viewer/smil.php,v 1.5 2008/01/02 11:12:45 jan Exp $
 *
 * Copyright 2006-2008 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 *
 * @author  Jan Schneider <jan@horde.org>
 * @author  Michael Slusarz <slusarz@horde.org>
 * @package Horde_MIME
 */
class IMP_Horde_MIME_Viewer_smil extends Horde_MIME_Viewer_smil
{
    /**
     * The MIME_Contents object, needed for the _callback() function.
     *
     * @var MIME_Contents
     */
    protected $_contents;

    /**
     * The list of related parts to the current part.
     *
     * @var array
     */
    protected  $_related = null;

    /**
     * Renders out the contents.
     *
     * @param array $params  Any parameters the Viewer may need.
     *
     * @return string  The rendered contents.
     */
    public function render($params)
    {
        $this->_contents = &$params[0];
        return parent::render($params);
    }

    /**
     * User-defined function callback for start elements.
     *
     * @param object $parser  Handle to the parser instance.
     * @param string $name    The name of this XML element.
     * @param array $attrs    List of this element's attributes.
     */
    protected function _startElement($parser, $name, $attrs)
    {
        switch ($name) {
        case 'IMG':
            if (isset($attrs['SRC'])) {
                $rp = $this->_getRelatedLink($attrs['SRC']);
                if ($rp !== false) {
                    $this->_content .= '<img src="' . $this->_contents->urlView($rp, 'view_attach') . '" alt="" /><br />';
                }
            }
            break;

        case 'TEXT':
            if (isset($attrs['SRC'])) {
                $rp = $this->_getRelatedLink($attrs['SRC']);
                if ($rp !== false) {
                    $this->_content .= htmlspecialchars($rp->getContents()) . '<br />';
                }
            }
            break;
        }
    }

    /**
     * Get related parts.
     *
     * @param string $cid  The CID to search for.
     *
     * @return mixed  Either the related MIME_Part or false.
     */
    protected function _getRelatedLink($cid)
    {
        if ($this->_related === null) {
            $this->_related = false;
            $related = $this->mime_part->getInformation('related_part');
            if ($related !== false) {
                $relatedPart = $this->_contents->getMIMEPart($related);
                $this->_related = $relatedPart->getCIDList();
            }
        }

        if ($this->_related) {
            $key = array_search('<' . $cid . '>', $this->_related);
            if ($key !== false) {
                $cid_part = $this->_contents->getDecodedMIMEPart($key);
                return $cid_part;
            }
        }

        return false;
    }
}
