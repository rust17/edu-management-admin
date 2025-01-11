<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Invoice;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class StudentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '学生管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // 只显示学生角色
        $grid->model()->where('role', 'student')->orderBy('id', 'desc');

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
        $show = new Show(User::where('role', 'student')->findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', '姓名');
        $show->field('email', '邮箱');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        // 展示学生参加的课程信息
        $show->studentCourses('参加的课程', function ($courses) {
            $courses->id('课程ID');
            $courses->name('课程名称');
            $courses->year_month('开课时间')->display(function ($yearMonth) {
                return Carbon::parse($yearMonth)->format('Y年m月');
            });
            $courses->fee('课程费用')->display(function ($fee) {
                return "￥{$fee}";
            });
            $courses->teacher()->name('授课教师');

            // 禁用课程列表的增加、删除、编辑按钮
            $courses->disableCreateButton();
            $courses->disableActions();
        });

        // 展示学生的账单信息
        $show->invoices('账单信息', function ($invoices) {
            $invoices->id('账单ID');
            $invoices->course()->name('课程名称');
            $invoices->amount('金额')->display(function ($amount) {
                return "￥{$amount}";
            });
            $invoices->status('状态')->display(function ($status) {
                return [
                    Invoice::STATUS_PENDING => '待支付',
                    Invoice::STATUS_PAID => '已支付',
                    Invoice::STATUS_FAILED => '支付失败'
                ][$status] ?? '未知';
            });
            $invoices->created_at('创建时间');

            // 禁用账单列表的增加、删除、编辑按钮
            $invoices->disableCreateButton();
            $invoices->disableActions();
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

        // 设置默认角色为学生
        $form->hidden('role')->default('student');

        // 保存前回调
        $form->saving(function (Form $form) {
            // 如果密码为空，则不修改密码
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}
