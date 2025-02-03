<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Admin Dashboard')
            ->description('Overview')
            ->row(function (Row $row) {
                // Add statistics cards
                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        'Total Students',
                        'users',
                        'aqua',
                        '/admin/students',
                        Student::count()
                    ));
                });

                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        'Total Teachers',
                        'user',
                        'green',
                        '/admin/teachers',
                        Teacher::count()
                    ));
                });

                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        'Total Courses',
                        'book',
                        'yellow',
                        '/admin/courses',
                        Course::count()
                    ));
                });

                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        'New Students This Month',
                        'user-plus',
                        'red',
                        '/admin/students',
                        Student::whereMonth('created_at', date('m'))->count()
                    ));
                });
            })
            ->row(function (Row $row) {
                if (!auth()->user()->role === 'admin') {
                    return $row;
                }

                // 添加系统信息
                $row->column(6, function (Column $column) {
                    $column->append(
                        new Box(
                            'System Environment',
                            Dashboard::environment()
                        )
                    );
                });

                $row->column(6, function (Column $column) {
                    $column->append(
                        new Box(
                            'Dependencies',
                            Dashboard::dependencies()
                        )
                    );
                });
            });
    }
}
