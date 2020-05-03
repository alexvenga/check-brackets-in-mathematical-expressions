<?php

namespace AlexVenga\BracketsService;

use AlexVenga\BracketsService\Exceptions\EmptySentenceException;
use AlexVenga\BracketsService\Exceptions\InvalidSentenceSymbolsException;

class BracketsChecker implements BracketsCheckerInterface
{

    /**
     * Array of possible brackets.
     *
     * @var array
     */
    protected $brackets;

    /**
     * Array of possible symbols.
     *
     * @var array
     */
    protected $possibleSymbols;

    /**
     * BracketsChecker constructor.
     *
     * @param array $brackets ['()', '{}', '[]']
     * @param array $possibleSymbols ["\n", "\t", "\r", " "]
     */
    public function __construct(array $brackets = ['()'], array $possibleSymbols = ["\n", "\t", "\r", " "])
    {
        $this->brackets = $brackets;
        $this->possibleSymbols = $possibleSymbols;

        return;
    }


    /**
     * Check brackets in sentence.
     *
     * @param string $sentence
     *
     * @return bool
     */
    public function check(string $sentence): bool
    {
        $this->validateSentence($sentence);
        return $this->isCorrect($this->clearSentence($sentence));
    }

    /**
     * Check only possible symbols in sentence and sentence not empty.
     *
     * @param string $sentence
     */
    protected function validateSentence(string $sentence): void
    {

        if (empty($sentence)) {
            throw new EmptySentenceException('Sentence can\'t be empty');
        }

        if (!empty(str_replace(array_merge(str_split(implode($this->brackets)), $this->possibleSymbols), '', $sentence))) {
            throw new InvalidSentenceSymbolsException('Sentence contains invalid symbols');
        }

        return;

    }

    /**
     * Check correct of brackets in sentence.
     *
     * @param string $sentence
     *
     * @return bool
     */
    protected function isCorrect(string $sentence): bool
    {
        $flagNeedRestart = true;
        while ($flagNeedRestart) {
            $flagNeedRestart = false;
            foreach ($this->brackets as $bracket) {
                while (strpos($sentence, $bracket) !== false) {
                    $flagNeedRestart = true;
                    $sentence = str_replace($bracket, '', $sentence);
                }
            }
        }


        if (!empty($sentence)) {
            return false;
        }

        return true;

    }

    /**
     * Remove all non brackets symbols.
     *
     * @param string $sentence
     *
     * @return string
     */
    protected function clearSentence(string $sentence): string
    {
        return str_replace($this->possibleSymbols, '', $sentence);
    }

}