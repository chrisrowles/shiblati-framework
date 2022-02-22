<?php

namespace Shiblati\Framework\Models;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Shiblati\Framework\Validators\UserCreateValidator;
use Shiblati\Framework\Validators\UserLoginValidator;
use Shiblati\Framework\Validators\UserUpdateValidator;


class User extends Model
{
    /** @var int  */
    protected int $id;

    /** @var string  */
    protected string $email;

    /** @var mixed  */
    protected mixed $password;

    /** @var string  */
    protected string $remember_token;

    /** @var string  */
    protected string $name;

    /** @var string  */
    protected string $created_at;

    /** @var string  */
    protected string $updated_at;

    /** @var UserCreateValidator|UserUpdateValidator  */
    public UserCreateValidator|UserUpdateValidator $validated;

    /**
     * Set user attributes.
     *
     * @param UserCreateValidator|UserUpdateValidator $request
     * @return User
     */
    public function setAttributes(UserCreateValidator|UserUpdateValidator $request): User
    {
        try {
            if (isset($request->id)) {
                $this->setId($request->id);
            } else {
                $this->setPassword($request->password);
            }

            $this->setEmail($request->email);
            $this->setName($request->name);

            // We don't ever want to send these parameters back in the response
            unset($request->password, $request->validate);
            $this->validated = $request;

        } catch (Exception $e) {
            $this->log->error($e->getMessage());
        }

        return $this;
    }

    /**
     * Save a new user.
     *
     * @return User|false
     */
    public function create(): User|false
    {
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind(':email', $this->email);
        if ($this->db->single()) {
            return false;
        }

        try {
            $this->db->query(
                "INSERT INTO users (email, password, name, created_at, updated_at) 
                VALUES (:email, :password, :name, NOW(), NOW())"
            );

            $this->db->bind(':email', $this->email);
            $this->db->bind(':password', $this->password);
            $this->db->bind(':name', $this->name);

            $this->db->execute();
        } catch (Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }

        return $this;
    }

    /**
     * Update a user.
     *
     * @return bool
     */
    public function update(): bool
    {
        try {
            $this->db->query("UPDATE users SET email = :email, name = :name, updated_at = NOW() WHERE id = :id");

            $this->db->bind(':email', $this->email);
            $this->db->bind(':name', $this->name);
            $this->db->bind(':id', $this->id);

            $this->db->execute();
        } catch (Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Login a user.
     *
     * @param UserLoginValidator $request
     * @return array|bool
     * @throws Exception
     */
    #[ArrayShape(['status' => "string", 'user' => "mixed", 'message' => "string"])]
    public function login(UserLoginValidator $request): array|bool
    {
        $username = $request->username;
        $password = $request->password;

        $this->db->query('SELECT id, email, password, name, created_at FROM users WHERE email = :email');
        $this->db->bind(':email', $username);
        $user = $this->db->single();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);

            return $user;
        }

        return false;
    }

    /**
     * TODO replace with new session management (blog controller needs updating too).
     *
     * @deprecated
     * @return bool
     */
    public function check(): bool
    {
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated']
            && isset($_SESSION['name']) && $_SESSION['name'] === session_id();
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $this->setId($id);

            $this->db->query("DELETE FROM users WHERE id = :id");
            $this->db->bind(':id', $this->id);

            $this->db->execute();
        } catch (Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }

        return true;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getRememberToken(): string
    {
        return $this->remember_token;
    }

    public function setRememberToken(string $token): void
    {
        $this->remember_token = $token;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
