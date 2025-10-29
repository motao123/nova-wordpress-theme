/**
 * Nova Theme Main JavaScript
 * 主题核心交互功能
 */

(function() {
    'use strict';
    
    // 调试开关
    const DEBUG = window.novaData && window.novaData.debug ? true : false;
    
    // 调试日志函数
    function debugLog(...args) {
        if (DEBUG) {
            console.log(...args);
        }
    }
    
    function debugWarn(...args) {
        if (DEBUG) {
            console.warn(...args);
        }
    }
    
    function debugError(...args) {
        if (DEBUG) {
            console.error(...args);
        }
    }

    /**
     * 移动端菜单切换
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('.header-menu');
        
        if (menuToggle && navigation) {
            // 确保按钮有适当的ARIA属性
            menuToggle.setAttribute('aria-expanded', 'false');
            
            menuToggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                navigation.classList.toggle('active');
                
                // 焦点管理：如果菜单打开，将焦点移到第一个菜单项
                if (!isExpanded) {
                    const firstMenuItem = navigation.querySelector('a');
                    if (firstMenuItem) {
                        firstMenuItem.focus();
                    }
                }
            });
            
            // ESC键关闭菜单
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && navigation.classList.contains('active')) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    navigation.classList.remove('active');
                    menuToggle.focus();
                }
            });
            
            // 移动端子菜单展开/收起
            const submenuToggles = navigation.querySelectorAll('.menu-item-has-children > a');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    // 如果是移动端
                    if (window.innerWidth <= 767) {
                        e.preventDefault();
                        const parentLi = this.parentElement;
                        parentLi.classList.toggle('submenu-open');
                    }
                });
            });
        }
    }

    /**
     * 桌面端菜单hover处理
     */
    function initDesktopMenu() {
        const menuItems = document.querySelectorAll('.header-menu .gore > li');
        
        menuItems.forEach(item => {
            const submenu = item.querySelector('.sub-menu');
            if (!submenu) return;
            
            let hoverTimeout;
            
            // 鼠标进入菜单项
            item.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                submenu.style.opacity = '1';
                submenu.style.visibility = 'visible';
                submenu.style.transform = 'translateY(0)';
            });
            
            // 鼠标离开菜单项
            item.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(() => {
                    submenu.style.opacity = '0';
                    submenu.style.visibility = 'hidden';
                    submenu.style.transform = 'translateY(-10px)';
                }, 150); // 150ms延迟
            });
            
            // 鼠标进入子菜单
            submenu.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                this.style.opacity = '1';
                this.style.visibility = 'visible';
                this.style.transform = 'translateY(0)';
            });
            
            // 鼠标离开子菜单
            submenu.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(() => {
                    this.style.opacity = '0';
                    this.style.visibility = 'hidden';
                    this.style.transform = 'translateY(-10px)';
                }, 150); // 150ms延迟
            });
        });
    }

    /**
     * 搜索功能
     */
    function initSearchToggle() {
        const searchButton = document.querySelector('.goFind');
        const searchBox = document.querySelector('.site-search');
        const closeButton = document.querySelector('.closeFind');
        const searchInput = document.querySelector('.site-search .field');
        
        if (searchButton && searchBox) {
            searchButton.addEventListener('click', function() {
                searchBox.classList.remove('none');
                searchBox.style.display = 'block';
                
                // 聚焦搜索框
                if (searchInput) {
                    setTimeout(() => {
                        searchInput.focus();
                    }, 100);
                }
            });
            
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    searchBox.classList.add('none');
                    searchBox.style.display = 'none';
                    searchButton.focus();
                });
            }
            
            // ESC键关闭搜索
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !searchBox.classList.contains('none')) {
                    searchBox.classList.add('none');
                    searchBox.style.display = 'none';
                    searchButton.focus();
                }
            });
        }
    }

    /**
     * 返回顶部按钮
     */
    function initBackToTop() {
        const backToTop = document.getElementById('back-to-top');
        
        if (backToTop) {
            // 滚动时显示/隐藏按钮
            const toggleBackToTop = function() {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('visible');
                    backToTop.setAttribute('aria-hidden', 'false');
                } else {
                    backToTop.classList.remove('visible');
                    backToTop.setAttribute('aria-hidden', 'true');
                }
            };
            
            // 初始检查
            toggleBackToTop();
            
            window.addEventListener('scroll', toggleBackToTop);
            
            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                
                // 将焦点移到页面顶部
                const skipLink = document.querySelector('.skip-link');
                if (skipLink) {
                    skipLink.focus();
                }
            });
        }
    }


    /**
     * 搜索功能增强
     */
    function initSearch() {
        const searchForms = document.querySelectorAll('.search-form');
        
        searchForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const input = this.querySelector('input[type="search"]');
                if (!input.value.trim()) {
                    e.preventDefault();
                    input.focus();
                }
            });
        });
    }

    /**
     * 平滑滚动
     */
    function initSmoothScroll() {
        // 使用原生平滑滚动
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        
                        // 更新焦点
                        target.setAttribute('tabindex', '-1');
                        target.focus();
                    }
                }
            });
        });
    }

    /**
     * 文章阅读进度条
     */
    function initReadingProgress() {
        // 仅在文章页面显示进度条
        if (!document.querySelector('.single-post, .single-page')) {
            return;
        }
        
        const content = document.querySelector('.entry-content');
        if (!content) return;
        
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.innerHTML = '<div class="reading-progress-bar"></div>';
        progressBar.setAttribute('role', 'progressbar');
        progressBar.setAttribute('aria-label', '阅读进度');
        document.body.appendChild(progressBar);
        
        const progressBarInner = progressBar.querySelector('.reading-progress-bar');
        
        let ticking = false;
        
        const updateProgress = function() {
            const scrollTop = window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            
            progressBarInner.style.width = scrollPercent + '%';
            progressBar.setAttribute('aria-valuenow', Math.round(scrollPercent));
            
            ticking = false;
        };
        
        const requestTick = function() {
            if (!ticking) {
                requestAnimationFrame(updateProgress);
                ticking = true;
            }
        };
        
        window.addEventListener('scroll', requestTick);
    }

    /**
     * 初始化代码高亮
     */
    function initCodeHighlight() {
        // 检查highlight.js是否已加载
        if (typeof hljs === 'undefined') {
            debugWarn('Highlight.js 未加载');
            return;
        }
        
        // 配置highlight.js
        hljs.configure({
            tabReplace: '    ', // 将tab替换为4个空格
            classPrefix: 'hljs-',
            languages: ['javascript', 'php', 'css', 'html', 'json', 'python', 'java', 'bash', 'sql', 'xml']
        });
        
        // 高亮所有pre code块
        document.querySelectorAll('pre code').forEach(block => {
            hljs.highlightElement(block);
        });
        
        // 初始化行号
        if (typeof hljsLineNumbersBlock !== 'undefined') {
            document.querySelectorAll('pre code').forEach(block => {
                hljsLineNumbersBlock(block, {
                    singleLine: false
                });
            });
        }
    }

    /**
     * 复制代码块
     */
    function initCodeBlockCopy() {
        document.querySelectorAll('pre code').forEach(codeBlock => {
            // 只对包含内容的代码块添加复制按钮
            if (!codeBlock.textContent.trim()) return;
            
            // 检查是否已经有包装器
            if (codeBlock.parentElement.classList.contains('code-block-wrapper')) {
                return;
            }
            
            const wrapper = document.createElement('div');
            wrapper.className = 'code-block-wrapper';
            
            const button = document.createElement('button');
            button.className = 'copy-code-button';
            button.textContent = '复制';
            button.setAttribute('aria-label', '复制代码');
            
            button.addEventListener('click', function() {
                const text = codeBlock.textContent;
                
                // 优先使用现代Clipboard API
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(() => {
                        const originalText = button.textContent;
                        button.textContent = '已复制';
                        button.setAttribute('aria-label', '已复制代码');
                        
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.setAttribute('aria-label', '复制代码');
                        }, 2000);
                    }).catch(err => {
                        debugError('复制失败: ', err);
                        // 降级到execCommand
                        copyFallback(text, button);
                    });
                } else {
                    // 降级方案
                    copyFallback(text, button);
                }
            });
            
            // 降级复制函数
            function copyFallback(text, button) {
                try {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textArea);
                    
                    if (successful) {
                        const originalText = button.textContent;
                        button.textContent = '已复制';
                        button.setAttribute('aria-label', '已复制代码');
                        
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.setAttribute('aria-label', '复制代码');
                        }, 2000);
                    } else {
                        button.textContent = '复制失败';
                        debugError('execCommand复制失败');
                    }
                } catch (err) {
                    button.textContent = '复制失败';
                    debugError('复制失败:', err);
                    alert('无法复制到剪贴板，请手动复制代码');
                }
            }
            
            codeBlock.parentNode.insertBefore(wrapper, codeBlock);
            wrapper.appendChild(codeBlock);
            wrapper.appendChild(button);
        });
    }

    /**
     * 暗色模式切换
     */
    function initDarkMode() {
        // 获取主题设置，默认为auto
        const theme = localStorage.getItem('theme') || 'auto';
        
        // 应用主题
        applyTheme(theme);
        
        // 创建主题切换器
        createThemeSwitcher(theme);
    }
    
    function applyTheme(theme) {
        const html = document.documentElement;
        
        // 清除所有主题类
        html.classList.remove('dark', 'light', 'auto');
        
        if (theme === 'dark') {
            html.classList.add('dark');
        } else if (theme === 'light') {
            html.classList.add('light');
        } else {
            // auto模式：根据系统设置
            html.classList.add('auto');
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.classList.add('dark');
            }
        }
    }
    
    function createThemeSwitcher(currentTheme) {
        const html = `
            <div class="cThemeSwitcher">
                <span class="${currentTheme === 'dark' ? 'is-active' : ''}" data-theme="dark" title="暗色模式">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                    </svg>
                </span>
                <span class="${currentTheme === 'light' ? 'is-active' : ''}" data-theme="light" title="亮色模式">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <path d="M12 1v2"></path>
                        <path d="M12 21v2"></path>
                        <path d="M4.22 4.22l1.42 1.42"></path>
                        <path d="M18.36 18.36l1.42 1.42"></path>
                        <path d="M1 12h2"></path>
                        <path d="M21 12h2"></path>
                        <path d="M4.22 19.78l1.42-1.42"></path>
                        <path d="M18.36 5.64l1.42-1.42"></path>
                    </svg>
                </span>
                <span class="${currentTheme === 'auto' ? 'is-active' : ''}" data-theme="auto" title="跟随系统">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M8 21h8"></path>
                        <path d="M12 17v4"></path>
                    </svg>
                </span>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', html);
        
        // 绑定点击事件
        document.querySelectorAll('.cThemeSwitcher span').forEach(item => {
            item.addEventListener('click', function() {
                if (this.classList.contains('is-active')) return;
                
                // 移除所有active状态
                document.querySelectorAll('.cThemeSwitcher span').forEach(span => {
                    span.classList.remove('is-active');
                });
                
                // 添加active状态
                this.classList.add('is-active');
                
                // 获取主题值
                const theme = this.dataset.theme;
                
                // 保存到localStorage
                localStorage.setItem('theme', theme);
                
                // 应用主题
                applyTheme(theme);
            });
        });
        
        // 监听系统主题变化（仅在auto模式下）
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                const currentTheme = localStorage.getItem('theme') || 'auto';
                if (currentTheme === 'auto') {
                    applyTheme('auto');
                }
            });
        }
    }

    /**
     * 文章阅读量统计（优化版）
     */
    function initPostViews() {
        // 检查novaData是否可用
        if (typeof novaData === 'undefined') {
            debugWarn('novaData未定义，阅读量统计功能无法使用');
            return;
        }
        
        // 查找文章元素 - 支持多种可能的情况
        const article = document.querySelector('article[class*="post-"], article.post, article');
        if (!article) {
            debugWarn('未找到文章元素，body class:', document.body.className);
            return;
        }
        
        // 获取文章ID - 支持多种格式
        let currentPostId = null;
        
        // 方法1: 从article的id属性获取
        const idMatch = article.id.match(/post-(\d+)/);
        if (idMatch) {
            currentPostId = idMatch[1];
        }
        
        // 方法2: 从data属性获取
        if (!currentPostId && article.dataset.postId) {
            currentPostId = article.dataset.postId;
        }
        
        // 方法3: 从URL获取
        if (!currentPostId) {
            const urlMatch = window.location.pathname.match(/\/(\d+)\//);
            if (urlMatch) {
                currentPostId = urlMatch[1];
            }
        }
        
        if (!currentPostId) {
            debugWarn('无法从页面中提取文章ID，article id:', article.id);
            return;
        }
        
        const storageKey = 'nova_viewed_' + currentPostId;
        
        // 检查今天是否已统计
        const today = new Date().toDateString();
        const viewed = localStorage.getItem(storageKey);
        
        debugLog('阅读量统计检查:', {
            postId: currentPostId,
            today: today,
            viewed: viewed,
            shouldUpdate: viewed !== today
        });
        
        if (viewed !== today) {
            // 使用AbortController防止并发请求
            const controller = new AbortController();
            
            // 发送AJAX请求更新阅读量
            const requestBody = 'action=nova_update_views&post_id=' + currentPostId + '&nonce=' + novaData.nonce;
            
            debugLog('发送阅读量更新请求:', {
                url: novaData.ajaxUrl,
                body: requestBody
            });
            
            fetch(novaData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: requestBody,
                signal: controller.signal
            })
            .then(response => {
                debugLog('收到响应:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP错误: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                debugLog('解析后的数据:', data);
                if (data.success && data.data && typeof data.data.views !== 'undefined') {
                    // 只有在确认成功后才设置localStorage
                    localStorage.setItem(storageKey, today);
                    
                    // 更新所有阅读量显示元素
                    const viewsElements = document.querySelectorAll('.views-count');
                    const newViews = data.data.views;
                    
                    debugLog('更新阅读量显示:', {
                        elements: viewsElements.length,
                        newViews: newViews
                    });
                    
                    viewsElements.forEach(el => {
                        // 保存原始HTML结构
                        const hasIcon = el.querySelector('svg');
                        if (hasIcon) {
                            // 如果有SVG图标，只更新数字部分
                            const textNodes = Array.from(el.childNodes).filter(node => node.nodeType === 3);
                            textNodes.forEach(node => {
                                const match = node.textContent.match(/(\d+)/);
                                if (match) {
                                    node.textContent = node.textContent.replace(/\d+/, newViews);
                                }
                            });
                        } else {
                            // 没有图标，直接替换整个文本
                            const text = el.textContent.trim();
                            const viewsMatch = text.match(/(\d+)/);
                            if (viewsMatch) {
                                el.textContent = text.replace(/\d+/, newViews);
                            }
                        }
                    });
                    
                    debugLog('阅读量更新成功:', newViews);
                } else {
                    debugError('阅读量更新失败，数据格式错误:', data);
                    // 不设置localStorage，允许重试
                }
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    debugError('更新阅读量失败:', error);
                    // 不设置localStorage，允许重试
                }
            });
        } else {
            debugLog('今天已统计过阅读量，使用缓存');
        }
    }

    /**
     * 文章点赞功能
     */
    function initPostLike() {
        document.querySelectorAll('.entry-like-button').forEach(button => {
            button.addEventListener('click', function() {
                // 防止重复点击
                if (this.classList.contains('liked')) {
                    return;
                }
                
                const postId = this.dataset.postId;
                const likeCountEl = this.querySelector('.like-count');
                
                // 立即更新UI反馈
                this.classList.add('liked');
                const currentCount = parseInt(likeCountEl.textContent) || 0;
                likeCountEl.textContent = currentCount + 1;
                
                // 发送AJAX请求
                fetch(novaData.ajaxUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=nova_post_like&post_id=' + postId + '&nonce=' + novaData.nonce
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        debugLog('点赞成功:', data.data.likes);
                        // 数字已经更新，这里可以添加成功动画
                        this.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1)';
                        }, 200);
                    } else {
                        // 恢复UI
                        this.classList.remove('liked');
                        likeCountEl.textContent = currentCount;
                        debugError('点赞失败:', data);
                    }
                })
                .catch(error => {
                    // 恢复UI
                    this.classList.remove('liked');
                    likeCountEl.textContent = currentCount;
                    debugError('点赞请求失败:', error);
                });
            });
        });
    }

    /**
     * 表单增强功能
     */
    function initFormEnhancements() {
        // 为所有表单添加验证
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.setAttribute('aria-invalid', 'true');
                        field.classList.add('error');
                    } else {
                        field.setAttribute('aria-invalid', 'false');
                        field.classList.remove('error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    const firstError = this.querySelector('[aria-invalid="true"]');
                    if (firstError) {
                        firstError.focus();
                    }
                }
            });
        });
        
        // 为表单字段添加实时验证
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.setAttribute('aria-invalid', 'true');
                    this.classList.add('error');
                } else {
                    this.setAttribute('aria-invalid', 'false');
                    this.classList.remove('error');
                }
            });
        });
    }

    /**
     * 图片点击放大功能
     */
    function initImageZoom() {
        // 仅在有文章内容的页面启用
        const entryContent = document.querySelector('.entry-content');
        if (!entryContent) {
            debugLog('Image zoom: 未找到 .entry-content');
            return;
        }
        
        // 查找所有图片，包括链接内的图片
        const images = entryContent.querySelectorAll('img');
        debugLog('Image zoom: 找到', images.length, '张图片');
        
        if (images.length === 0) return;
        
        // 阻止链接内的图片点击事件冒泡
        entryContent.querySelectorAll('a img').forEach(img => {
            const link = img.closest('a');
            if (link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            }
        });
        
        // 创建图片查看器
        const viewer = document.createElement('div');
        viewer.className = 'image-viewer';
        viewer.innerHTML = `
            <div class="image-viewer-backdrop"></div>
            <div class="image-viewer-content">
                <button class="image-viewer-close" aria-label="关闭">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"></path>
                    </svg>
                </button>
                <img class="image-viewer-img" src="" alt="">
            </div>
        `;
        document.body.appendChild(viewer);
        
        const backdrop = viewer.querySelector('.image-viewer-backdrop');
        const closeBtn = viewer.querySelector('.image-viewer-close');
        const img = viewer.querySelector('.image-viewer-img');
        
        // 点击图片放大
        images.forEach((image, index) => {
            image.style.cursor = 'zoom-in';
            
            // 获取图片的真实URL
            function getImageUrl(imgElement) {
                let url = '';
                
                // 优先使用data-src（如果有的话）
                if (imgElement.dataset.src) {
                    url = imgElement.dataset.src;
                }
                // 使用srcset中最大的图片
                else if (imgElement.srcset) {
                    const srcset = imgElement.srcset.split(',');
                    const largest = srcset[srcset.length - 1].trim().split(' ')[0];
                    url = largest;
                }
                // 使用currentSrc（浏览器自动选择的最佳图片）
                else if (imgElement.currentSrc) {
                    url = imgElement.currentSrc;
                }
                // 最后使用src
                else if (imgElement.src) {
                    url = imgElement.src;
                }
                
                // 如果是完整的URL，直接返回
                if (url.startsWith('http://') || url.startsWith('https://')) {
                    return url;
                }
                
                // 如果是相对路径，转换为绝对路径
                if (url.startsWith('/')) {
                    return window.location.origin + url;
                }
                
                // 如果有baseURL
                if (url.startsWith('./') || url.startsWith('../')) {
                    return new URL(url, window.location.href).href;
                }
                
                // 其他情况
                return url;
            }
            
            image.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const imageUrl = getImageUrl(this);
                debugLog('Image zoom: 原始图片信息', {
                    src: this.src,
                    currentSrc: this.currentSrc,
                    srcset: this.srcset,
                    datasetSrc: this.dataset.src,
                    finalUrl: imageUrl
                });
                
                if (!imageUrl || imageUrl.includes('undefined')) {
                    debugError('Image zoom: 无法获取有效的图片URL');
                    return;
                }
                
                img.src = imageUrl;
                img.alt = this.alt || '';
                viewer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
        
        // 关闭查看器
        function closeViewer() {
            viewer.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        backdrop.addEventListener('click', closeViewer);
        closeBtn.addEventListener('click', closeViewer);
        
        // ESC键关闭
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && viewer.classList.contains('active')) {
                closeViewer();
            }
        });
    }

    /**
     * 文章目录TOC生成
     */
    function initTOC() {
        const entryContent = document.querySelector('.entry-content');
        if (!entryContent || !entryContent.querySelector('h2, h3, h4')) return;
        
        const headings = entryContent.querySelectorAll('h2, h3, h4');
        if (headings.length < 2) return; // 至少2个标题才显示目录
        
        // 创建TOC容器
        const tocContainer = document.createElement('details');
        tocContainer.className = 'article-toc';
        tocContainer.innerHTML = `
            <summary>目录</summary>
            <nav class="toc-nav"></nav>
        `;
        
        const tocNav = tocContainer.querySelector('.toc-nav');
        const tocList = document.createElement('ul');
        tocList.className = 'toc-list';
        
        let tocHTML = '';
        let currentLevel = 0;
        let tocCount = 0;
        
        headings.forEach((heading, index) => {
            // 获取标题级别
            const level = parseInt(heading.tagName.substring(1));
            const title = heading.textContent.trim();
            
            // 如果没有ID，添加一个
            if (!heading.id) {
                tocCount++;
                heading.id = 'toc-' + tocCount;
            }
            
            // 构建目录HTML
            if (level > currentLevel) {
                // 进入更深层级
                tocHTML += '<ul class="toc-list dept-' + level + '">';
            } else if (level < currentLevel) {
                // 返回上一层
                tocHTML += '</li></ul>'.repeat(currentLevel - level);
            } else if (index > 0) {
                // 同级
                tocHTML += '</li>';
            }
            
            tocHTML += '<li class="toc-item level-' + level + '">';
            tocHTML += '<a href="#' + heading.id + '" class="toc-link">' + title + '</a>';
            
            currentLevel = level;
        });
        
        // 闭合所有未闭合的标签
        if (currentLevel > 0) {
            tocHTML += '</li>' + '</ul>'.repeat(currentLevel - 1);
        }
        
        tocList.innerHTML = tocHTML;
        tocNav.appendChild(tocList);
        
        // 插入到文章内容之前
        entryContent.insertBefore(tocContainer, entryContent.firstChild);
        
        // 添加平滑滚动
        tocNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                    
                    // 高亮当前标题
                    headings.forEach(h => h.classList.remove('toc-active'));
                    target.classList.add('toc-active');
                }
            });
        });
        
        // 滚动时高亮当前标题
        let ticking = false;
        function updateActiveHeading() {
            const scrollTop = window.pageYOffset;
            const headerOffset = 100;
            
            let currentHeading = null;
            headings.forEach(heading => {
                const headingTop = heading.getBoundingClientRect().top + scrollTop;
                if (scrollTop + headerOffset >= headingTop) {
                    currentHeading = heading;
                }
            });
            
            // 更新高亮
            tocNav.querySelectorAll('.toc-link').forEach(link => {
                link.classList.remove('active');
            });
            
            if (currentHeading) {
                const activeLink = tocNav.querySelector('a[href="#' + currentHeading.id + '"]');
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
            
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateActiveHeading);
                ticking = true;
            }
        });
    }

    /**
     * 二维码分享功能
     */
    function initQRCodeShare() {
        // 检查是否在单篇文章页面
        if (!document.body.classList.contains('single')) return;
        
        // 检查QRCode库是否已加载
        if (typeof QRCode === 'undefined') {
            debugWarn('QRCode库未加载');
            return;
        }
        
        // 查找点赞按钮作为插入点
        const likeWrapper = document.querySelector('.entry-like-wrapper');
        if (!likeWrapper) return;
        
        // 创建分享按钮
        const shareButton = document.createElement('button');
        shareButton.className = 'entry-share-button';
        shareButton.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                <polyline points="16 6 12 2 8 6"></polyline>
                <line x1="12" y1="2" x2="12" y2="15"></line>
            </svg>
            <span>分享</span>
        `;
        shareButton.setAttribute('aria-label', '分享文章');
        
        // 创建二维码模态框
        const modal = document.createElement('div');
        modal.className = 'qr-share-modal';
        modal.innerHTML = `
            <div class="qr-modal-backdrop"></div>
            <div class="qr-modal-content">
                <button class="qr-modal-close" aria-label="关闭">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"></path>
                    </svg>
                </button>
                <h3>扫码分享</h3>
                <div class="qr-code-container"></div>
                <p class="qr-tip">使用手机扫描二维码分享文章</p>
            </div>
        `;
        document.body.appendChild(modal);
        
        const backdrop = modal.querySelector('.qr-modal-backdrop');
        const closeBtn = modal.querySelector('.qr-modal-close');
        const qrContainer = modal.querySelector('.qr-code-container');
        
        // 点击分享按钮
        shareButton.addEventListener('click', function() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // 生成二维码
            qrContainer.innerHTML = ''; // 清空旧二维码
            const currentUrl = window.location.href;
            new QRCode(qrContainer, {
                text: currentUrl,
                width: 200,
                height: 200,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        });
        
        // 关闭模态框
        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        backdrop.addEventListener('click', closeModal);
        closeBtn.addEventListener('click', closeModal);
        
        // ESC键关闭
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
        
        // 插入到页面
        likeWrapper.appendChild(shareButton);
    }

    /**
     * 初始化所有功能
     */
    function init() {
        initMobileMenu();
        initDesktopMenu();
        initSearchToggle();
        initBackToTop();
        initSearch();
        initSmoothScroll();
        initReadingProgress();
        initCodeHighlight();
        initCodeBlockCopy();
        initFormEnhancements();
        initDarkMode();
        initPostViews();
        initPostLike();
        initImageZoom();
        initTOC();
        initQRCodeShare();
    }

    // 页面加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // 页面可见性变化时重新检查某些功能
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // 页面重新可见时重新初始化某些功能
        }
    });

    /**
     * 阅读进度条
     */
    function initReadingProgress() {
        // 检查是否启用了阅读进度功能
        if (novaData && !novaData.enableReadingProgress) {
            debugLog('阅读进度功能已禁用');
            return;
        }
        
        const article = document.querySelector('.entry-content');
        if (!article) return;
        
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            progressBar.style.width = scrollPercent + '%';
        });
    }

    // 初始化阅读进度条
    initReadingProgress();


})();