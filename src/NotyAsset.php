<?php namespace MuVO\Yii2\Notifications;

use yii\web\AssetBundle;

class NotyAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/needim/noty/lib';

    /**
     * @var array
     */
    public $css = [
        'noty.css',
        'themes/bootstrap-v3.css',
        'themes/bootstrap-v4.css',
        'themes/light.css',
        'themes/metroui.css',
        'themes/mint.css',
        'themes/nest.css',
        'themes/relax.css',
        'themes/semanticui.css',
        'themes/sunset.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'noty.js'
    ];
}
