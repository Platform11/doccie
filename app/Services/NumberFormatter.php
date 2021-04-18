<?php

namespace App\Services;

class NumberFormatter
{
    public static function format($value)
    {
      $mode = empty($value['transform']) ? null : $value['transform'];

      $formatter = new \NumberFormatter('nl_NL', \NumberFormatter::CURRENCY);

      return match ($mode) {
        'to_positive' => $value < 0 ? $formatter->formatCurrency((float)$value*-1, 'EUR') : $formatter->formatCurrency((float)$value, 'EUR'),
        'to_negative' => $value > 0 ? $formatter->formatCurrency((float)$value*-1, 'EUR') : $formatter->formatCurrency((float)$value, 'EUR'),
        'flip' => $formatter->formatCurrency((float)$value*-1, 'EUR')
      };
    }
}