<?php

namespace App\Service;

class MenuManager
{
    public function getMenuItems(): array
    {
        return [
            new MenuItem('app_dashboard', 'bi bi-house', 'Home'),
            new MenuItem('app_tasks_system', 'bi bi-card-checklist', 'Tasks'),
            new MenuItem('app_finance', 'bi bi-bank', 'Finance'),
            new MenuItem(
                null,
                'bi bi-briefcase',
                'Wuwei',
                true,
                [
                    new MenuItem('app_goal_index', 'bi bi-bullseye', 'Goals'),
                    new MenuItem('app_time_system_index', 'bi bi-clock', 'Time Systems'),
                    new MenuItem('app_cigarette_log_index', 'bi bi-smoke', 'Cigarette'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_tag_type_index', 'bi bi-folder', 'Tag Types'),
                    new MenuItem('app_tag_index', 'bi bi-tags', 'Tags'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_item_list_index', 'bi bi-list', 'Item List'),
                    new MenuItem('app_item_index', 'bi bi-view-list', 'Item'),
                ]
            ),
            new MenuItem(
                null,
                'bi bi-briefcase',
                'myApp',
                true,
                [
                    new MenuItem('app_client_index', 'bi bi-person', 'Clients'),
                    new MenuItem('app_accounts_index', 'bi bi-file-earmark-text', 'Accounts'),
                    new MenuItem('app_task_lists_index', 'bi bi-list-task', 'Task Lists'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_tasks_index', 'bi bi-card-checklist', 'Tasks'),
                    new MenuItem('app_time_tracking', 'bi bi-clock-history', 'Time Tracking'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_transactions_index', 'bi bi-receipt', 'Transactions'),
                    new MenuItem('app_contract_index', 'bi bi-file-earmark', 'Contract'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_cost_of_life_index', 'bi bi-currency-exchange', 'Cost Of Life'),
                    new MenuItem('app_currency_index', 'bi bi-currency-dollar', 'Currency'),
                    new MenuItem(null, '', '', false, [], true), // Divider
                    new MenuItem('app_dashboard_task_lists_index', 'bi bi-list-check', 'Dashboard Tasklists'),
                    new MenuItem('app_days_index', 'bi bi-calendar-day', 'Days'),
                    new MenuItem('app_holiday_index', 'bi bi-calendar-event', 'Holiday'),
                    new MenuItem('app_notes_index', 'bi bi-journal-text', 'Notes'),
                    new MenuItem('app_objective_index', 'bi bi-flag', 'Objectives'),
                    new MenuItem('app_rate_index', 'bi bi-graph-up', 'Rate'),
                    new MenuItem('app_work_log_index', 'bi bi-journal', 'Worklog'),
                ]
            ),
            new MenuItem('app_schedule_index', 'bi bi-calendar', 'Schedule'),
            new MenuItem('app_roadmap_index', 'bi bi-map', 'Roadmap'),
        ];
    }
}
