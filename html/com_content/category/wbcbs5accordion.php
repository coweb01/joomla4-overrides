<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.collapse', '.accordion', []);

$app          = Factory::getApplication();
$doc          = $app->getDocument();
$template     = $app->getTemplate(true);
$mediapath    = 'media/templates/site/'. $template->template .'/';


/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $doc->getWebAssetManager();
$wa->registerAndUseScript('wbc.convertsvg', $mediapath. 'js/wbcconvertsvg.js', [], [], []);
//$wa->addInlineScript($inlineScript, [ 'name' => 'init.svg' ]);


$this->category->text = $this->category->description;
$app->triggerEvent('onContentPrepare', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$this->category->description = $this->category->text;

$results = $app->triggerEvent('onContentAfterTitle', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayTitle = trim(implode("\n", $results));

$results = $app->triggerEvent('onContentBeforeDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$beforeDisplayContent = trim(implode("\n", $results));

$results = $app->triggerEvent('onContentAfterDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayContent = trim(implode("\n", $results));

$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';
?>
<div class="wbc__com-content-wbcaccordion" itemscope itemtype="https://schema.org/Blog">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>
  
    <?php if ($beforeDisplayContent || $afterDisplayContent || $this->params->get('show_description', 1) || $this->params->def('show_description_image', 1) || $this->params->get('show_category_title', 1)) : ?>
        <div class="wbc__category-desc-container d-flex align-items-center mb-4">
          
            <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
                <picture class="p-3">
                <?php echo LayoutHelper::render(
                    'joomla.html.image',
                    [
                        'src' => $this->category->getParams()->get('image'),
                        'alt' => empty($this->category->getParams()->get('image_alt')) && empty($this->category->getParams()->get('image_alt_empty')) ? false : $this->category->getParams()->get('image_alt'),
                        'class' => 'wbc__categoryimg',
                    ]
                ); ?>
                </picture>
            <?php endif; ?>

            <?php if ($this->params->get('show_category_title', 1) || ($this->params->get('show_description') && $this->category->description)) : ?>
            <div class="flex-grow-1 ms-3">
                <?php if ($this->params->get('show_category_title', 1)) : ?>
                    <div class="wbc__category-title">
                    <<?php echo $htag; ?>>
                            <?php echo $this->category->title; ?> 
                    <<?php echo $htag; ?>>
                </div>
                <?php endif; ?>
                <?php echo $afterDisplayTitle; ?>

                <div class="wbc__category-desc">
                    <?php echo $beforeDisplayContent; ?>
                    <?php if ($this->params->get('show_description') && $this->category->description) : ?>
                        <?php echo HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
                    <?php endif; ?>
                    <?php echo $afterDisplayContent; ?>
                </div>
            </div>
            <?php endif;?>    

            <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
                <?php $this->category->tagLayout = new FileLayout('joomla.content.tags'); ?>
                <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
            <?php endif; ?>

        </div>
    <?php endif; ?>

    <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
        <?php if ($this->params->get('show_no_articles', 1)) : ?>
            <div class="alert alert-info">
                <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                    <?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($this->lead_items)) : ?>
        <div class="wbc__com-content-category-accordion__items mb-5 wbc__accordion-blog--items accblog-items <?php echo $this->params->get('blog_class_leading'); ?>">
            <?php foreach ($this->lead_items as &$item) : ?>
              
                <div class="wbc__com-content-category-accordion__item accblog-item" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                    <?php
                    $this->item = &$item; ?>
                    <?php echo $this->loadTemplate('item');?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
   <?php /* Intro Items als Accordion --------------------------------*/ ?>
    <?php if (!empty($this->intro_items)) : ?> 
        <?php $i = 0; ?>
        <?php $categorys = array();?>
        <?php $catid = 0; ?>

        <?php /* Kategorien gruppieren */ ?>
        <?php foreach ($this->intro_items as $key => &$item) : ?>
            <?php // print_r($item); ?>
           <?php if ( $item->catid == $catid ) {
                    $i2++;
                    $categorys[$i]['items'][$i2] = $item;                     
                      
                } else {
                    $i++;
                    $i2 = 0;
                    $categorys[$i] = array(
                        'catid' => $item->catid,
                        'category_title' => $item->category_title,
                        'items' => array($item)                  
                    );
                }
                $catid = $item->catid;
            ?>
        <?php endforeach; ?> 
        <?php  // end Gruppierung  ?>

        <?php $count_items = 0; ?>
        <?php $collapse_first_item = $this->params->get('collapse_first_item',0); ?>
        <?php $blogClass = $this->params->get('blog_class', ''); ?>
        <?php $accId = 'wbc__Acc_' . rand(); ?>
        <?php if ((int) $this->params->get('num_columns') > 1) : ?>
            <?php $blogClass .= (int) $this->params->get('multi_column_order', 0) === 0 ? '' : ' columns-'; ?>
            <?php $blogClass .= (int) $this->params->get('num_columns'); ?>
        <?php endif; ?>

        <div id="<?php echo $accId;?>" class="wbc__com-content-category-accordion__items accordion accordion-flush wbc__accordion-items <?php echo $blogClass; ?>">
        
            <?php $cid = 0; ?> 
            <?php $i = 0;?> 
            
            <?php foreach ($categorys as $category ) : ?>
                <?php $count_items++; ?>
                        
                <div class="wbc__com-content-category-accordion__item wbc__accordion-items accordion-item"
                    itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                        <?php /* Accordion Button */ ?>
                        <?php if ( $category['catid'] != $cid ) { ?>
                            <div id="heading_<?php echo $category['catid'];?>" class="accordion-header" >
                                <h3 class="wbc_item_title mb-0" itemprop="name">
                                    <button class="accordion-button text-left <?php echo ($count_items == 1 && $collapse_first_item) ? '' :'collapsed';?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $category['catid'];?>" aria-expanded="true" aria-controls="collapse-<?php echo $category['catid'];?>">
                                        <?php echo $this->escape($category['category_title']); ?>
                                    </button> 
                                </h3>
                            </div>              
                        <?php  } ?>
                        <?php /* Ende Accordion Button */ ?>

                        <div id="collapse-<?php echo $category['catid'];?>" class="accordion-collapse collapse <?php echo ($count_items == 1 && $collapse_first_item) ? 'show' :'';?>" data-bs-parent="<?php echo $accId;?>">
                            <?php foreach ( $category['items'] as $wbcitem ) : ?>  
                                <?php $this->item = $wbcitem; ?>
                                
                                <?php echo $this->loadTemplate('accitem'); /* Unterkategorien */ ?>
                        
                            <?php endforeach ?>
                        </div>
                        <?php $cid = $category['catid']; ?>    

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->link_items)) : ?>
        <div class="wbc__accordion-items-more">
            <?php echo $this->loadTemplate('links'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->maxLevel != 0 && !empty($this->children[$this->category->id])) : ?>
        <div class="wbc__com-content-category-blog__children cat-children mt-5 row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-<?php echo $this->params->get('num_columns');?> g-4">
            <?php  echo $this->loadTemplate('children'); ?>
        </div>   
    <?php endif; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="wbc__com-content-category-accordion__navigation w-100">
            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                <p class="com-content-category-accordion__counter counter float-end pt-3 pe-2">
                    <?php echo $this->pagination->getPagesCounter(); ?>
                </p>
            <?php endif; ?>
            <div class="wbc__com-content-category-accordion__pagination">
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
