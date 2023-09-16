<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'schedules')]
class Schedule extends Entity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;
    #[ORM\Column(type: 'datetime')]
    private string $will_at;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'schedules')]
    #[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
    private User|null $teacher = null;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'schedules')]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    private User|null $student = null;

    public function __construct(array $data)
    {
        $this->setStudent($data['student']);
        $this->setTeacher($data['teacher']);
        $this->setWillAt($data['will_at']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getWillAt(): string
    {
        return $this->will_at;
    }

    public function setWillAt(string $will_at): void
    {
        $this->will_at = $will_at;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): void
    {
        $this->teacher = $teacher;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): void
    {
        $this->student = $student;
    }
}