<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categoryID;

    /**
     * @ORM\OneToMany(targetEntity=CourseClass::class, mappedBy="courseID", orphanRemoval=true)
     */
    private $courseClasses;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategoryID(): ?Category
    {
        return $this->categoryID;
    }

    public function setCategoryID(?Category $categoryID): self
    {
        $this->categoryID = $categoryID;

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
            $courseClass->setCourseID($this);
        }

        return $this;
    }

    public function removeCourseClass(CourseClass $courseClass): self
    {
        if ($this->courseClasses->removeElement($courseClass)) {
            // set the owning side to null (unless already changed)
            if ($courseClass->getCourseID() === $this) {
                $courseClass->setCourseID(null);
            }
        }

        return $this;
    }
}
