<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_fields
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Override fuer ACF - Artikel Feld 
 * Artikel Rückwärts verknüpfen
 * Ausgabe subfield kontaktdaten
 * 
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Factory;

if (!$displayData['field']) {
    return;
}
//  verknüpfte Artikel rückwärts 
$value          = $displayData['field']->value;

if (empty($value)) {
    return;
}

/* die Itemids extrahieren */
$string = $value;
preg_match_all('/<div id="itemid">(\d+)<\/div>/', $string, $matches);
$itemids = $matches[1];

// Model laden
$app          = Factory::getApplication();
$mvcFactory   = $app->bootComponent('com_content')->getMVCFactory();

foreach ($itemids as $value) {
    $itemid = (int) $value;
    $model  = $mvcFactory->createModel('Article', 'Administrator', ['ignore_request' => true]);
    $model->setState('filter.published', 1);
    $item   = $model->getItem($itemid);

    if (!$item) {
        continue;
    }

    $fields = FieldsHelper::getFields('com_content.article', $item, true);

    // Subform-Feld finden
    foreach ($fields as $field) {
        if (!empty($field->value) && $field->name == 'zustaendige-mitarbeiter' || $field->name == 'ansprechpartner-rathaus') {
            // Werte eines Subforms ausgeben
            if (isset($field->subform_rows)) {
                $result = '';
                $context = $field->context;
                // Iterate over each row that we have
                foreach ($field->subform_rows as $subform_row) {
                    // Placeholder array to generate this rows output
                    $row_output = [];

                    // Iterate over each sub field inside of that row
                    foreach ($subform_row as $subfield) {
                        
                        // Skip empty fields
                        if(empty($subfield->value) || trim($subfield->value) === '') {
                            continue;
                        }

                        $class       = trim($subfield->params->get('render_class', ''));
                        $layout      = trim($subfield->params->get('layout', 'render'));
                        $fieldtyp    = trim($subfield->type);
                        $fieldid     = trim($subfield->id);
                        $content     = trim(
                            FieldsHelper::render(
                                $context,
                                'field.' . $layout, // normally just 'field.render'
                                ['field' => $subfield]
                            )
                        );

                        // Skip empty output
                        if ($content === '') {
                            continue;
                        }

                        // Generate the output for this sub field and row
                        $row_output[] = '<div class="wbc__field-entry-'.  $fieldtyp . ' ' . ($class ? (' ' . $class) : '') . '">' . $content . '</div>';
                    }

                    // Skip empty rows
                    if (count($row_output) == 0) {
                        continue;
                    }

                    $result .= '<div class="wbc__subform-row">' . implode(' ', $row_output) . '</div>';
                }
                // die Felder ausgeben ?>
                <?php if (trim($result) != '') { ?>
                <div class="wbc__subform-fields mb-3 <?php echo trim($field->params->get('render_class', ''));?>">
                    <?php echo $label; ?>
                    <?php echo $result; ?>
                </div>
                <?php } ?>
    <?php } else {
                if (!empty($field->value)) {
                    // Werte kein Subform-Feld
                    $class       = trim($field->params->get('render_class', ''));
                    $layout      = trim($field->params->get('layout', 'render'));
                    $fieldtyp    = trim($field->type);
                    $fieldid     = trim($field->id);
                    $content     = trim(
                        FieldsHelper::render(
                            $context,
                            'field.' . $layout, // normally just 'field.render'
                            ['field' => $field]
                        )
                    ); ?>
                    <div class="wbc__field-wrapper"><?php echo $content;?></div>
            <?php }
            }
        }
    }
}
?>