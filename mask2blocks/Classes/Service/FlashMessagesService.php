<?php
declare(strict_types=1);

namespace T3dev\Mask2blocks\Service;

use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FlashMessagesService
 */
class FlashMessagesService
{
    protected string $queueIdentifier = 'core.template.flashMessages';

    /**
     * FlashMessagesService constructor.
     *
     * @param FlashMessageService $flashMessageService
     */
    public function __construct(
        protected FlashMessageService $flashMessageService
    ){}

    /**
     * @param string $queueIdentifier
     */
    public function setQueueIdentifier(string $queueIdentifier): void
    {
        $this->queueIdentifier = $queueIdentifier;
    }

    /**
     * @param string $message
     * @param string $title
     *
     * @throws Exception
     */
    public function addError(string $message, string $title = ''): void
    {
        $this->addMessage($message, $title, ContextualFeedbackSeverity::ERROR);
    }

    /**
     * @param string $message
     * @param string $title
     * @param int|ContextualFeedbackSeverity $severity
     *
     * @throws Exception
     */
    public function addMessage(
        string $message,
        string $title = '',
        ContextualFeedbackSeverity|int $severity = ContextualFeedbackSeverity::OK
    ): void {
        /* @var FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            $title,
            $severity,
            false
        );

        $this
            ->getMessageQueue()
            ->enqueue($flashMessage);
    }

    /**
     * @return FlashMessageQueue
     */
    private function getMessageQueue(): FlashMessageQueue
    {
        return $this->flashMessageService->getMessageQueueByIdentifier($this->queueIdentifier);
    }
}
