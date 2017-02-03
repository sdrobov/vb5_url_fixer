<?php

namespace Vb5UrlFixer;

/**
 * @property int nodeid
 * @property int routeid
 * @property int contenttypeid
 * @property int publishdate
 * @property int unpublishdate
 * @property int userid
 * @property int groupid
 * @property string authorname
 * @property string description
 * @property string title
 * @property string htmltitle
 * @property int parentid
 * @property string urlident
 * @property int displayorder
 * @property int starter
 * @property int created
 * @property int lastcontent
 * @property int lastcontentid
 * @property string lastcontentauthor
 * @property int lastauthorid
 * @property string lastprefixid
 * @property int textcount
 * @property int textunpubcount
 * @property int totalcount
 * @property int totalunpubcount
 * @property string ipaddress
 * @property int showpublished
 * @property int oldid
 * @property int oldcontenttypeid
 * @property int nextupdate
 * @property int lastupdate
 * @property int featured
 * @property string CRC32
 * @property string taglist
 * @property int inlist
 * @property int protected
 * @property int setfor
 * @property int votes
 * @property int hasphoto
 * @property int hasvideo
 * @property int deleteuserid
 * @property string deletereason
 * @property int open
 * @property int showopen
 * @property bool sticky
 * @property bool approved
 * @property bool showapproved
 * @property int viewperms
 * @property int commentperms
 * @property int nodeoptions
 * @property string prefixid
 * @property int iconid
 * @property int public_preview
 */
class Node extends BaseModel
{
    public function __construct($data)
    {
        parent::__construct($data);
    }
}
