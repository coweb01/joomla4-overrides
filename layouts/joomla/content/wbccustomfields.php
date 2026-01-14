<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * Ausgabe der Customfields 
 * Das die Felder vom Typ Subform sollten das Layout wbcsubform erhalten.
 */


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\File;

$fields =  $displayData['customfields'];
$itemid =  $displayData['itemid'];
$app            = Factory::getApplication();
$doc            = $app->getDocument();
$template       = $app->getTemplate();
$layoutpath     = '/html/layouts/com_fields/field';
$subfieldlayout = 'wbcsubform';
$layoutfile     = 'templates/'.$template.$layoutpath.'/'.$subfieldlayout. '.php'; 

?>
<div class="wbc__customfields mb-5">
    <?php foreach ( $fields as $field ) : ?>

        <?php if ( $field->group_state == 1 && $field->group_title == 'Digitales Rathaus'  || $field->group_title == 'digitales rathaus') :  // Felder Digitales Rathaus ?>
                <?php // Den Inhalt der Felder ausgeben ?>
                <?php $layout = ( $field->params['layout'] ) ? $field->params['layout'] : 'render' ; ?>
               <?php var_dump($layout);?>
                <?php if ( File::exists($layoutfile) && $field->type == 'subform' ) {
                    $layout = $subfieldlayout;  
                } ?>
                <?php $subfieldcontent = FieldsHelper::render($field->context, 'field.' . $layout, array('field' => $field));?>
                <?php echo $subfieldcontent; ?>
            
        <?php endif; ?>  

    <?php endforeach; ?>
</div>
