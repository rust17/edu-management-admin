<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TeacherController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '教师管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // 只显示教师角色
        $grid->model()->where('role', 'teacher')->orderBy('id', 'desc');

        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '姓名');
        $grid->column('email', '邮箱');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        // 设置每页显示行数
        $grid->paginate(15);

        // 查询过滤
        $grid->filter(function (Grid\Filter $filter) {
            $filter->like('name', '姓名');
            $filter->like('email', '邮箱');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::where('role', 'teacher')->findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', '姓名');
        $show->field('email', '邮箱');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        // 展示教师的课程信息
        $show->teacherCourses('开设的课程', function ($courses) {
            $courses->id('课程ID');
            $courses->name('课程名称');
            $courses->year_month('开课时间')->display(function ($yearMonth) {
                return Carbon::parse($yearMonth)->format('Y年m月');
            });
            $courses->fee('课程费用')->display(function ($fee) {
                return "￥{$fee}";
            });
            $courses->students('学生数')->display(function ($students) {
                return count($students);
            });

            // 禁用课程列表的增加、删除、编辑按钮
            $courses->disableCreateButton();
            $courses->disableActions();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', '姓名')->required();
        $form->email('email', '邮箱')->required();
        $form->password('password', '密码')
            ->required()
            ->default(function ($form) {
                return $form->isEditing() ? $form->model()->password : '';
            });

        // 设置默认角色为教师
        $form->hidden('role')->default('teacher');

        // 保存前回调
        $form->saving(function (Form $form) {
            // 如果密码为空，则不修改密码
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        $form->saved(function (Form $form) {
            // 当创建教师时，自动创建教师扩展信息
            if ($form->isCreating()) {
                Teacher::create([
                    'user_id' => $form->model()->id,
                ]);
            }
        });

        return $form;
    }
}
