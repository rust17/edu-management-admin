<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Student;
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
    protected $title = 'Student Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // Only show student role
        $grid->model()->where('role', 'student')->orderBy('id', 'desc');

        $grid->column('id', 'ID')->sortable();
        $grid->column('name', 'Name');
        $grid->column('email', 'Email');
        $grid->column('created_at', 'Created At');
        $grid->column('updated_at', 'Updated At');

        // Set number of items per page
        $grid->paginate(15);

        // Query filters
        $grid->filter(function (Grid\Filter $filter) {
            $filter->like('name', 'Name');
            $filter->like('email', 'Email');
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
        $show->field('name', 'Name');
        $show->field('email', 'Email');
        $show->field('created_at', 'Created At');
        $show->field('updated_at', 'Updated At');

        // Show student's enrolled courses
        $show->studentCourses('Enrolled Courses', function ($courses) {
            $courses->id('Course ID');
            $courses->name('Course Name');
            $courses->year_month('Start Date')->display(function ($yearMonth) {
                return Carbon::parse($yearMonth)->format('Y-m');
            });
            $courses->fee('Course Fee')->display(function ($fee) {
                return "$${fee}";
            });
            $courses->teacher()->name('Teacher');

            // Disable create, delete and edit buttons for course list
            $courses->disableCreateButton();
            $courses->disableActions();
        });

        // Show student's invoice information
        $show->invoices('Invoice Information', function ($invoices) {
            $invoices->id('Invoice ID');
            $invoices->course()->name('Course Name');
            $invoices->amount('Amount')->display(function ($amount) {
                return "$${amount}";
            });
            $invoices->status('Status')->display(function ($status) {
                return [
                    Invoice::STATUS_PENDING => 'Pending',
                    Invoice::STATUS_PAID => 'Paid',
                    Invoice::STATUS_FAILED => 'Failed'
                ][$status] ?? 'Unknown';
            });
            $invoices->created_at('Created At');

            // Disable create, delete and edit buttons for invoice list
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

        $form->text('name', 'Name')->required();
        $form->email('email', 'Email')->required();
        $form->password('password', 'Password')
            ->required()
            ->default(function ($form) {
                return $form->isEditing() ? $form->model()->password : '';
            });

        // Set default role as student
        $form->hidden('role')->default('student');

        // Before saving callback
        $form->saving(function (Form $form) {
            // Don't modify password if empty
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        $form->saved(function (Form $form) {
            // Create student profile when creating new student
            if ($form->isCreating()) {
                Student::create([
                    'user_id' => $form->model()->id,
                ]);
            }
        });

        return $form;
    }
}
