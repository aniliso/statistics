<?php

namespace Modules\Statistics\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\User\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

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
