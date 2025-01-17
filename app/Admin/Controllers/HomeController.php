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
            ->title('管理后台')
            ->description('数据概览')
            ->row(function (Row $row) {
                // 添加数据统计卡片
                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        '学生总数',
                        'users',
                        'aqua',
                        '/admin/students',
                        Student::count()
                    ));
                });

                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        '教师总数',
                        'user',
                        'green',
                        '/admin/teachers',
                        Teacher::count()
                    ));
                });

                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        '课程总数',
                        'book',
                        'yellow',
                        '/admin/courses',
                        Course::count()
                    ));
                });

                $row->column(3, function (Column $column) {
                    $column->append(new InfoBox(
                        '本月新增学生',
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
                            '系统环境',
                            Dashboard::environment()
                        )
                    );
                });

                $row->column(6, function (Column $column) {
                    $column->append(
                        new Box(
                            '依赖信息',
                            Dashboard::dependencies()
                        )
                    );
                });
            });
    }
}
