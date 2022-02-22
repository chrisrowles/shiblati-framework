<?php

namespace Shiblati\Framework\Models;

use Exception;


class Session extends Model
{
    /** @var int $id  */
    protected int $id;

    /** @var string  */
    protected string $name;

    /** @var string  */
    protected string $user_id;

    /** @var string  */
    protected string $created_at;

    /** @var string  */
    protected string $updated_at;

    /** @var array  */
    protected array $session;

    /** @var array $validate */
    private array $validate = [
        'name',
        'id',
        'created_at'
    ];

    /** @var int TTL */
    public const TTL = 3600;

    /**
     * Set session attributes.
     *
     * @param array $data
     * @return Session
     */
    public function setAttributes(array $data): Session
    {
        try {
            $this->setName(session_id());
            $this->setUserId($data['user_id']);

            $this->session = ['name' => $this->name, 'user_id' => $this->user_id];
        } catch (Exception $e) {
            $this->log->error($e->getMessage());
        }

        return $this;
    }

    public function get(): mixed
    {
        if (empty($_SESSION)) {
            return false;
        }

        $this->db->query(
            "SELECT id, name, user_id, created_at FROM sessions WHERE name = :name AND user_id = :user_id"
        );
        $this->db->bind(':name', $_SESSION['name']);
        $this->db->bind(':user_id', $_SESSION['user']['id']);

        $session = $this->db->single();
        if ($session) {
            return $session;
        }

        return false;
    }

    /**
     * Create a new session.
     *
     * @param array $user
     * @return Session
     * @throws Exception
     */
    public function create(array $user): Session
    {
        if (!empty($_SESSION)) {
            $this->db->query(
                "SELECT id, name, user_id, created_at FROM sessions WHERE name = :name AND user_id = :user_id"
            );
            $this->db->bind(':name', $_SESSION['name']);
            $this->db->bind(':user_id', $_SESSION['user']['id']);
            if ($this->db->single()) {
                return $this;
            }
        }

        $this->validate($user);

        $this->db->query(
            "REPLACE INTO sessions (name, user_id, created_at, updated_at) VALUES (:name, :user_id, NOW(), NOW())"
        );

        $this->db->bind(':name', $this->name);
        $this->db->bind(':user_id', $this->user_id);

        $this->db->execute();

        $_SESSION['authenticated'] = true;
        $_SESSION['name'] = $this->name;
        $_SESSION['user'] = $user;

        return $this;
    }

    /**
     * Delete a session.
     *
     * @param bool $destroy
     * @return bool
     */
    public function destroy(bool $destroy = true): bool
    {
        try {
            $name = $_SESSION['name'];
            $user_id = $_SESSION['user']['id'];

            $this->db->query("DELETE FROM sessions WHERE name = :name AND user_id = :user_id");

            $this->db->bind(':name', $name);
            $this->db->bind(':user_id', $user_id);

            $this->db->execute();

            if ($destroy) {
                session_regenerate_id();
                session_destroy();
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validate post data.
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function validate(array $data): bool
    {
        if (!empty($data)) {
            $missing = [];
            foreach($this->validate as $attribute) {
                if (!isset($data[$attribute])) {
                    $missing[$attribute] = $attribute;
                }
            }

            if (empty($missing)) {
                return true;
            } else {
                throw new Exception('The following parameters are missing from the session: '
                    . implode(', ', $missing));
            }
        } else {
            throw new Exception('No parameters detected.');
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return void
     */
    public function getName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $id
     */
    public function setUserId(string $id): void
    {
        $this->user_id = $id;
    }
}
