<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation;

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $rank;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator-> add(
            "username",
        new Uniqueness(
            [
                "field" => "username",
                "message" => "This username is already in use"
            ]
        )
        );

        $validator-> add(
            "username",
            new PresenceOf(
                [
                    "field" => "username",
                    "message" => "Please fill in a username"
                ]
            )
        );

        $validator-> add(
            "email",
            new Uniqueness(
                [
                    "field" => "email",
                    "message" => "This email is already in use"
                ]
            )
        );

        $validator-> add(
            "email",
            new PresenceOf(
                [
                    "field" => "email",
                    "message" => "Please fill in an email"
                ]
            )
        );


        return($this->validate($validator));

    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setup(
          ['notNullValidations' => false]
        );

        $this->hasMany('id', 'Characters', 'users_id', ['alias' => 'Characters']);
        $this->hasMany('id', 'News', 'users_id', ['alias' => 'News']);
        $this->hasMany('id', 'UsersHasEvents', 'users_id', ['alias' => 'UsersHasEvents']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
