<?php
/**
 * 头部模板
 *
 * @package Nova
 */
?>
<!DOCTYPE html>
<script>
// 初始主题设置
(function() {
    const theme = localStorage.getItem('theme') || 'auto';
    const html = document.documentElement;
    html.classList.remove('dark', 'light', 'auto');
    if (theme === 'dark') {
        html.classList.add('dark');
    } else if (theme === 'light') {
        html.classList.add('light');
    } else {
        html.classList.add('auto');
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.classList.add('dark');
        }
    }
})();
</script>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('跳转到内容', 'nova'); ?></a>


<header class="site-header">
    <div class="header-container container">
        <div class="header-logo">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                if (is_front_page() && is_home()) :
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                else :
                    ?>
                    <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                    <?php
                endif;
            }
            ?>
        </div>

        <nav class="header-menu" role="navigation" aria-label="<?php esc_attr_e('主导航', 'nova'); ?>">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'gore',
                    'items_wrap'     => '<ul class="gore">%3$s</ul>',
                ));
            } else {
                echo '<ul class="gore">';
                echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('首页', 'nova') . '</a></li>';
                // 显示分类
                $categories = get_categories(array('orderby' => 'name', 'order' => 'ASC', 'number' => 5));
                foreach ($categories as $category) {
                    echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
                echo '</ul>';
            }
            ?>
        </nav>

        <div class="header-actions">
            <a href="<?php echo esc_url(get_feed_link()); ?>" class="iconButton" aria-label="<?php esc_attr_e('RSS订阅', 'nova'); ?>" title="<?php esc_attr_e('RSS订阅', 'nova'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 11a9 9 0 0 1 9 9"></path>
                    <path d="M4 4a16 16 0 0 1 16 16"></path>
                    <circle cx="5" cy="19" r="1"></circle>
                </svg>
            </a>
            <button class="iconButton goFind" aria-label="<?php esc_attr_e('搜索', 'nova'); ?>" title="<?php esc_attr_e('搜索', 'nova'); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 19L13 13M15 8C15 11.866 11.866 15 8 15C4.13401 15 1 11.866 1 8C1 4.13401 4.13401 1 8 1C11.866 1 15 4.13401 15 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="iconButton menu-toggle mobile" aria-controls="primary-menu" aria-expanded="false">
                <span class="screen-reader-text"><?php esc_html_e('菜单', 'nova'); ?></span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- 搜索框 -->
    <div class="site-search none">
        <form method="get" class="site-form flex" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" class="field" placeholder="<?php esc_attr_e('输入关键词进行搜索…', 'nova'); ?>" maxlength="2048" autocomplete="off" value="<?php echo get_search_query(); ?>" name="s" required>
            <button type="button" class="iconButton closeFind" aria-label="<?php esc_attr_e('关闭搜索', 'nova'); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </form>
    </div>
</header>