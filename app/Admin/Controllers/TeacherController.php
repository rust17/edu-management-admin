<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Auth\Database\Administrator;
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
    protected $title = 'Teacher Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // Only show teacher role
        $grid->model()->where('role', 'teacher')->orderBy('id', 'desc');

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
        $show = new Show(User::where('role', 'teacher')->findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', 'Name');
        $show->field('email', 'Email');
        $show->field('created_at', 'Created At');
        $show->field('updated_at', 'Updated At');

        // Show teacher's courses
        $show->teacherCourses('Teaching Courses', function ($courses) {
            $courses->id('Course ID');
            $courses->name('Course Name');
            $courses->year_month('Start Date')->display(function ($yearMonth) {
                return Carbon::parse($yearMonth)->format('Y-m');
            });
            $courses->fee('Course Fee')->display(function ($fee) {
                return "$${fee}";
            });
            $courses->students('Student Count')->display(function ($students) {
                return count($students);
            });
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

        // Admin backend user information
        if ($form->isEditing()) {
            $form->text('adminProfile.username', 'Admin Username')->required();
            $form->password('adminProfile.password', 'Admin Password')->required();
        }

        // Set default role as teacher
        $form->hidden('role')->default('teacher');

        // Before saving callback
        $form->saving(function (Form $form) {
            // Don't modify password if empty
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        $form->saved(function (Form $form) {
            // Create teacher profile when creating new teacher
            if ($form->isCreating()) {
                Teacher::create([
                    'user_id' => $form->model()->id,
                ]);

                $admin = new Administrator();
                $admin->username = $form->model()->email;
                $admin->password = $form->model()->password;
                $admin->name = $form->model()->name;
                $admin->user_id = $form->model()->id;
                $admin->role = 'teacher';
                $admin->save();
            }
        });

        return $form;
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        $request = request();

        if ($request->password && $user->password != $request->password) {
            $request->password = Hash::make($request->password);
        }

        $user->update($request->only(['name', 'email']) + ['password' => $request->password]);

        $adminPassword = $user->adminProfile->password;
        if ($request->adminProfile['password'] &&
            $user->adminProfile->password != $request->adminProfile['password']
        ) {
            $adminPassword = Hash::make($request->adminProfile['password']);
        }

        $user->adminProfile->update([
            'username' => $request->adminProfile['username'],
            'password' => $adminPassword,
        ]);

        admin_success('Saved successfully');
    }
}
