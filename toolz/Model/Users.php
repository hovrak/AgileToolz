<?php
namespace toolz\Model;

use atk4\data\Model;
use atk4\data\ValidationException;
use atk4\login\Field\Password;

# Features of Users model
use atk4\login\Feature\SetupModel;
use atk4\login\Feature\PasswordManagement;
use atk4\login\Feature\Signup;
use atk4\login\Feature\UniqueFieldValue;

/**
 * Example users data model.
 */
class Users extends Model
{
    use SetupModel;
    use PasswordManagement;
    use Signup;
    use UniqueFieldValue;

    public $table = 'login_user';
    public $caption = 'Users';

    public function init()
    {
        parent::init();

        $this->addField('name');
        $this->addField('email');
        $this->addField('password', [Password::class]);

        // currently user can have only one role. In future it should be n:n relation
        $this->hasOne('role_id', [Role::class, 'our_field'=>'role_id', 'their_field'=>'id', 'caption'=>'Role'])->withTitle();

        // traits
        $this->setupUserModel();
        $this->initSignup();
        $this->initPasswordManagement();
    }
}
