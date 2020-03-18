<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation { show as traitShow; }

    public function setup()
    {
        $this->crud->setModel('App\Models\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
        $this->crud->addButtonFromView('line', 'profile', 'profile', 'beginning');
    }

    protected function show($id)
    {
        $content = $this->traitShow($id);
        $this->crud->removeColumn('password');
        $this->crud->removeColumn('skill');
        $this->crud->removeColumn('remember_token');
        return $content;
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setColumns(['name','email','is_admin','reputation']);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(UserRequest::class);

        $this->crud->addFields([
            'Name'=>'name',
            'Email'=>'email',
            'Password'=>'password',
            'Is Admin'=>'is_admin',
            [
                'name'=>'image',
                'type'=>'hidden'
            ]
        ]);
    }

    public function store() {
        $email = $this->crud->request->request->get('email');
        $hash = md5(strtolower(trim($email)));
        $image = "https://www.gravatar.com/avatar/$hash?d=wavatar";
        $this->crud->request->request->add(['image'=>$image]);

        $password = $this->crud->request->request->get('password');
        $this->crud->request->request->set('password',Hash::make($password));

        $response = $this->traitStore();
        return $response;

    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UserUpdateRequest::class);

        // dd($this->crud->request->request->get('reputation'));
        $this->crud->addFields([
            'Name'=>'name',
            'Is Admin'=>'is_admin',
            'Image' => 'image',
            'Reputation' => 'reputation'
        ]);
    }

    public function update() {
        // TODO: replace this with a validation rule, reputation should have a minimum of zero
        $rep = $this->crud->request->request->get('reputation');
        $rep = $rep > 0 ? $rep : 0;
        $this->crud->request->request->set('reputation',$rep);

        $response = $this->traitUpdate();
        return $response;
    }
}
