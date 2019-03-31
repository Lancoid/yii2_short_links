<?php

namespace app\widgets;

use yii\base\{InvalidConfigException, Widget};
use app\assets\ClipboardJsAsset;
use yii\helpers\Html;

/**
 * Class ClipboardJsWidget
 *
 * @package app\widgets
 */
class ClipboardJsWidget extends Widget
{
    /** @var string */
    public $text;

    /** @var string */
    public $inputId;

    /** @var string */
    public $label = 'Copy to clipboard';

    /** @var string */
    public $successText = 'Copied';

    /** @var array */
    public $htmlOptions = ['class' => 'btn'];

    /** @var bool */
    public $cut = false;

    /** @var string */
    public $tag = 'button';

    /**
     * {@inheritdoc}
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->text) && !isset($this->inputId)) {
            throw new InvalidConfigException('"text" or "inputId" must be set for the ClipboardJsWidget.');
        }

        if (isset($this->inputId) && substr($this->inputId, 0, 1) !== '#') {
            $this->inputId = '#' . $this->inputId;
        }

        parent::init();

        ClipboardJsAsset::register($this->getView());
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $options = $this->htmlOptions;
        $options['data']['clipboard-action'] = 'copy';

        if (isset($this->text)) {
            $options['data']['clipboard-text'] = $this->text;
        } elseif (isset($this->inputId)) {
            $options['data']['clipboard-target'] = $this->inputId;
            if ($this->cut) {
                $options['data']['clipboard-action'] = 'cut';
            }
        }

        if ($this->successText) {
            $options['data']['clipboard-success'] = $this->successText;
        }

        Html::addCssClass($options, 'clipboard-js-init');

        echo Html::tag($this->tag, $this->label, $options);
    }
}
