<?php

namespace Vb5UrlFixer;

/**
 * @property int routeid
 * @property string name
 * @property int redirect301
 * @property string prefix
 * @property string regex
 * @property string class
 * @property string controller
 * @property string action
 * @property string template
 * @property string arguments
 * @property int contentid
 * @property string guid
 * @property string product
 */
class RouteNew extends BaseModel
{
    public function __construct($data)
    {
        parent::__construct($data);
    }
}
