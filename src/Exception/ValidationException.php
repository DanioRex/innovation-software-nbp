<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    public function __construct(
        protected ConstraintViolationListInterface $violations,
    ) {
        parent::__construct($this->getJoinedMessages());
    }

    protected function getMessages(): array
    {
        $messages = [];
        foreach ($this->violations as $violation) {
            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $messages;
    }

    protected function getJoinedMessages(): string
    {
        $messages = [];

        foreach ($this->getMessages() as $property => $messagesArray) {
            $messages[] = sprintf(
                '%s: %s',
                $property,
                implode(' ', $messagesArray),
            );
        }

        return implode('; ', $messages);
    }
}
