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
        'nigga',
        'motherfucker',
        'shit',
        'slut',
        'whore',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::passes((string) $value)) {
            $fail('Please choose a respectful name.');
        }
    }

    public static function passes(string $value): bool
    {
        $normalized = mb_strtolower((string) preg_replace('/[^\pL]+/u', ' ', $value));
        $words = preg_split('/\s+/u', trim($normalized), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            if (in_array($word, self::BLOCKED_WORDS, true)) {
                return false;
            }
        }

        return true;
    }
}
