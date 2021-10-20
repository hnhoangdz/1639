<?php

namespace App\Entity;

use App\Repository\CourseClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseClassRepository::class)
 */
class CourseClass
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
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="courseClasses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacherID;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="courseClasses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $courseID;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, inversedBy="courseClasses")
     */
    private $studentID;

    /**
     * @ORM\Column(type="date")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEnd;

    public function __construct()
    {
        $this->studentID = new ArrayCollection();
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

    public function getTeacherID(): ?Teacher
    {
        return $this->teacherID;
    }

    public function setTeacherID(?Teacher $teacherID): self
    {
        $this->teacherID = $teacherID;

        return $this;
    }

    public function getCourseID(): ?Course
    {
        return $this->courseID;
    }

    public function setCourseID(?Course $courseID): self
    {
        $this->courseID = $courseID;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudentID(): Collection
    {
        return $this->studentID;
    }

    public function addStudentID(Student $studentID): self
    {
        if (!$this->studentID->contains($studentID)) {
            $this->studentID[] = $studentID;
        }

        return $this;
    }

    public function removeStudentID(Student $studentID): self
    {
        $this->studentID->removeElement($studentID);

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }
}
