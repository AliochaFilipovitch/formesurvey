<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $question;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Survey::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryQuestion::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryQuestion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    public function getCategoryQuestion(): ?CategoryQuestion
    {
        return $this->categoryQuestion;
    }

    public function setCategoryQuestion(?CategoryQuestion $categoryQuestion): self
    {
        $this->categoryQuestion = $categoryQuestion;

        return $this;
    }
}
