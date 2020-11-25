<?php

namespace CEKW\WpPluginFramework\Core\View;

class AdminPageView
{
    private int $activeSideTabId = 0;
    private array $sideTabs = [];
    private array $titleLinks = [];

    public function addSideTab(string $tabText, string $tabContent): AdminPageView {
        $this->sideTabs[] = [
            'tabText' => $tabText,
            'tabContent' => $tabContent
        ];
        return $this;
    }

    public function addTitleLink(string $text, string $href, string $target='_self'): AdminPageView {
        $this->titleLinks[] = '<a href="'.$href.'" class="page-title-action" target="'.$target.'">'.$text.'</a>';
        return $this;
    }

    public function setActiveSideTabId(int $tabId): AdminPageView {
        $this->activeSideTabId = $tabId;
        return $this;
    }

    public function __toString(){
        $tabContent = '';
        $titleLinks = '';
        $navTabs = '';

        array_map(function ($link) use (&$titleLinks){
           $titleLinks.=$link;
        },$this->titleLinks);

        foreach ($this->sideTabs as $index=>$tabSetting){
            $navTabs .= '<a href="?page='.$_REQUEST['page'].'&sideTab='.$index.'" class="nav-tab '.($this->activeSideTabId===$index?'nav-tab-active':'').'">'.$tabSetting['tabText'].'</a>';
            if($this->activeSideTabId === $index){
                $tabContent = $tabSetting['tabContent'];
            }
        }

        return sprintf(
            '<div class="wrap">
                <h1 class="wp-heading-inline">%s</h1>
                %s %s
                <div class="tab-content">%s</div>
            </div>',
            esc_html(get_admin_page_title()), $titleLinks, (count($this->sideTabs)>1?'<nav class="nav-tab-wrapper">'.$navTabs.'</nav>':'<h2>Modules</h2>'), $tabContent
        );
    }
}