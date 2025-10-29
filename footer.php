<?php
/**
 * 页脚模板
 *
 * @package Nova
 */
?>

<footer id="colophon" class="site-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
    <div class="container">
        <?php
        // 获取页脚设置
        $footer_copyright = get_theme_mod('nova_footer_copyright', '');
        $footer_icp = get_theme_mod('nova_footer_icp', '');
        $footer_gaba = get_theme_mod('nova_footer_gaba', '');
        $footer_gaba_url = get_theme_mod('nova_footer_gaba_url', '');
        $footer_links = get_theme_mod('nova_footer_links', false);
        $footer_theme_credit = get_theme_mod('nova_footer_theme_credit', true);
        $footer_load_time = get_theme_mod('nova_footer_load_time', false);
        ?>
        
        <?php if ($footer_links) : ?>
            <div class="footer-links-section">
                <?php if (has_nav_menu('footer')) : ?>
                    <p class="footer-links">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'footer-link-list',
                            'items_wrap'     => '<span class="footer-link-list">%3$s</span>',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </p>
                <?php else : ?>
                    <?php
                    // 如果没有页脚菜单，尝试显示WordPress链接
                    $bookmarks = get_bookmarks(array('limit' => 10));
                    if ($bookmarks) : ?>
                        <p class="footer-links">
                            <span class="footer-link-list">
                                <?php foreach ($bookmarks as $bookmark) : ?>
                                    <a href="<?php echo esc_url($bookmark->link_url); ?>" target="_blank" rel="nofollow">
                                        <?php echo esc_html($bookmark->link_name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </span>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="site-info">
            <p>
                <?php if ($footer_copyright) : ?>
                    <?php echo wp_kses_post($footer_copyright); ?>
                <?php else : ?>
                    &copy; <?php echo date('Y'); ?> 
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                    <?php esc_html_e('保留所有权利。', 'nova'); ?>
                <?php endif; ?>
                
                <?php if ($footer_icp) : ?>
                    &nbsp;<a rel="nofollow" target="_blank" href="http://www.beian.miit.gov.cn/"><?php echo esc_html($footer_icp); ?></a>
                <?php endif; ?>
                
                <?php if ($footer_gaba) : ?>
                    &nbsp;<a rel="nofollow" target="_blank" href="<?php echo esc_url($footer_gaba_url); ?>">
                        <?php echo esc_html($footer_gaba); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($footer_theme_credit) : ?>
                    &nbsp;&nbsp;<?php esc_html_e('基于', 'nova'); ?> 
                    <a href="<?php echo esc_url(__('https://wordpress.org/', 'nova')); ?>" target="_blank" rel="noopener noreferrer">
                        WordPress
                    </a>
                    <?php esc_html_e('构建，使用', 'nova'); ?> 
                    <a href="https://github.com/motao123/nova-wordpress-theme" target="_blank" rel="noopener noreferrer">
                        Nova Theme
                    </a>
                <?php endif; ?>
                
                <?php if ($footer_load_time) : ?>
                    &nbsp;&nbsp;<?php esc_html_e('页面生成时间：', 'nova'); ?>
                    <?php timer_stop(1); ?>
                    <?php esc_html_e('秒', 'nova'); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</footer>

<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('返回顶部', 'nova'); ?>" title="<?php esc_attr_e('返回顶部', 'nova'); ?>">
    ↑
</button>

<?php wp_footer(); ?>

</body>
</html>