<?php
/**
 * Nova Theme Functions
 * 
 * @Author: 陌涛
 * @Email:  imotao@88.com
 * @Link:   imotao.com
 * @Date:   2025-10-27
 * @Last Modified by:   MoTao
 * @Last Modified time: 2025-10-29
 * 
 * @package Nova
 * @version 1.5.2
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 定义主题版本
define('NOVA_VERSION', '1.5.2');
define('NOVA_DIR', get_template_directory());
define('NOVA_URI', get_template_directory_uri());

// 加载Markdown解析器
require_once NOVA_DIR . '/includes/Markdown.php';

/**
 * 设置主题功能
 */
function nova_setup() {
    // 添加主题支持
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // 启用链接管理器（用于友情链接）
    add_filter('pre_option_link_manager_enabled', '__return_true');
    
    // 添加自定义logo支持
    add_theme_support('custom-logo', array(
        'height'      => 50,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    
    
    // 添加响应式嵌入支持
    add_theme_support('responsive-embeds');
    
    // 添加文章格式支持
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
    ));
    
    // 添加选择性刷新支持
    add_theme_support('customize-selective-refresh-widgets');
    
    // Gutenberg编辑器支持
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // 编辑器颜色调色板
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('主色调', 'nova'),
            'slug'  => 'primary',
            'color' => '#5bc0eb',
        ),
        array(
            'name'  => __('辅助色', 'nova'),
            'slug'  => 'secondary',
            'color' => '#333333',
        ),
        array(
            'name'  => __('成功色', 'nova'),
            'slug'  => 'success',
            'color' => '#3bb273',
        ),
        array(
            'name'  => __('警告色', 'nova'),
            'slug'  => 'warning',
            'color' => '#ffc320',
        ),
        array(
            'name'  => __('危险色', 'nova'),
            'slug'  => 'danger',
            'color' => '#e15554',
        ),
    ));
    
    // 编辑器字体大小
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('小', 'nova'),
            'size' => 14,
            'slug' => 'small',
        ),
        array(
            'name' => __('正常', 'nova'),
            'size' => 16,
            'slug' => 'normal',
        ),
        array(
            'name' => __('大', 'nova'),
            'size' => 20,
            'slug' => 'large',
        ),
        array(
            'name' => __('超大', 'nova'),
            'size' => 24,
            'slug' => 'extra-large',
        ),
    ));
    
    // 添加Starter Content支持
    add_theme_support('starter-content', array(
        'posts' => array(
            'home' => array(
                'post_type' => 'page',
                'post_title' => __('首页', 'nova'),
                'post_content' => __('欢迎使用Nova主题！这是一个专为内容创作者打造的WordPress主题。', 'nova'),
            ),
            'about' => array(
                'post_type' => 'page',
                'post_title' => __('关于', 'nova'),
                'post_content' => __('这里可以介绍您的网站或个人信息。', 'nova'),
            ),
            'contact' => array(
                'post_type' => 'page',
                'post_title' => __('联系', 'nova'),
                'post_content' => __('这里可以添加联系方式或联系表单。', 'nova'),
            ),
        ),
        'options' => array(
            'show_on_front' => 'page',
            'page_on_front' => '{{home}}',
            'page_for_posts' => '{{blog}}',
        ),
        'theme_mods' => array(
            'nova_footer_copyright' => __('© 2025 您的网站名称. 保留所有权利.', 'nova'),
            'nova_enable_markdown' => true,
            'nova_enable_reading_progress' => true,
        ),
        'nav_menus' => array(
            'primary' => array(
                'name' => __('主导航菜单', 'nova'),
                'items' => array(
                    'link_home',
                    'page_about',
                    'page_contact',
                ),
            ),
        ),
    ));
    
    // 注册导航菜单
    register_nav_menus(array(
        'primary' => __('主导航菜单', 'nova'),
        'footer'  => __('页脚菜单', 'nova'),
    ));
    
    // 设置内容宽度（单栏布局）
    $GLOBALS['content_width'] = 800;
}
add_action('after_setup_theme', 'nova_setup');


/**
 * 获取CDN基础URL
 */
function nova_get_cdn_url() {
    return get_theme_mod('nova_cdn_url', 'https://cdnjs.cloudflare.com/ajax/libs');
}

/**
 * 获取Gravatar加速URL
 */
function nova_get_gravatar_cdn() {
    $cdn = get_theme_mod('nova_gravatar_cdn', 'geekzu');
    
    $cdn_urls = array(
        'geekzu' => 'https://sdn.geekzu.org/avatar/',
        'loli' => 'https://gravatar.loli.net/avatar/',
        'sep_cc' => 'https://gravatar.sep.cc/avatar/',
        '7ed' => 'https://sdn.geekzu.org/avatar/',
        'cravatar' => 'https://cravatar.cn/avatar/',
    );
    
    return isset($cdn_urls[$cdn]) ? $cdn_urls[$cdn] : '';
}

