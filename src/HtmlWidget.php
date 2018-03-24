<?php namespace MuVO\Yii2\Notifications;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * Class HtmlWidget
 * @package MuVO\Yii2\Notifications
 */
class HtmlWidget extends Widget
{
    /**
     * @var array
     */
    public $options = [
        'style' => 'display: none;',
    ];

    /**
     * @var Notification[]
     */
    private $_notifications = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->_notifications)) {
            $this->_notifications = \Yii::$app->session
                ->getFlash(Notification::class, []);
        }
    }

    /**
     * @param array|Notification[] $notifications
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public function setNotifications(array $notifications)
    {
        foreach ($notifications as $notification) {
            if ($notification instanceof Notification) {
                $this->_notifications[] = $notification;
            } elseif ($notification = \Yii::createObject(Notification::class, [$notification])) {
                $this->_notifications[] = $notification;
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!empty($this->_notifications)) {
            $this->view->registerAssetBundle(NotyAsset::class, View::POS_HEAD);
            $this->view->registerJs('const notifications = $(\'ul#' . addslashes($this->id) . '>li\');
if (undefined !== notifications) {
    for (i = 0; i < notifications.length; i++) {
        new Noty($(notifications[i]).data())
            .setText($(notifications[i]).html(), true)
            .show();
    }
}', View::POS_READY);

            return Html::ul($this->_notifications, ArrayHelper::merge([
                'id' => $this->id,
                'encode' => false,
                'item' => function (Notification $notification) {
                    return Html::tag('li', $notification->text,
                        ['data' => $notification->options]);
                },
            ], $this->options));
        }

        return null;
    }
}
