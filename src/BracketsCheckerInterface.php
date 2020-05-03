<?php


namespace AlexVenga\BracketsService;

interface BracketsCheckerInterface
{

    public function check(string $sentence): bool;

}