/**
 * 替换Gravatar URL
 */
function nova_replace_gravatar_url($avatar) {
    $cdn_url = nova_get_gravatar_cdn();
    
    if (empty($cdn_url)) {
        return $avatar;
    }
    
    // 替换gravatar.com为加速地址
    $avatar = str_replace(array(
        'www.gravatar.com/avatar',
        '0.gravatar.com/avatar',
        '1.gravatar.com/avatar',
        '2.gravatar.com/avatar',
        'secure.gravatar.com/avatar',
    ), str_replace('https://', '', $cdn_url), $avatar);
    
    return $avatar;
}
add_filter('get_avatar_url', 'nova_replace_gravatar_url', 10, 1);

/**
 * 获取Google字体加速URL
 */
function nova_get_google_fonts_cdn() {
    $cdn = get_theme_mod('nova_google_fonts_cdn', 'geekzu');
    
    $cdn_urls = array(
        'geekzu' => 'https://fonts.geekzu.org',
        'loli' => 'https://fonts.loli.net',
        'ustc' => 'https://fonts.lug.ustc.edu.cn',
    );
    
    return isset($cdn_urls[$cdn]) ? $cdn_urls[$cdn] : '';
}

/**
 * 替换Google字体URL
 */
function nova_replace_google_fonts_url($fonts_url) {
    $cdn_url = nova_get_google_fonts_cdn();
    
    if (empty($cdn_url)) {
        return $fonts_url;
    }
    
    // 替换Google字体URL
    $fonts_url = str_replace('fonts.googleapis.com', str_replace('https://', '', $cdn_url), $fonts_url);
    $fonts_url = str_replace('fonts.gstatic.com', str_replace('https://', '', $cdn_url), $fonts_url);
    
    return $fonts_url;
}
add_filter('style_loader_src', 'nova_replace_google_fonts_url', 10, 1);

/**
 * 加载样式和脚本
 */
function nova_enqueue_scripts() {
    // 主题样式
    wp_enqueue_style('nova-style', get_stylesheet_uri(), array(), NOVA_VERSION);
    
    // 主JavaScript文件
    wp_enqueue_script('nova-main', NOVA_URI . '/assets/js/main.js', array(), NOVA_VERSION, true);
    
    // Gutenberg编辑器样式
    add_editor_style('assets/css/editor-style.css');
    
    // 代码高亮 - 仅在文章和页面加载
    if (is_singular()) {
        $cdn_url = nova_get_cdn_url();
        
        // Highlight.js库 - 优先使用本地文件
        $local_highlight = NOVA_URI . '/assets/js/libs/highlight.min.js';
        if (file_exists(NOVA_DIR . '/assets/js/libs/highlight.min.js')) {
            wp_enqueue_script('highlight-js', $local_highlight, array(), '11.9.0', true);
        } else {
            wp_enqueue_script('highlight-js', $cdn_url . '/highlight.js/11.9.0/highlight.min.js', array(), '11.9.0', true);
        }
        
        // Highlight.js 常用语言包
        $local_languages = NOVA_URI . '/assets/js/libs/highlight-languages.min.js';
        if (file_exists(NOVA_DIR . '/assets/js/libs/highlight-languages.min.js')) {
            wp_enqueue_script('highlight-js-languages', $local_languages, array('highlight-js'), '11.9.0', true);
        } else {
            wp_enqueue_script('highlight-js-languages', $cdn_url . '/highlight.js/11.9.0/languages.min.js', array('highlight-js'), '11.9.0', true);
        }
        
        // 行号插件
        $local_line_numbers = NOVA_URI . '/assets/js/libs/highlightjs-line-numbers.min.js';
        if (file_exists(NOVA_DIR . '/assets/js/libs/highlightjs-line-numbers.min.js')) {
            wp_enqueue_script('highlight-js-line-numbers', $local_line_numbers, array('highlight-js'), '2.8.0', true);
        } else {
            wp_enqueue_script('highlight-js-line-numbers', $cdn_url . '/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js', array('highlight-js'), '2.8.0', true);
        }
        
        // 二维码生成库 - 优先使用本地文件
        $local_qrcode = NOVA_URI . '/assets/js/libs/qrcode.min.js';
        if (file_exists(NOVA_DIR . '/assets/js/libs/qrcode.min.js')) {
            wp_enqueue_script('qrcode', $local_qrcode, array(), '1.0.0', true);
        } else {
            wp_enqueue_script('qrcode', $cdn_url . '/qrcodejs/1.0.0/qrcode.min.js', array(), '1.0.0', true);
        }
    }
    
    // 本地化脚本数据
    wp_localize_script('nova-main', 'novaData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('nova-nonce'),
        'debug'   => defined('WP_DEBUG') && WP_DEBUG,
        'enableReadingProgress' => get_theme_mod('nova_enable_reading_progress', true),
    ));
    
    // 评论回复脚本
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'nova_enqueue_scripts');

