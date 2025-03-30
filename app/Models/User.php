<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'users';

    // Keep id_user as primary key for database queries
    protected $primaryKey = 'id_user';

    // Declare all fillable fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'time_user',
        'phone',
        'address',
        'avatar',
        'born',
        'gt',
        'role',
        'api_token'
    ];

    // Explicitly declare all properties to avoid deprecation warnings
    public $id_user;
    public $email;
    public $password;
    public $name;
    public $status;
    public $time_user;
    public $phone;
    public $address;
    public $avatar;
    public $born;
    public $gt;
    public $role;
    public $api_token;

    // Map id_user to id for consistent access
    public function __get($key)
    {
        if ($key === 'id') {
            return $this->id_user;
        }

        return $this->{$key} ?? null;
    }

    /**
     * Verify if the given password matches the user's password
     * 
     * @param string $password The password to check
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Find a user by email
     * 
     * @param string $email The email to search for
     * @return User|null
     */
    public static function findByEmail($email)
    {
        $user = new static();
        $sql = "SELECT * FROM {$user->table} WHERE email = ? LIMIT 1";

        $statement = $user->connection->prepare($sql);
        $statement->execute([$email]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $user = new static();
        foreach ($result as $key => $value) {
            $user->{$key} = $value; // Direct property assignment instead of using setAttribute
        }

        return $user;
    }
}
