<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$lang   = Factory::getLanguage();
$user   = Factory::getUser();
$groups = $user->getAuthorisedViewLevels();

if ($this->maxLevel != 0 && count($this->children[$this->category->id]) > 0) : ?>
    <?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
        <?php $cssCol = $child->getParams()->get('image_alt'); ?>
        <?php if ( $child->getParams()->get('image_alt_empty') ) { ?>
                <?php $HtmlImage =  HTMLHelper::_('image', $child->getParams()->get('image'), '' , ['class' => 'card-img card-img-top inline-svg']); ?>
        <?php } else { ?>
                <?php $HtmlImage =  HTMLHelper::_('image', $child->getParams()->get('image'), $child->getParams()->get('image_alt'), ['class' => 'card-img card-img-top inline-svg']); ?>
        <?php } ?>        
        <?php // Check whether category access level allows access to subcategories. ?>
        <?php if (in_array($child->access, $groups)) : ?>
            <?php if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) : ?>
            <?php if( $this->params->get('num_columns') > 1 ) { ?>
                <div class="col align-items-center <?php echo $cssCol;?>">
            <?php } ?>    
                <a href="<?php echo Route::_(RouteHelper::getCategoryRoute($child->id, $child->language)); ?>" class="wbc__card-link">     

                    <div class="wbc__com-content-category-blog__child card "> 
                        <div class="wbc__card-body card-body"> 
                                <?php if ( $child->getParams()->get('image')) : ?>
                                    <div class="wbc__bg-secondary wbc__border_icon wbc__card_icon wbc__grow">
                                    <?php echo $HtmlImage; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($lang->isRtl()) : ?>
                                <h3 class="wbc__card-title card-title text-center text-break mt-3 h4">
                                    <?php if ($this->params->get('show_cat_num_articles', 1)) : ?>
                                        <span class="badge bg-info tip">
                                            <?php echo $child->getNumItems(true); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php echo $this->escape($child->title); ?>
                                </h3>
                                <?php else : ?>
                                <h3 class="wbc__card-title card-title text-center mt-3 h4">
                                    <?php echo $this->escape($child->title); ?></a>
                                    <?php if ($this->params->get('show_cat_num_articles', 1)) : ?>
                                        <span class="badge bg-info">
                                            <?php echo Text::_('COM_CONTENT_NUM_ITEMS'); ?>&nbsp;
                                            <?php echo $child->getNumItems(true); ?>
                                        </span>
                                    <?php endif; ?>
                                </h3>
                                <?php endif; ?>
                                
                                <?php if ($this->params->get('show_subcat_desc') == 1) : ?>
                                    <?php if ($child->description) : ?>
                                    <div class="com-content-category-blog__description category-desc wbc__card-text card-text text-center">
                                        <?php echo HTMLHelper::_('content.prepare', $child->description, '', 'com_content.category'); ?>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                        </div>
                    </div>
                </a>   
                <?php if( $this->params->get('num_columns') > 1 ) { ?>
                </div>
            <?php } ?> 
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif;
