<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends Entity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;
    #[ORM\Column(type: 'string', unique: true)]
    private string $email;
    #[ORM\Column(type: 'string')]
    private string $first_name;
    #[ORM\Column(type: 'string')]
    private string $last_name;
    #[ORM\Column(type: 'string')]
    private string $surname;
    #[ORM\Column(type: 'string')]
    private string $phone;
    #[ORM\Column(type: 'string')]
    private string $role;
    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Schedule::class)]
    private Collection $schedules;
    #[ORM\JoinTable(name: 'courses_users')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'course_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Course::class)]
    private Collection $courses;

    public function __construct(array $data)
    {
        $this->schedules = new ArrayCollection();

        $this->setId($data['id'] ?? null);
        $this->setEmail($data['email']);
        $this->setFirstName($data['first_name']);
        $this->setLastName($data['last_name']);
        $this->setSurname($data['surname']);
        $this->setPhone($data['phone']);
        $this->setRole($data['role']);
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function setCourses(Collection $courses): void
    {
        $this->courses = $courses;
    }

    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function setSchedules(Collection $schedules): void
    {
        $this->schedules = $schedules;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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