<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity=CourseClass::class, mappedBy="teacherID", orphanRemoval=true)
     */
    private $courseClasses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    public function __construct()
    {
        $this->courseClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|CourseClass[]
     */
    public function getCourseClasses(): Collection
    {
        return $this->courseClasses;
    }

    public function addCourseClass(CourseClass $courseClass): self
    {
        if (!$this->courseClasses->contains($courseClass)) {
            $this->courseClasses[] = $courseClass;
            $courseClass->setTeacherID($this);
        }

        return $this;
    }

    public function removeCourseClass(CourseClass $courseClass): self
    {
        if ($this->courseClasses->removeElement($courseClass)) {
            // set the owning side to null (unless already changed)
            if ($courseClass->getTeacherID() === $this) {
                $courseClass->setTeacherID(null);
            }
        }

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        if($avatar != null) {
            $this->avatar = $avatar;
        }
        return $this;
    }
}
