<?php

namespace App\Service;

class MenuManager
{
    public function getMenuItems(): array
    {
        return [
            new MenuItem('app_dashboard', 'fa fa-home', 'Home'),
            new MenuItem(
                null,
                'fa fa-briefcase',
                'Wuwei',
                true,
                [
                    new MenuItem('app_goal_index', 'fa fa-bullseye', 'Goals'),
                    new MenuItem('app_time_system_index', 'fa fa-lungs', 'Time Systems'),
                    new MenuItem('app_cigarette_log_index', 'fa fa-smoking', 'Cigarette'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_tag_type_index', 'fa fa-folder-tree', 'Tag Types'),
                    new MenuItem('app_tag_index', 'fa fa-tags', 'Tags'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_item_list_index', 'fa fa-tags', 'Item List'),
                ]
            ),
            new MenuItem(
                null,
                'fa fa-briefcase',
                'myApp',
                true,
                [
                    new MenuItem('app_client_index', 'fa fa-user-tie', 'Clients'),
                    new MenuItem('app_accounts_index', 'fa fa-file-invoice', 'Accounts'),
                    new MenuItem('app_task_lists_index', 'fa fa-file-invoice', 'Task Lists'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_tasks_index', 'fa fa-file-invoice', 'Tasks'),
                    new MenuItem('app_time_tracking', 'fa fa-file-invoice', 'Time Tracking'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_transactions_index', 'fa fa-file-invoice', 'Transactions'),
                    new MenuItem('app_contract_index', 'fa fa-file-invoice', 'Contract'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_cost_of_life_index', 'fa fa-file-invoice', 'Cost Of Life'),
                    new MenuItem('app_currency_index', 'fa fa-file-invoice', 'Currency'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_dashboard_task_lists_index', 'fa fa-file-invoice', 'Dashboard Tasklists'),
                    new MenuItem('app_days_index', 'fa fa-file-invoice', 'Days'),
                    new MenuItem('app_holiday_index', 'fa fa-file-invoice', 'Holiday'),
                    new MenuItem('app_notes_index', 'fa fa-file-invoice', 'Notes'),
                    new MenuItem('app_objective_index', 'fa fa-file-invoice', 'Objectives'),
                    new MenuItem('app_rate_index', 'fa fa-file-invoice', 'Rate'),
                    new MenuItem('app_work_log_index', 'fa fa-file-invoice', 'Worklog'),
                ]
            ),
            new MenuItem('app_schedule_index', '', 'Schedule'),
        ];
    }
}
