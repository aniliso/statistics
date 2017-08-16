<?php

namespace Modules\Statistics\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterStatisticsSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('statistics::dashboards.title.statistics'), function (Item $item) {
                $item->icon('fa fa-bar-chart');
                $item->weight(10);
                $item->route('admin.statistics.dashboard.index');
                $item->authorize(
                    $this->auth->hasAccess('statistics.dashboards.index')
                );
            });
        });

        return $menu;
    }
}
