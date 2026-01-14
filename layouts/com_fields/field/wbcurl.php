<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_fields
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;


if (!array_key_exists('field', $displayData)) {
    return;
}

$field = $displayData['field'];

$label = Text::_($field->label);
$value = $field->value;
$showLabel = $field->params->get('showlabel');
$prefix = Text::plural($field->params->get('prefix'), $value);
$suffix = Text::plural($field->params->get('suffix'), $value);
$labelClass = $field->params->get('label_render_class');
$valueClass = $field->params->get('value_render_class');

if ($value == '') {
    return;
}

if ( $field->type == 'acfurl') {
    $value = json_decode($field->value); ?>
        <?php $target = "_self"; ?>
        <?php  switch ( $value->target  ) {
                    case 'new_tab' : $target = '_blank'; 
                    break;
                    case 'same_tab' : $target = '_self'; 
                    break;
            }?> 
        <span class="wbc__field wbc__field_url mt-3 <?php echo $valueClass;?>"><a href="<?php echo $value->url;?>" title="<?php echo $value->text;?>" target="<?php echo $target; ?>"><i class="fas fa-external-link-alt"></i> <?php echo $value->text;?></a></span>  
<?php } ?>