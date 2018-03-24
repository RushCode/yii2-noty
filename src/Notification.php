<?php namespace MuVO\Yii2\Notifications;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Session;

class Notification extends BaseObject
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $text;

    /**
     * @param string $text
     * @param array $options
     * @param Session|null $session
     * @return bool
     */
    public static function add(string $text, array $options = [], Session $session = null)
    {
        if (is_null($session)) {
            $session = \Yii::$app->get('session');
        }

        $removeAfterAccess = ArrayHelper::remove($options, 'autoremove', true);
        try {
            $notification = \Yii::createObject(static::class, [[
                'options' => $options,
                'text' => $text,
            ]]);

            $session->addFlash(self::class,
                $notification,
                $removeAfterAccess);

            return true;
        } catch (\Throwable $e) {
            \Yii::error($e->getMessage(), __METHOD__);
        }

        return false;
    }

    /**
     * @param string $text
     * @param int|null $timeout
     * @param Session|null $session
     * @return bool
     */
    public static function info(string $text, int $timeout = null, Session $session = null)
    {
        return static::add($text,
            ['type' => 'info', 'timeout' => $timeout],
            $session);
    }

    /**
     * @param string $text
     * @param int|null $timeout
     * @param Session|null $session
     * @return bool
     */
    public static function warn(string $text, int $timeout = null, Session $session = null)
    {
        return static::add($text,
            ['type' => 'warning', 'timeout' => $timeout],
            $session);
    }

    /**
     * @param string $text
     * @param int|null $timeout
     * @param Session|null $session
     * @return bool
     */
    public static function error(string $text, int $timeout = null, Session $session = null)
    {
        return static::add($text,
            ['type' => 'error', 'timeout' => $timeout],
            $session);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($text = ArrayHelper::remove($this->options, 'text')) {
            $this->text = $text;
        }
    }

    /**
     * @param string $tagName
     * @return string
     */
    public function render(string $tagName = 'span')
    {
        return Html::tag($tagName, $this->text,
            ['data' => $this->options]);
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Throwable $e) {
            \Yii::error($e->getMessage(), __METHOD__);
        }

        return null;
    }
}
