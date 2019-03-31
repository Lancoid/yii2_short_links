<?php

namespace app\assets;

use yii\web\{AssetBundle, View};

/**
 * Class ClipboardJsAsset
 *
 * @package app\assets
 */
class ClipboardJsAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@bower/clipboard/dist';

    /**
     * {@inheritdoc}
     */
    public $js = ['clipboard.min.js'];

    /**
     * {@inheritdoc}
     */
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        $view->registerJs("
		var clipboard = new Clipboard('.clipboard-js-init');
		clipboard.on('success', function(e) {
            if(typeof e.trigger.dataset.clipboardSuccess !== 'undefined') {
              var reset = e.trigger.innerHTML;
              setTimeout(function(){e.trigger.innerHTML = reset;}, 1000);
              e.trigger.innerHTML = e.trigger.dataset.clipboardSuccess;
            }
		});
		clipboard.on('error', function(e) {
			e.trigger.innerHtml = e.trigger.dataset.clipboardText;
		});
		", View::POS_READY, 'clipboard-js-init');
    }
}
