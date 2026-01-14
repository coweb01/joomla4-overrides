<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');

?>
<div class="com-users-reset reset">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        </div>
        <div class="page-header">
            <h2>
                <?php echo Text::_('WBC_BLANCO_COM_USER_FORGOT_PW'); ?> 
            </h2>
        </div>
    <?php endif; ?>
    <form id="user-registration" action="<?php echo Route::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="com-users-reset__form form-validate form-horizontal card">
        <div class="card-body">
            <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                <?php if($fieldset->name == 'default'): ?>    
                    <fieldset>  
                        <?php if ($fieldset->label) : ?>
                            <p><?php echo Text::_($fieldset->label); ?></p>
                        <?php endif; ?>
                        <?php $field = $this->form->getField('email'); ?>
                        <?php $label = $field->getAttribute('label'); ?>
                        <?php $field->hint = $label; ?>
                        <?php $this->form->setFieldAttribute($field->fieldname, 'hint', $label); ?>
                        <div class="form-floating mb-3">
                            <?php echo $field->input; ?>
                            <?php echo $field->label; ?>
                        </div>
                    </fieldset>
                <?php endif; ?>    
            <?php endforeach; ?>
       
        <div class="com-users-reset__submit control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary validate">
                    <?php echo Text::_('JSUBMIT'); ?>
                </button>
            </div>
        </div>
        <?php echo HTMLHelper::_('form.token'); ?>
        </div>
    </form>
</div>