/**
 * 移除不必要的WordPress头部标签和禁用emoji
 */
function nova_cleanup_head() {
    // 移除不必要的头部标签
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    
    // 禁用表情符号
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('emoji_svg_url', '__return_false');
    
    // 移除WordPress版本信息
    add_filter('the_generator', '__return_empty_string');
}
add_action('init', 'nova_cleanup_head');

/**
 * 性能优化：移除不必要的脚本和样式
 */
function nova_optimize_scripts() {
    // 移除jQuery（如果不需要）
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
    
    // 移除Block Editor样式（如果不需要）
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style');
    }
    
    // 移除WordPress嵌入脚本
    wp_deregister_script('wp-embed');
}
add_action('wp_enqueue_scripts', 'nova_optimize_scripts', 100);

/**
 * 添加自定义内容长度
 */
function nova_excerpt_length($length) {
    return 55;
}
add_filter('excerpt_length', 'nova_excerpt_length');

/**
 * 自定义摘要更多文本
 */
function nova_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'nova_excerpt_more');


/**
 * 获取文章阅读时间
 */
function nova_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 假设每分钟阅读200字
    
    return $reading_time;
}

/**
 * 获取文章阅读量（带缓存和大小限制）
 */
function nova_get_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // 使用缓存避免重复查询，带大小限制
    static $cache = array();
    static $cache_count = 0;
    $max_cache_size = 100;
    
    if (isset($cache[$post_id])) {
        return $cache[$post_id];
    }
    
    // 清理缓存以防止内存溢出
    if ($cache_count >= $max_cache_size) {
        $cache = array_slice($cache, -50, null, true);
        $cache_count = 50;
    }
    
    // 优先级：WordPress Popular Posts > Jetpack > Nova自己的统计 > 其他插件的统计
    $views = 0;
    
    // 1. 检查 WordPress Popular Posts 插件
    if (function_exists('wpp_get_views')) {
        $views = wpp_get_views($post_id);
    }
    // 2. 检查 Jetpack Stats
    elseif (function_exists('stats_get_csv')) {
        $post_url = get_permalink($post_id);
        $views = get_post_meta($post_id, 'jetpack_post_views', true);
    }
    // 3. 优先使用Nova自己的统计字段
    elseif ($nova_views = get_post_meta($post_id, 'nova_post_views', true)) {
        $views = is_numeric($nova_views) ? intval($nova_views) : 0;
    }
    // 4. 回退到其他常见的浏览量meta字段
    elseif ($other_views = get_post_meta($post_id, 'views', true)) {
        $views = intval($other_views);
    }
    
    // 缓存结果
    $cache[$post_id] = $views;
    $cache_count++;
    
    return $views;
}

/**
 * 设置文章阅读量
 */
function nova_set_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // 获取当前阅读量
    $current_views = nova_get_post_views($post_id);
    
    // 增加1
    $new_views = $current_views + 1;
    
    // 保存到主题自己的字段，同时尝试保持兼容性
    update_post_meta($post_id, 'nova_post_views', $new_views);
    
    // 如果之前使用的是其他字段，也更新一下保持同步
    $old_views = get_post_meta($post_id, 'views', true);
    if ($old_views && !get_post_meta($post_id, 'nova_migrated', true)) {
        // 首次迁移：将旧数据合并
        if ($old_views > $new_views) {
            update_post_meta($post_id, 'nova_post_views', $old_views);
            $new_views = $old_views;
        }
        update_post_meta($post_id, 'nova_migrated', true);
    }
    
    return $new_views;
}

/**
 * AJAX更新阅读量
 */
function nova_update_post_views() {
    // 检查nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nova-nonce')) {
        wp_send_json_error(array('message' => 'Nonce验证失败'));
        return;
    }
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if ($post_id && get_post($post_id)) {
        // 获取当前阅读量
        $current_views = nova_get_post_views($post_id);
        
        // 增加1
        $new_views = $current_views + 1;
        
        // 更新数据库
        update_post_meta($post_id, 'nova_post_views', $new_views);
        
        // 记录日志（仅在开发环境）
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Nova: 文章 ' . $post_id . ' 阅读量从 ' . $current_views . ' 更新到 ' . $new_views);
        }
        
        wp_send_json_success(array(
            'views' => $new_views,
            'previous_views' => $current_views,
            'message' => '阅读量更新成功'
        ));
    } else {
        wp_send_json_error(array('message' => '无效的文章ID'));
    }
}
add_action('wp_ajax_nova_update_views', 'nova_update_post_views');
add_action('wp_ajax_nopriv_nova_update_views', 'nova_update_post_views');

/**
 * 批量迁移旧浏览数据（优化版）
 */

/**
 * 自定义分页导航
 */
