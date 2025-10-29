<?php
/**
 * 搜索表单模板
 *
 * @package Nova
 */

$unique_id = esc_attr(uniqid('search-form-'));
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search" 
           id="<?php echo esc_attr($unique_id); ?>" 
           class="search-field" 
           placeholder="<?php esc_attr_e('输入关键词进行搜索…', 'nova'); ?>" 
           value="<?php echo get_search_query(); ?>" 
           name="s" 
           autocomplete="off" 
           aria-label="<?php esc_attr_e('搜索关键词', 'nova'); ?>" />
    <input type="hidden" name="post_type" value="post" />
    <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('搜索', 'nova'); ?>">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 19L13 13M15 8C15 11.866 11.866 15 8 15C4.13401 15 1 11.866 1 8C1 4.13401 4.13401 1 8 1C11.866 1 15 4.13401 15 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</form>

