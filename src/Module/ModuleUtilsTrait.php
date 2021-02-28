<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\Admin\AbstractSubmenuPage;
use CEKW\WpPluginFramework\Core\Admin\MenuPageInterface;
use CEKW\WpPluginFramework\Core\RenderTemplateTrait;
use ReflectionClass;
use ReflectionMethod;
use WP_Screen;

trait ModuleUtilsTrait
{
    use RenderTemplateTrait;

    /**
     * @param string $template - Relative to the template dir.
     */
    public function addHelpTab(string $screenId, string $title, string $template): AbstractModule
    {
        $this->addAction('in_admin_header', function () use ($screenId, $title, $template) {
            $screen = get_current_screen();
            if (!$screen instanceof WP_Screen) {
                return;
            }

            if ($screenId !== $screen->id) {
                return;
            }

            $screen->add_help_tab([
                'id' => md5($title),
                'title' => $title,
                'content' => $this->renderTemplate($this->templateDirPath . $template)
            ]);
        });

        return $this;
    }

    /**
     * @param string $template - Relative to the template dir.
     * @param string|int|null $metaKey - Add meta key for sorting.
     */
    public function addListTableColumn(string $type, string $label, string $template, $metaKey = null, int $priority = 0): AbstractModule
    {
        if (post_type_exists($type)) {
            $entity = 'postType';
            $filterName = "manage_{$entity}_posts_columns";
            $actionName = "manage_{$entity}_posts_custom_column";
            $sortableFilter = "manage_edit-{$entity}_sortable_columns";
            $preGetAction = 'pre_get_posts';
        } elseif (taxonomy_exists($type)) {
            $entity = 'taxonomy';
            $filterName = "manage_edit-{$entity}_posts_columns";
            $actionName = "manage_{$entity}_custom_column";
        } else {
            $entity = 'users';
            $filterName = 'manage_users_columns';
            $actionName = 'manage_users_custom_column';
            $sortableFilter = 'manage_users_sortable_columns';
            $preGetAction = 'pre_get_users';
        }

        $key = sanitize_title($label);
        $newColumn = [$key => $label];
        $templatePath = $this->templateDirPath . $template;

        $this->addFilter(
            $filterName,
            fn($columns) => $priority < 0 ? array_merge($newColumn, $columns) : array_merge($columns, $newColumn),
            $priority
        );

        if ($entity === 'postType') {
            $this->addAction(
                $actionName,
                fn($columnName, $id) => $columnName === $key ? $this->renderTemplate($templatePath, compact('id')) : '',
                $priority,
                2
            );
        } else {
            $this->addAction(
                $actionName,
                fn($output, $columnName, $id) => $columnName === $key ? $this->renderTemplate($templatePath, compact('id')) : $output,
                $priority,
                3
            );
        }

        if (!empty($metaKey) && $entity !== 'taxonomy') {
            $this->addFilter($sortableFilter, fn($columns) => array_merge($columns, [$key => $metaKey]));
            $this->addAction($preGetAction, function ($query) use ($key, $metaKey) {
                if ($key !== $query->get('oderby')) {
                    return;
                }

                $query->set('meta_key', $metaKey);

                if (is_numeric($metaKey)) {
                    $query->set('orderby', 'meta_value_num');
                }
            });
        }

        return $this;
    }

    public function addMenuPage(MenuPageInterface $menuPage): AbstractModule
    {
        $addMenuAction = $menuPage instanceof AbstractSubmenuPage ? 'add_submenu_page' : 'add_menu_page';
        $menuPage->setTemplateDirPath($this->templateDirPath);
        $this->addAction('admin_menu', fn() => call_user_func_array($addMenuAction, $menuPage->getArgs()));

        $menuPageReflection = new ReflectionClass($menuPage);
        $menuPageMethods = $menuPageReflection->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($menuPageMethods as $method) {
            if ($method->class !== $menuPageReflection->getName()) {
                continue;
            }

            $actionName = '';
            if (substr($method->name, -6, 6) === 'Action' && substr($method->name, -10, 10) !== 'AjaxAction') {
                $actionName = 'admin_post_' . preg_replace('/Action$/', '', $method->name);
            }

            if (substr($method->name, -10, 10) === 'AjaxAction') {
                $actionName = 'wp_ajax_' . preg_replace('/AjaxAction$/', '', $method->name);
            }

            if (empty($actionName)) {
                continue;
            }

            $this->addAction($actionName, function () use ($menuPage, $method) {
                $args = [];
                foreach ($_REQUEST as $key => $value) {
                    $args[':' . $key] = $value;
                }

                $this->injector->execute([$menuPage, $method->name], $args);
            });
        }

        return $this;
    }
}