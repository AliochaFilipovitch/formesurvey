<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question", orphanRemoval=true)
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity=QuestionMultipleChoice::class, mappedBy="question", orphanRemoval=true)
     */
    private $questionMultipleChoices;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->questionMultipleChoices = new ArrayCollection();
    }

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

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuestionMultipleChoice[]
     */
    public function getQuestionMultipleChoices(): Collection
    {
        return $this->questionMultipleChoices;
    }

    public function addQuestionMultipleChoice(QuestionMultipleChoice $questionMultipleChoice): self
    {
        if (!$this->questionMultipleChoices->contains($questionMultipleChoice)) {
            $this->questionMultipleChoices[] = $questionMultipleChoice;
            $questionMultipleChoice->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionMultipleChoice(QuestionMultipleChoice $questionMultipleChoice): self
    {
        if ($this->questionMultipleChoices->contains($questionMultipleChoice)) {
            $this->questionMultipleChoices->removeElement($questionMultipleChoice);
            // set the owning side to null (unless already changed)
            if ($questionMultipleChoice->getQuestion() === $this) {
                $questionMultipleChoice->setQuestion(null);
            }
        }

        return $this;
    }
}
