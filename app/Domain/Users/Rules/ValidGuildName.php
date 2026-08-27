<?php

namespace App\Domain\Users\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidGuildName implements ValidationRule
{
    private const BLOCKED_WORDS = [
        'ass',
        'asshole',
        'bastard',
        'bitch',
        'damn',
        'fuck',
        'motherfucker',
        'shit',
        'slut',
        'whore',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = mb_strtolower((string) preg_replace('/[^\pL]+/u', ' ', $value));
        $words = preg_split('/\s+/u', trim($normalized), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            if (in_array($word, self::BLOCKED_WORDS, true)) {
                $fail('Please choose a respectful name.');

                return;
            }
        }
    }
}