function nova_pagination() {
    if (is_singular()) {
        return;
    }
    
    global $wp_query;
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max   = intval($wp_query->max_num_pages);
    
    if ($max <= 1) {
        return;
    }
    
    echo '<nav class="pagination" role="navigation" aria-label="' . esc_attr__('分页导航', 'nova') . '">';
    
    $pagination = paginate_links(array(
        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '',
        'current'   => max(1, $paged),
        'total'    => $max,
        'prev_text' => '&laquo; ' . __('上一页', 'nova'),
        'next_text' => __('下一页', 'nova') . ' &raquo;',
        'type'     => 'list',
        'end_size' => 2,
        'mid_size' => 2,
    ));
    
    // paginate_links已经转义，但使用wp_kses_post更安全
    echo wp_kses_post($pagination);
    echo '</nav>';
}

/**
 * 添加主题自定义面板
 */
function nova_customize_register($wp_customize) {
    // 移除默认的头部部分，使用自定义的
    $wp_customize->remove_section('header_image');
    
    // 移除WordPress默认的头部文字颜色设置
    $wp_customize->remove_control('header_textcolor');
    $wp_customize->remove_setting('header_textcolor');
    
    // ========================================
    // CDN设置部分
    // ========================================
    $wp_customize->add_section('nova_cdn', array(
        'title'    => __('Nova CDN设置', 'nova'),
        'priority' => 160,
    ));
    
    // CDN基础URL
    $wp_customize->add_setting('nova_cdn_url', array(
        'default'           => 'https://cdnjs.cloudflare.com/ajax/libs',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nova_cdn_url', array(
        'label'       => __('前端公共库CDN', 'nova'),
        'description' => __('选择前端公共库CDN服务（地址显示在选择项中）', 'nova'),
        'section'     => 'nova_cdn',
        'type'        => 'select',
        'choices'     => array(
            'https://cdnjs.cloudflare.com/ajax/libs' => 'cdnjs.cloudflare.com - https://cdnjs.cloudflare.com/ajax/libs',
            'https://s4.zstatic.net/ajax/libs' => 's4.zstatic.net - https://s4.zstatic.net/ajax/libs',
            'https://cdnjs.snrat.com/ajax/libs' => 'cdnjs.snrat.com - https://cdnjs.snrat.com/ajax/libs',
            'https://lib.baomitu.com' => 'lib.baomitu.com - https://lib.baomitu.com',
            'https://cdnjs.loli.net/ajax/libs' => 'cdnjs.loli.net - https://cdnjs.loli.net/ajax/libs',
            'https://use.sevencdn.com/ajax/libs' => 'use.sevencdn.com - https://use.sevencdn.com/ajax/libs',
        ),
    ));
    
    // Gravatar加速
    $wp_customize->add_setting('nova_gravatar_cdn', array(
        'default'           => 'geekzu',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_gravatar_cdn', array(
        'label'       => __('Gravatar加速', 'nova'),
        'description' => __('选择Gravatar头像加速服务', 'nova'),
        'section'     => 'nova_cdn',
        'type'        => 'select',
        'choices'     => array(
            '' => '—使用默认—',
            'geekzu' => '极客族 Gravatar - https://sdn.geekzu.org/avatar/',
            'loli' => 'loli Gravatar - https://gravatar.loli.net/avatar/',
            'sep_cc' => 'sep.cc Gravatar - https://gravatar.sep.cc/avatar/',
            '7ed' => '7ED Gravatar - https://sdn.geekzu.org/avatar/',
            'cravatar' => 'Cravatar - https://cravatar.cn/avatar/',
        ),
    ));
    
    // Google字体加速
    $wp_customize->add_setting('nova_google_fonts_cdn', array(
        'default'           => 'geekzu',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_google_fonts_cdn', array(
        'label'       => __('Google字体加速', 'nova'),
        'description' => __('选择Google字体加速服务', 'nova'),
        'section'     => 'nova_cdn',
        'type'        => 'select',
        'choices'     => array(
            '' => '—使用默认—',
            'geekzu' => '极客族 Google字体 - https://fonts.geekzu.org',
            'loli' => 'loli Google字体 - https://fonts.loli.net',
            'ustc' => '中科大 Google字体 - https://fonts.lug.ustc.edu.cn',
        ),
    ));
    
    // ========================================
    // 功能设置部分
    // ========================================
    $wp_customize->add_section('nova_features', array(
        'title'    => __('Nova 功能设置', 'nova'),
        'priority' => 28,
    ));
    
    // Markdown评论支持
    $wp_customize->add_setting('nova_enable_markdown', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('nova_enable_markdown', array(
        'label'       => __('启用Markdown评论', 'nova'),
        'description' => __('允许评论者使用Markdown格式（**粗体**、*斜体*、`代码`、[链接](url)等）', 'nova'),
        'section'     => 'nova_features',
        'type'        => 'checkbox',
    ));
    
    // 阅读进度百分比
    $wp_customize->add_setting('nova_enable_reading_progress', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('nova_enable_reading_progress', array(
        'label'       => __('启用阅读进度百分比', 'nova'),
        'description' => __('在文章页面顶部显示阅读进度条，实时反映文章阅读进度', 'nova'),
        'section'     => 'nova_features',
        'type'        => 'checkbox',
    ));
    
    
    // ========================================
    // 页脚设置部分
    // ========================================
    $wp_customize->add_section('nova_footer', array(
        'title'    => __('Nova 页脚设置', 'nova'),
        'priority' => 40,
    ));
    
    // 自定义页脚版权信息
    $wp_customize->add_setting('nova_footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('nova_footer_copyright', array(
        'label'       => __('自定义页脚版权信息', 'nova'),
        'description' => __('留空则使用默认版权信息，支持HTML标签', 'nova'),
        'section'     => 'nova_footer',
        'type'        => 'textarea',
    ));
    
    // ICP备案号
    $wp_customize->add_setting('nova_footer_icp', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_footer_icp', array(
        'label'   => __('ICP备案号', 'nova'),
        'section' => 'nova_footer',
        'type'    => 'text',
    ));
    
    // 公安备案号
    $wp_customize->add_setting('nova_footer_gaba', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_footer_gaba', array(
        'label'   => __('公安备案号', 'nova'),
        'section' => 'nova_footer',
        'type'    => 'text',
    ));
    
    // 公安备案跳转链接
    $wp_customize->add_setting('nova_footer_gaba_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nova_footer_gaba_url', array(
        'label'   => __('公安备案跳转链接', 'nova'),
        'section' => 'nova_footer',
        'type'    => 'url',
    ));
    
    // 显示友情链接
    $wp_customize->add_setting('nova_footer_links', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('nova_footer_links', array(
        'label'       => __('显示友情链接', 'nova'),
        'description' => __('可以通过菜单或WordPress链接管理器添加', 'nova'),
        'section'     => 'nova_footer',
        'type'        => 'checkbox',
    ));
    
    // 显示主题版权信息
    $wp_customize->add_setting('nova_footer_theme_credit', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('nova_footer_theme_credit', array(
        'label'   => __('显示主题版权信息', 'nova'),
        'section' => 'nova_footer',
        'type'    => 'checkbox',
    ));
    
    // 显示网站加载时间
    $wp_customize->add_setting('nova_footer_load_time', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('nova_footer_load_time', array(
        'label'   => __('显示网站加载时间', 'nova'),
        'section' => 'nova_footer',
        'type'    => 'checkbox',
    ));
    
    // ========================================
    // 首页样式设置部分
    // ========================================
    $wp_customize->add_section('nova_home_style', array(
        'title'    => __('Nova 首页样式', 'nova'),
        'priority' => 50,
    ));
    
    // 首页文章卡片样式
    $wp_customize->add_setting('nova_home_card_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_home_card_style', array(
        'label'   => __('首页文章卡片样式', 'nova'),
        'section' => 'nova_home_style',
        'type'    => 'select',
        'choices' => array(
            'default' => __('默认样式（横向卡片）', 'nova'),
            'nova'  => __('网格样式', 'nova'),
        ),
    ));
    
    // 网格样式默认图片
    $wp_customize->add_setting('nova_home_default_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'nova_home_default_image', array(
        'label'       => __('网格样式默认图片', 'nova'),
        'description' => __('当文章没有缩略图时使用此图片', 'nova'),
        'section'     => 'nova_home_style',
        'settings'    => 'nova_home_default_image',
    )));
}
add_action('customize_register', 'nova_customize_register');


