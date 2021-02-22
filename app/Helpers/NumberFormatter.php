<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function format($number, $mode = null)
    { 
      $formatter = new \NumberFormatter('nl_NL', \NumberFormatter::CURRENCY);

      if($mode === 'to_positive')
      {
        return $number < 0 ? $formatter->formatCurrency((float)$number*-1, 'EUR') : $formatter->formatCurrency((float)$number, 'EUR');
      }

      if($mode === 'to_negative')
      {
        return $number < 0 ? $formatter->formatCurrency((float)$number*-1, 'EUR') : $formatter->formatCurrency((float)$number, 'EUR');
      }

      if($mode === 'flip')
      {
        return $formatter->formatCurrency((float)$number*-1, 'EUR');
      }

      return $formatter->formatCurrency((float)$number, 'EUR');
    }
}