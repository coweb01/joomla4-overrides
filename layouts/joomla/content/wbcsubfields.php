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

use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Layout\FileLayout; 


// Get all custom field instances 
$allFields = (FieldsHelper::getFields('com_content.article', null, false, null, true));
$content     = $displayData['content'];
$fieldid     = $displayData['subfieldid'];

foreach ( $allFields as $wbcfield) {
    if ( $wbcfield->id == $fieldid ) {
            $subfieldfparams   = $wbcfield->fieldparams; // Object parameter des Felds
            $subfieldparams    = $wbcfield->params; // Object parameter
            $subfieldtyp       = $wbcfield->type;
            $subfieldlabel     = $wbcfield->label;
            $subfieldname      = $wbcfield->name;
            $subfieldshowLabel = $subfieldparams->get('showlabel');
            $valueClass        = $subfieldparams->get('value_render_class');
            $labelClass        = $subfieldparams->get('label_render_class');
    }
}
?>
<?php 
// Download link Tassos Custom Field
if  ( $subfieldtyp == 'acfurl' ) :
    
    if ( !empty($content['url']) ) {

        $url = Uri::getInstance($content['url']);
        if ( !$url->getScheme() ) {
            $url->setScheme('https');
        }

        switch ( $content['target']  ) {
                case 'new_tab' : 
                            $url_attributes['target'] = 'target="_blank"'; 
                            $url_attributes['rel'] = 'rel="noopener"'; 
                break;

                case 'same_tab' : 
                            $url_attributes['target'] = 'target="_self"';
                            $url_attributes['rel'] = '';       
                break;
        } ?>

        <div class="<?php echo $valueClass;?> <?php echo $subfieldname;?>">
            <?php if ( $subfieldshowLabel ) { ?>
                <div class="<?php echo $labelClass;?> h4"><?php echo $subfieldlabel;?></div>
            <?php } ?>
            <span><i class="fas fa-external-link-alt"></i><a href="<?php echo $url->toString() ;?>" <?php echo implode(' ', $url_attributes);?> title="<?php echo ($content['text']) ? $content['text'] : '';?>"> <?php echo $content['text'];?></a></span>
        </div>

    <?php } ?>

<?php endif; 

// downloads 
if ( $subfieldtyp == 'mediajce' ) {
    //var_dump($subfieldfparams );
    $type = explode(',', $subfieldfparams->get('mediatype')); ?>

    <?php if ( !empty( $content['media_src']) ) { ?>
   
        <?php $url = Uri::getInstance($content['media_src']);?>
        <div class="<?php echo $valueClass;?> <?php echo $subfieldname;?>"> 
        <?php if ( $subfieldshowLabel ) { ?>
                <span class="<?php echo $labelClass;?>"><?php echo $subfieldlabel;?>  </span>
        <?php } ?>
        <?php if (in_array('pdf', $type)) { ?>
            <?php  $url_attributes['target'] = ( $subfieldfparams->get('media_target') == 'download' ) ? 'download="'. $url->toString() .'"' : 'target="'.$subfieldfparams->get('media_target').'"'; ?>
            <?php  $url_attributes['rel'] = 'nofollow' ?>
            <span><i class="fas fa-file-pdf" aria-hidden="true" ></i><a href="<?php echo $url->toString() ;?>" <?php echo implode(' ', $url_attributes);?> title="<?php echo $content['media_text'];?>"> <?php echo $content['media_text'];?></a></span>
            <?php } ?> 
        </div>

     <?php } ?>
<?php }

// link / URL
if  ( $subfieldtyp == 'url' ) : 
        $url = Uri::getInstance($content);
        if ( !$url->getScheme() ) {
            $url->setScheme('https');
    } ?>

    <div class="<?php echo $valueClass;?> <?php echo $subfieldname;?>">
    <?php if ( $subfieldshowLabel ) { ?>
            <div class="<?php echo $labelClass;?> h4"><?php echo $subfieldlabel;?></div>
    <?php } ?>
        <a href="<?php echo $url->toString() ;?>" <?php implode('&nbsp;', $url_attributes);?> title="<?php echo $content;?>"><i class="fas fa-external-link-alt"></i> <?php echo $content;?></a>
    </div>

<?php endif; 

if  ( $subfieldtyp == 'text' || $subfieldtyp == 'textarea' || $subfieldtyp == 'editor' ) :?>
    <div class="<?php echo $valueClass;?> <?php echo $subfieldlabel;?>">
    <?php if ( $subfieldshowLabel ) { ?>
            <div class="<?php echo $labelClass;?> h4"><?php echo $subfieldlabel;?></div>
    <?php } ?>
    <?php echo $content;?>
    </div>
<?php endif; 

// alle weiteren Felder die mehrere Inhalte haben:
if  ( is_array($content) && $subfieldtyp != 'acfurl' && $subfieldtyp != 'mediajce') :?>
    
    <div class="<?php echo $valueClass;?> <?php echo $subfieldname;?>">
        <?php if ( $subfieldshowLabel ) { ?>
                <div class="<?php echo $labelClass;?> h4"><?php echo $subfieldlabel;?></div>
        <?php } ?>
        </span>
        <?php foreach ( $content as $value ) { ?>                               
            <div><?php echo $value;?></div>
        <?php } ?>
    </div>
<?php endif;
