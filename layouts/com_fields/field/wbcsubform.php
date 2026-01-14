<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_fields
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * Override fuer Feld Subform. 
 * Ausgabe der Felder Typ:
 * uri, text, textarea, editor, acfurl
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Layout\FileLayout;

$layoutSubfields = new FileLayout('joomla.content.wbcsubfields'); 
//$layoutSubfields->render(); 

if (!array_key_exists('field', $displayData)) {
    return;
}

// Get all custom field instances 
//$allFields = (FieldsHelper::getFields('com_content.article', null, false, null, true));
$field = $displayData['field'];
$subfield = array();
$subfield['value']          = $field->value;
$subfield['label']          = Text::_($field->label);
$subfield['showLabel']      = $field->params->get('showlabel');
$subfield['prefix']         = Text::plural($field->params->get('prefix'), $field->value);
$subfield['suffix']         = Text::plural($field->params->get('suffix'), $field->value);
$subfield['labelClass']     = $field->params->get('label_render_class');
$subfield['valueClass']     = $field->params->get('value_render_class');
$subfield['url_attributes'] = array( 'rel' => 'rel="noopener"',
                                    'target' => 'target="_blank"'
                                    );
if ($field->value == '') {
    return;
}
if ( $field->type == 'subform') { // alle Felder innerhalb einer Subform
    $subfield_rows    = json_decode($field->value, true , 200 ); 
        foreach ( $subfield_rows as $name => $subfield_row ) : // die einzelnen Felder in einem Subfield             

            if ( is_array($subfield_row) ) : ?>
                <div class="mt-5">
                    <?php foreach ( $subfield_row as $rowid => $content) :  ?>
                        <?php //Feld ist ein repeatable Feld ?>
                        <?php $subfield['subfieldid'] = intval(str_replace('field','', $rowid)); ?>
                        <?php $subfield['content'] = $content; ?>
                        <?php echo $layoutSubfields->render($subfield);?>
                    <?php endforeach; ?>
                </div>
            <?php else :  // Feld ist kein Repeatable Feld ?>  
                <?php if ( !empty($subfield_row) ) { ?>
                    <?php $subfield['subfieldid'] = intval(str_replace('field','', $name)); ?>
                    <?php $subfield['content'] = $subfield_row; ?>
                    <div class="mt-5">
                        <?php echo $layoutSubfields->render($subfield); ?>
                    </div>
                <?php } ?>
            <?php endif; ?>
        <?php endforeach; ?>
<?php } ?>

