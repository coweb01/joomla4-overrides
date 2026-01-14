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
use Joomla\CMS\Form\Form;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');

$form = $this->form;
// Ausgabe der einzelnen Felder Placeholder wird gleich label gesetzt
function ausgabe ($field, $form) { ?>
    <?php if (!$field) { return; } ?>
    <?php $label        = $field->getAttribute('label'); ?>
    <?php $field->hint  = $label; ?>
    <?php $form->setFieldAttribute($field->fieldname, 'hint', $label); ?>

    <?php if ($field->type == 'Password') { ?>
        <div class="mb-3"> 
            <?php echo $field->input;  ?>
        </div>  
    <?php } else { ?>
        <div class="form-floating mb-3"> 
            <?php echo $field->input;  ?>  
            <?php echo $field->label; ?>
        </div>
    <?php } ?>    
<?php } ?>

<div class="com-users-registration registration wbc__registration">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>
    <div class="wbc__intro-registration"><?php echo Text::_('WBC_BLANCO_INTRO_REGISTRATION');?></div>

    <form id="member-registration" action="<?php echo Route::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="com-users-registration__form form-validate" enctype="multipart/form-data">
        <div class="row my-5">
                <div class="col-12 col-md-6 mb-3">
                    <fieldset class="wbc__profildata card" name="profildata">
                        <div class="card-header">
                            <h3><?php echo Text::_('WBC_HEADLINE_REGISTRATION_PROFILDATA');?></h3>
                        </div>
                        <div class="card-body">
                            <?php 
                                ausgabe($this->form->getField('name'), $form); 
                            ?>  
                            <?php ausgabe($this->form->getField('address1','profile'), $form); ?>	
                            <div class="row plzcity">
                                <div class="col-12 col-sm-4">
                                    <?php ausgabe($this->form->getField('postal_code','profile'), $form); ?> 
                                </div>
                                <div class="col">
                                    <?php ausgabe($this->form->getField('city','profile'), $form); ?> 
                                </div>
                            </div>    
                        
                            <?php ausgabe($this->form->getField('phone','profile'), $form); ?>
                            <div class="small">
                                <?php echo $this->form->getField('institution','com_fields', $form)->description; ?>
                            </div> 
                        
                            <?php ausgabe($this->form->getField('institution','com_fields'), $form); ?>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-md-6 mb-3 card">
                    <div class="card-header">
                        <h3><?php echo Text::_('WBC_HEADLINE_REGISTRATION_USERDATA');?></h3>
                    </div>
                    <div class="card-body">
                        <fieldset class="wbc__userdata" name="userdata">
                            <?php ausgabe( $this->form->getField('username'), $form); ?>
                            <?php ausgabe( $this->form->getField('email1'), $form); ?>
                            <div class="mt-5">
                            <?php ausgabe( $this->form->getField('password1'), $form); ?>
                            <?php ausgabe( $this->form->getField('password2'), $form); ?>
                            </div>
                        </fieldset>
                    </div>   
                </div>
        </div>
        <div class="row mb-5">
            <?php echo $this->form->renderFieldset('privacyconsent'); ?>
            <?php if ($this->captchaEnabled) : ?>
            <?php echo $this->form->renderFieldset('captcha'); ?>
            <?php endif; ?>
            <div class="com-users-registration__submit control-group mt-3">
                <div class="controls">
                    <button type="submit" class="com-users-registration__register btn btn-primary validate">
                        <?php echo Text::_('JREGISTER'); ?>
                    </button>
                    <input type="hidden" name="option" value="com_users">
                    <input type="hidden" name="task" value="registration.register">
                </div>
            </div>       
        </div>        
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>