/**
 * 增强搜索功能
 */
function nova_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $query->set('post_type', 'post');
        }
    }
}
add_action('pre_get_posts', 'nova_search_filter');


/**
 * 添加作者信息框
 */
function nova_author_box() {
    if (!is_single()) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name');
    $author_bio = get_the_author_meta('description');
    $author_url = get_author_posts_url($author_id);
    
    if (empty($author_bio)) {
        return;
    }
    
    echo '<div class="author-box">';
    echo '<div class="author-avatar">' . get_avatar($author_id, 96) . '</div>';
    echo '<div class="author-info">';
    echo '<h3 class="author-name"><a href="' . esc_url($author_url) . '">' . esc_html($author_name) . '</a></h3>';
    echo '<p class="author-bio">' . esc_html($author_bio) . '</p>';
    echo '</div>';
    echo '</div>';
}

/**
 * 添加结构化数据（增强版）
 */
function nova_add_structured_data() {
    if (!is_single()) {
        return;
    }
    
    global $post;
    
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
    $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
    
    // 文章基础结构化数据
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'BlogPosting',
        'headline' => get_the_title(),
        'image'    => $image ? $image[0] : '',
        'datePublished' => get_the_date('c'),
        'dateModified'  => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name'  => get_the_author(),
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
            'logo'  => array(
                '@type' => 'ImageObject',
                'url'   => $logo ? $logo[0] : '',
            ),
        ),
        'description' => wp_trim_words(get_the_excerpt(), 20),
    );
    
    // 添加评论结构化数据
    $comments = get_comments(array(
        'post_id' => $post->ID,
        'status' => 'approve',
        'number' => 10
    ));
    
    if (!empty($comments)) {
        $schema['commentCount'] = get_comments_number();
        $schema['comment'] = array();
        
        foreach ($comments as $comment) {
            $schema['comment'][] = array(
                '@type' => 'Comment',
                'author' => array(
                    '@type' => 'Person',
                    'name' => $comment->comment_author,
                    'url' => $comment->comment_author_url ? $comment->comment_author_url : ''
                ),
                'datePublished' => get_comment_date('c', $comment->comment_ID),
                'text' => wp_strip_all_tags($comment->comment_content)
            );
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'nova_add_structured_data');

/**
 * 安全增强：移除版本信息
 */
function nova_remove_version() {
    return '';
}
add_filter('the_generator', 'nova_remove_version');

/**
 * 添加HTTP安全头部
 */
function nova_security_headers() {
    // X-Content-Type-Options: 防止MIME类型嗅探
    header('X-Content-Type-Options: nosniff');
    
    // X-Frame-Options: 防止点击劫持
    header('X-Frame-Options: SAMEORIGIN');
    
    // X-XSS-Protection: 启用XSS过滤器
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer-Policy: 控制referrer信息
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions-Policy: 限制功能使用
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}
add_action('send_headers', 'nova_security_headers');

/**
 * Markdown评论支持
 */
function nova_markdown_comments($comment_text) {
    if (!get_theme_mod('nova_enable_markdown', false)) {
        return $comment_text;
    }
    
    // 使用Markdown解析器
    $parsed = Nova_Markdown::parse($comment_text);
    
    return $parsed;
}
add_filter('comment_text', 'nova_markdown_comments', 10, 1);

/**
 * 面包屑导航
 */
function nova_breadcrumbs() {
    // 首页不显示面包屑
    if (is_front_page()) {
        return;
    }
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('面包屑导航', 'nova') . '">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // 首页
    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url(home_url('/')) . '">';
    echo '<span itemprop="name">' . esc_html__('首页', 'nova') . '</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';
    
    $position = 2;
    
    // 分类页
    if (is_category()) {
        $category = get_queried_object();
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html($category->name) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    // 标签页
    elseif (is_tag()) {
        $tag = get_queried_object();
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__('标签: ', 'nova') . esc_html($tag->name) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    // 单篇文章
    elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a itemprop="item" href="' . esc_url(get_category_link($category->term_id)) . '">';
            echo '<span itemprop="name">' . esc_html($category->name) . '</span></a>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            $position++;
        }
        
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    // 静态页面
    elseif (is_page()) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    // 搜索结果
    elseif (is_search()) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__('搜索结果', 'nova') . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    // 404页面
    elseif (is_404()) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__('404', 'nova') . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * 性能优化：预加载关键资源
 */
function nova_preload_critical_resources() {
    // 只在非后台且非feed页面预加载
    if (is_admin() || is_feed()) {
        return;
    }
    
    // 移除CSS预加载，因为WordPress会自动处理CSS的优先级
    // 这样可以避免浏览器警告
    
    // 如果需要预加载字体或其他资源，可以在这里添加
    // echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>';
}
add_action('wp_head', 'nova_preload_critical_resources', 1);


/**
 * 确保评论数量正确更新
 */
function nova_update_comment_count_on_comment_post($comment_id, $comment_approved, $commentdata) {
    if ($comment_approved == 1) {
        wp_update_comment_count_now($commentdata['comment_post_ID']);
    }
}
add_action('comment_post', 'nova_update_comment_count_on_comment_post', 10, 3);

/**
 * 确保评论删除时更新计数
 */
function nova_update_comment_count_on_delete($comment_id) {
    $comment = get_comment($comment_id);
    if ($comment) {
        wp_update_comment_count_now($comment->comment_post_ID);
    }
}
add_action('delete_comment', 'nova_update_comment_count_on_delete');
add_action('trash_comment', 'nova_update_comment_count_on_delete');
add_action('untrash_comment', 'nova_update_comment_count_on_delete');

/**
 * 检查是否为友链（带缓存）
 */
function nova_is_friend_link($url = '') {
    if (empty($url)) {
        return false;
    }
    
    // 验证URL格式
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    // 使用缓存
    $cache_key = 'nova_friend_links_' . md5($url);
    $cached = wp_cache_get($cache_key);
    if ($cached !== false) {
        return $cached;
    }
    
    // 获取友情链接（使用缓存优化的查询）
    $bookmarks = get_bookmarks(array(
        'limit' => -1,
        'orderby' => 'name',
        'order' => 'ASC'
    ));
    
    $result = false;
    
    // 提取评论URL的域名
    $comment_domain = parse_url($url, PHP_URL_HOST);
    
    if ($comment_domain) {
        foreach ($bookmarks as $bookmark) {
            // 提取域名进行比较
            $bookmark_domain = parse_url($bookmark->link_url, PHP_URL_HOST);
            
            if ($bookmark_domain && $bookmark_domain === $comment_domain) {
                $result = true;
                break;
            }
        }
    }
    
    // 缓存结果1小时
    wp_cache_set($cache_key, $result, '', 3600);
    
    return $result;
}

/**
 * 在评论作者名后添加友链图标
 */
function nova_add_friend_icon($author, $comment_id) {
    $comment = get_comment($comment_id);
    
    if ($comment && !empty($comment->comment_author_url)) {
        if (nova_is_friend_link($comment->comment_author_url)) {
            $icon = '<svg viewBox="0 0 64 64" fill="none" role="presentation" aria-hidden="true" focusable="false" class="friend-icon" title="友好链接" style="display: inline-block; width: 16px; height: 16px; margin-left: 4px; vertical-align: middle;"><path fill-rule="evenodd" clip-rule="evenodd" d="M56.48 38.3C58.13 36.58 60 34.6 60 32c0-2.6-1.88-4.57-3.52-6.3-.95-.97-1.98-2.05-2.3-2.88-.33-.82-.35-2.17-.38-3.49-.02-2.43-.07-5.2-2-7.13-1.92-1.92-4.7-1.97-7.13-2h-.43c-1.17-.02-2.29-.04-3.07-.38-.87-.37-1.9-1.35-2.87-2.3C36.58 5.89 34.6 4 32 4c-2.6 0-4.57 1.88-6.3 3.53-.97.94-2.05 1.97-2.88 2.3-.82.32-2.17.34-3.49.37-2.43.03-5.2.08-7.13 2-1.92 1.93-1.97 4.7-2 7.13v.43c-.02 1.17-.04 2.29-.38 3.06-.37.88-1.35 1.9-2.3 2.88C5.89 27.43 4 29.4 4 32c0 2.6 1.88 4.58 3.53 6.3.94.98 1.97 2.05 2.3 2.88.32.82.34 2.17.37 3.49.03 2.43.08 5.2 2 7.13 1.93 1.93 4.7 1.98 7.13 2h.43c1.17.02 2.29.04 3.06.38.88.37 1.9 1.34 2.88 2.3C27.43 58.13 29.4 60 32 60c2.6 0 4.58-1.88 6.3-3.52.98-.95 2.05-1.98 2.88-2.3.82-.33 2.17-.35 3.49-.38 2.43-.02 5.2-.07 7.13-2 1.93-1.92 1.98-4.7 2-7.13v-.43c.02-1.17.04-2.29.38-3.07.37-.87 1.34-1.9 2.3-2.87zM33.1 45.15c-.66.47-1.55.47-2.22 0C27.57 42.8 18 35.76 18 28.9c0-6.85 6.5-10.25 13.26-4.45.43.37 1.05.37 1.48 0 6.76-5.8 13.27-2.4 13.26 4.45 0 6.56-9.57 13.9-12.89 16.24z" fill="#FFC017"></path></svg>';
            return $author . $icon;
        }
    }
    
    return $author;
}
add_filter('get_comment_author', 'nova_add_friend_icon', 10, 2);

/**
 * 获取文章点赞数
 */
function nova_get_post_likes($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $likes = get_post_meta($post_id, 'nova_post_likes', true);
    return is_numeric($likes) ? intval($likes) : 0;
}

/**
 * AJAX点赞文章
 */
function nova_post_like() {
    // 检查nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nova-nonce')) {
        wp_send_json_error(array('message' => 'Nonce验证失败'));
        return;
    }
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if ($post_id && get_post($post_id)) {
        // 获取当前点赞数
        $current_likes = nova_get_post_likes($post_id);
        
        // 增加1
        $new_likes = $current_likes + 1;
        
        // 更新数据库
        update_post_meta($post_id, 'nova_post_likes', $new_likes);
        
        wp_send_json_success(array(
            'likes' => $new_likes,
            'previous_likes' => $current_likes,
            'message' => '点赞成功'
        ));
    } else {
        wp_send_json_error(array('message' => '无效的文章ID'));
    }
}
add_action('wp_ajax_nova_post_like', 'nova_post_like');
add_action('wp_ajax_nopriv_nova_post_like', 'nova_post_like');

/**
 * ========================================
 * 新增功能 - 1. 文章置顶标识
 * ========================================
 */
function nova_sticky_badge() {
    if (is_sticky()) {
        echo '<span class="sticky-badge">' . esc_html__('置顶', 'nova') . '</span>';
    }
}

/**
 * ========================================
 * 新增功能 - 2. 站点统计展示
 * ========================================
 */

/**
 * ========================================
 * 新增功能 - 3. 相关文章推荐
 * ========================================
 */
function nova_related_posts() {
    if (!is_single()) {
        return;
    }
    
    $categories = get_the_category();
    if (empty($categories)) {
        return;
    }
    
    $args = array(
        'category__in' => array($categories[0]->term_id),
        'post__not_in' => array(get_the_ID()),
        'posts_per_page' => 3,
        'orderby' => 'rand',
        'no_found_rows' => true,
    );
    
    $related = new WP_Query($args);
    
    if ($related->have_posts()) {
        echo '<div class="related-posts">';
        echo '<h3 class="related-posts-title">' . esc_html__('相关文章', 'nova') . '</h3>';
        echo '<div class="related-posts-grid">';
        
        while ($related->have_posts()) {
            $related->the_post();
            echo '<article class="related-post-item">';
            
            if (has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '" class="related-post-thumb">';
                the_post_thumbnail('thumbnail', array('alt' => get_the_title()));
                echo '</a>';
            }
            
            echo '<div class="related-post-content">';
            echo '<h4 class="related-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
            echo '<p class="related-post-excerpt">' . esc_html(wp_trim_words(get_the_excerpt(), 15)) . '</p>';
            echo '<div class="related-post-meta">';
            echo '<span class="related-post-date">' . esc_html(get_the_date()) . '</span>';
            echo '<span class="related-post-views">' . esc_html(nova_get_post_views()) . ' ' . esc_html__('阅读', 'nova') . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</article>';
        }
        
        echo '</div>';
        echo '</div>';
        wp_reset_postdata();
    }
}

/**
 * ========================================
 * 新增功能 - 4. 社交分享按钮
 * ========================================
 */


/**
 * ========================================
 * 新增功能 - 6. SEO功能
 * ========================================
 */

/**
 * 自定义网站标题（最终版 - 完全控制标题输出）
 */
function nova_document_title($title) {
    // 只在首页应用自定义设置
    if (!is_front_page()) {
        return $title;
    }
    
    // 获取分隔符
    $separator = get_theme_mod('nova_seo_title_separator', '|');
    
    // 获取自定义首页标题
    $custom_title = get_theme_mod('nova_seo_home_title', '');
    
    if (!empty($custom_title)) {
        // 如果设置了自定义标题，直接返回自定义标题
        return esc_html($custom_title);
    } else {
        // 留空则自动调用站点标题+副标题
        $site_name = get_bloginfo('name');
        $tagline = get_bloginfo('description');
        
        if (!empty($tagline)) {
            // 站点标题 + 分隔符 + 副标题
            return esc_html($site_name . ' ' . $separator . ' ' . $tagline);
        } else {
            // 只有站点标题
            return esc_html($site_name);
        }
    }
}
add_filter('pre_get_document_title', 'nova_document_title', 999);

/**
 * 添加SEO元数据
 */
function nova_add_seo_meta() {
    // 首页SEO
    if (is_front_page()) {
        $keywords = get_theme_mod('nova_seo_home_keywords', '');
        $description = get_theme_mod('nova_seo_home_description', '');
        
        if (!empty($keywords)) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        if (!empty($description)) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'nova_add_seo_meta', 1);

/**
 * 注册SEO设置面板
 */
function nova_customize_seo_settings($wp_customize) {
    // SEO设置部分
    $wp_customize->add_section('nova_seo', array(
        'title'    => __('Nova SEO设置', 'nova'),
        'priority' => 35,
    ));
    
    // 标题分隔符
    $wp_customize->add_setting('nova_seo_title_separator', array(
        'default'           => '|',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_seo_title_separator', array(
        'label'       => __('标题分隔符', 'nova'),
        'description' => __('用于分隔标题的符号，如：|、-、_等', 'nova'),
        'section'     => 'nova_seo',
        'type'        => 'text',
    ));
    
    // 首页标题
    $wp_customize->add_setting('nova_seo_home_title', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_seo_home_title', array(
        'label'       => __('自定义首页标题', 'nova'),
        'description' => __('留空则自动调用「后台-设置-常规」中的"站点标题+副标题"的内容', 'nova'),
        'section'     => 'nova_seo',
        'type'        => 'text',
    ));
    
    // 首页关键词
    $wp_customize->add_setting('nova_seo_home_keywords', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nova_seo_home_keywords', array(
        'label'       => __('首页关键词', 'nova'),
        'description' => __('多个关键词之间用英文逗号隔开，一般不超过100个字符', 'nova'),
        'section'     => 'nova_seo',
        'type'        => 'text',
    ));
    
    // 首页描述
    $wp_customize->add_setting('nova_seo_home_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('nova_seo_home_description', array(
        'label'       => __('首页描述', 'nova'),
        'description' => __('一般不超过200个字符，显示在百度搜索结果页面', 'nova'),
        'section'     => 'nova_seo',
        'type'        => 'textarea',
    ));
}
add_action('customize_register', 'nova_customize_seo_settings');