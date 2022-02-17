<?php

namespace ZeroDaHero\LaravelWorkflow\Events;

use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\TransitionBlocker;
use Symfony\Component\Workflow\TransitionBlockerList;
use Symfony\Component\Workflow\WorkflowInterface;

class GuardEvent extends BaseEvent
{
    private $transitionBlockerList;

    /**
     * {@inheritdoc}
     */
    public function __construct(object $subject, Marking $marking, Transition $transition, WorkflowInterface $workflow = null)
    {
        parent::__construct($subject, $marking, $transition, $workflow);

        $this->transitionBlockerList = new TransitionBlockerList();
    }

    public function getTransition(): Transition
    {
        return parent::getTransition();
    }

    public function isBlocked(): bool
    {
        return !$this->transitionBlockerList->isEmpty();
    }

    public function setBlocked(bool $blocked, string $message = null): void
    {
        if (!$blocked) {
            $this->transitionBlockerList->clear();

            return;
        }

        $this->transitionBlockerList->add(TransitionBlocker::createUnknown($message));
    }

    public function getTransitionBlockerList(): TransitionBlockerList
    {
        return $this->transitionBlockerList;
    }

    public function addTransitionBlocker(TransitionBlocker $transitionBlocker): void
    {
        $this->transitionBlockerList->add($transitionBlocker);
    }
}
