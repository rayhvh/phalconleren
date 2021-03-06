<?php

use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation;

class Characters extends \Phalcon\Mvc\Model

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
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $class;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $spec;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $main;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $users_id;

    /**
     * Initialize method for model.
     */

    public function validation()
    {
        $validator = new Validation();

        $validator-> add(
            "name",
            new Uniqueness(
                [
                    "field" => "name",
                    "message" => "This character name already belongs to somebody!"
                ]
            )
        );

        $validator-> add(
            "name",
            new PresenceOf(
                [
                    "field" => "name",
                    "message" => "Make sure you fill in a character!"
                ]
            )
        );

        return($this->validate($validator));

    }


    public function initialize()
    {
        $this->belongsTo('users_id', 'Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'characters';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Characters[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Characters
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
