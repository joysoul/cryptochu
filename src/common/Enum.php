<?php
namespace cryptochu\common;

use cryptochu\exceptions\ValueException;
use cryptochu\utilities\TypeUtility;

/**
 * @author Emile Pels
 * @package cryptochu\common
 */
abstract class Enum
{
    /**
     * Error constants.
     */
    const ERROR_CHOICE_NOT_SUPPORTED = 'Choice "%s" is not supported for this enum.';

    /**
     * @var string
     */
    private $choice;

    /**
     * @param string $choice
     */
    protected function __construct(string $choice)
    {
        $this->assertChoiceSupported($choice);

        $this->choice = $choice;
    }

    /**
     * @param string $choice
     *
     * @throws ValueException
     */
    private function assertChoiceSupported(string $choice)
    {
        foreach ($this->getSupportedChoices() as $supportedChoice) {
            if ($choice === $supportedChoice) {
                return;
            }
        }

        throw new ValueException(vsprintf(self::ERROR_CHOICE_NOT_SUPPORTED, [$choice]));
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (is_null($other) || TypeUtility::isType($other, TypeUtility::getType($this)) === false) {
            return false;
        }

        return $this->getChoice() === $other->getChoice();
    }

    /**
     * @return string
     */
    public function getChoice(): string
    {
        return $this->choice;
    }

    /**
     * @return string[]
     */
    abstract protected function getSupportedChoices(): array;
}
